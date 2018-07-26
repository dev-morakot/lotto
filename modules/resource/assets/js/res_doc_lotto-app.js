'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("myapp", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model','bic.common','bic.module'
]);

app.config(['$httpProvider', function ($httpProvider) {
        $httpProvider.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
        $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
    }]);


app.factory("Resource", function ($http) {

    var items = [];
    for (var i = 0; i < 50; i++) {
        items.push({id: i, name: "name " + i, description: "description " + i});
    }
    var resources = [];

    return {
        init: function () {
            return resources;
        },
        get: function (offset, limit) {
            return items.slice(offset, offset + limit);
        },
        total: function () {
            return items.length;
        }
    };
});

app.animation('.slide', function () {
    var NG_HIDE_CLASS = 'ng-hide';
    return {
        beforeAddClass: function (element, className, done) {
            if (className === NG_HIDE_CLASS) {
                element.slideUp(done);
            }
        },
        removeClass: function (element, className, done) {
            if (className === NG_HIDE_CLASS) {
                element.hide().slideDown(done);
            }
        }
    };
});

app.filter('formatDate', function () {
    return function (input) {
        // use moment.js
        var date = moment(input);

        return date.format('DD/MM/YYYY');
    };
});
app.filter('uomFilter', function ($filter) {
    return function (uoms, _product) {
        if (_product) {
            var _category_id = $filter('filter')(uoms, {id: _product.uom_id})[0].category_id;
            console.log(_category_id);
            return $filter('filter')(uoms, {category_id: _category_id});
        } else {
            return uoms;
        }
    };
});
/**
 * AngularJS default filter with the following expression:
 * "person in people | filter: {name: $select.search, age: $select.search}"
 * performs an AND between 'name: $select.search' and 'age: $select.search'.
 * We want to perform an OR.
 */
app.filter('propsFilter', function () {
    return function (items, props) {
        var out = [];

        if (angular.isArray(items)) {
            var keys = Object.keys(props);

            items.forEach(function (item) {
                var itemMatches = false;

                for (var i = 0; i < keys.length; i++) {
                    var prop = keys[i];
                    var text = props[prop].toLowerCase();
                    if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }
                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }

        return out;
    };
});

/*
 * Directive
 */
app.directive('convertToNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (val) {
                //saves integer to model null as null
                return val == null ? null : parseInt(val, 10);
            });
            ngModel.$formatters.push(function (val) {
                //return string for formatter and null as null
                return val == null ? null : '' + val;
            });
        }
    };
});

app.directive('stringToNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (value) {
                return value == null ? null : (parseFloat(value) || 0);
            });
            ngModel.$formatters.push(function (value) {
                return parseFloat(value);
            });
        }
    };
});

/*
 * Controller
 */
app.controller("FormController", function ($scope, $http,$timeout,
        $location, $window,
        $filter, uibDateParser, Resource) {
    // Resources
    $scope.msg = false;
    $scope.model = {};
    $scope.users = [];
    $scope.lottos = [];
    $scope.modline = {
        amount_total: 0
    }
    // openAddLine

    $scope.openAddLine = function (){
        console.log("openAddLine" , $scope.model);
        var model = $scope.model;
        console.log("lenght", Object.keys(model.number).length);
        var numbers = Object.keys(model.number).length;
       
        if(numbers > 3) {
            bootbox.alert('กรุณาใส่จำนวนเลขให้ถูกต้อง');
        }

      if(!model.user) {
          bootbox.alert("กรุณาใส่ชื่อผู้ซื้อ");
          return;
      }

        if(numbers === 3 && model.top_amount > 1) {
            var top_three = {
                firstname: model.user.firstname,
                user_id: model.user.id,
                top_amount: model.top_amount,
                amount: model.top_amount,
                number: model.number,
                type: 'สามตัวบน',
            };        
            
            $scope.lottos.push(top_three);
            $scope.model = {};
            $scope.model.user = {
                id: model.user.id,
                firstname: model.user.firstname,
                lastname: model.user.lastname
            };
            
            $scope.calculationStack();
            
        }

        if(numbers === 3 && model.below_amount > 1) {
            var below_amount = {
                firstname: model.user.firstname,
                user_id: model.user.id,
                below_amount: model.below_amount,
                amount: model.below_amount,
                number: model.number,
                type: 'สามตัวล่าง',
            };
            $scope.lottos.push(below_amount);
            $scope.model = {};
            $scope.model.user = {
                id: model.user.id,
                firstname: model.user.firstname,
                lastname: model.user.lastname
            };
            $scope.calculationStack();
            
        }
        if(numbers === 3 && model.otd_amount > 1) {
            var otd_amount = {
                firstname: model.user.firstname,
                user_id: model.user.id,
                otd_amount: model.otd_amount,
                amount: model.otd_amount,
                number: model.number,
                type: 'สามตัวโต๊ด'
            };
            $scope.lottos.push(otd_amount);
            $scope.model = {};
            $scope.model.user = {
                id: model.user.id,
                firstname: model.user.firstname,
                lastname: model.user.lastname
            };
            $scope.calculationStack();
            
        }

        if(numbers === 2 && model.top_amount > 1) {
            var top_two = {
                firstname: model.user.firstname,
                user_id: model.user.id,
                top_amount: model.top_amount,
                amount: model.top_amount,
                number: model.number,
                type: 'สองตัวบน'
            };
            $scope.lottos.push(top_two);
            $scope.model = {};
            $scope.model.user = {
                id: model.user.id,
                firstname: model.user.firstname,
                lastname: model.user.lastname
            };
            $scope.calculationStack();
            
        }
        if(numbers === 2 && model.below_amount > 1) {
            var below = {
                firstname: model.user.firstname,
                user_id: model.user.id,
                below_amount: model.below_amount,
                amount: model.below_amount,
                number: model.number,
                type: 'สองตัวล่าง'
            };
            $scope.lottos.push(below);
            $scope.model = {};
            $scope.model.user = {
                id: model.user.id,
                firstname: model.user.firstname,
                lastname: model.user.lastname
            };
            $scope.calculationStack();
            
        }
        if(numbers === 2 && model.otd_amount > 1) {
            bootbox.alert('ตัวเลข 2 ตัวไม่สามารถซื้อโต๊ด/กลับเลขได้');
            return false;
        }
        
    }

    $scope.doRemoveLine = function (_line) {
        var index = $scope.lottos.indexOf(_line);
        $scope.lottos.splice(index, 1);
        $scope.calculationStack();
    }

    $scope.calculationStack = function (){
        $scope.sumAmountTotal();
    }

    $scope.sumAmountTotal = function () {
       console.log("sumAmountTotal =>", $scope.lottos);
       
       var sum = 0;
       angular.forEach($scope.lottos, function (v, k) {
          var total = v.amount;
          sum += (parseFloat(total));
        
        });
        $scope.modline.amount_total = sum; 
        console.log("amount_total", $scope.modline.amount_total);
    }
   
    //
    console.log("initial");
    $scope.formSave = function () {
        var data = {
            modline: $scope.modline,
            lottos: $scope.lottos
        };
        console.log(data);
        $http.post('/resource/res-doc-lotto/form-save', data)
            .then(function (response) {
                bootbox.confirm('บันทึกรายการเรียบร้อย', function (result) {
                    if(result) {
                        console.log(response.data);
                        navigateToView();
                    }
                });
            }, function errorCallback(response) {
            console.log('save-lotto error', response);
            switch(response.status) {
                case 422:
                    //validation error
                    var msg = "<ul>";
                    angular.forEach(response.data, function (value, key) {
                        console.log('failed prop', key, value);
                        msg = msg + "<li>"+value+"</li>";
                    });
                    msg += "</ul>";
                    bootbox.dialog({
                        message: msg,
                        backdrop: true,
                        onEscape: true
                    });
                    break;
                default:
                    bootbox.alert(response.data.message);
                    break;
            }
        });
    }
    function navigateToView() {
        $window.location.href = '/resource/res-doc-lotto/';
    }
});

