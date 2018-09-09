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


app.directive('exportTable', function(){
    var link = function ($scope, elm, attr) {
      $scope.$on('export-pdf', function (e, d) {
          elm.tableExport({ type: 'pdf', escape: false });
      });
      $scope.$on('export-excel', function (e, d) {
          elm.tableExport({ type: 'excel', escape: false });
      });
      $scope.$on('export-doc', function (e, d) {
          elm.tableExport({ type: 'doc', escape: false });
      });
      $scope.$on('export-csv', function (e, d) {
          elm.tableExport({ type: 'csv', escape: false });
      });
  }
  return {
      restrict: 'C',
      link: link
  }
});

/*
 * Controller
 */
app.controller("FormController", function ($scope, $http,$timeout,
        $location, $window,
        $filter, uibDateParser, Resource) {
    
    $scope.two_top = [];
    $scope.two_below = [];

    // ส่วนตัดเก็บ
    $scope.two_cut_top_amount = [];
    $scope.two_cut_below_amount = [];

    // ส่วนตัดส่ง
    $scope.two_send_top_amount = [];
    $scope.two_send_below_amount = [];
    $scope.total_top = {};
    $scope.total_below = {};


    // top
    $http.get('/resource/res-doc-report/get-two-top')
        .then(function (response) {
            $scope.two_top = response.data.arr;
            $scope.sum_top = response.data.sum;
        });
    // below
    $http.get('/resource/res-doc-report/get-two-below')
        .then(function (response) {
            $scope.two_below = response.data.arr;
            $scope.sum_below = response.data.sum;
        });

    // ส่วนตัด เก็บ
    $scope.SaveCut = function (){
        console.log("save cut", {msg_top: $scope.two_top, msg_below: $scope.two_below});
        var data = {
            two_top: $scope.two_top,
            two_below: $scope.two_below
        };
        $http.post("/resource/res-doc-report/save-as-cut", data)
            .then(function (response) {
                console.log(response.data);
                $scope.two_cut_top_amount = response.data.res_top_amount;
                $scope.two_cut_below_amount = response.data.res_below_amount;
                $scope.amount_total_top = response.data.sum_top;
                $scope.amount_total_below = response.data.sum_below;
                
            });
    }

    // ส่วนตัดส่ว
    $scope.SaveSend = function (){ 
        var data = {
            two_top: $scope.two_top,
            two_below: $scope.two_below
        };

        $http.post('/resource/res-doc-report/save-send-lotto', data)
            .then(function (response) {
                console.log(response.data);
                $scope.two_send_top_amount = response.data.res_send_top_amount;
                $scope.two_send_below_amount = response.data.res_send_below_amount;
                $scope.total_top = response.data.sum_top;
                $scope.total_below = response.data.sum_below;
            });
    }


    $scope.exportData = function () {
        var blob = new Blob([document.getElementById('exportable').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, "สรุปยอดตัดเก็บ_เลขสองตัว.xls");
    };

    $scope.exportDataSend = function (){
        var blob = new Blob([document.getElementById('exportableSend').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, "สรุปยอดตัดส่ง_เลขสองตัว.xls");
    }

    $scope.exportAction = function (option) {
        switch (option) {
            case 'pdf': $scope.$broadcast('export-pdf', {}); 
                break; 
            case 'excel': $scope.$broadcast('export-excel', {});
                break; 
            case 'doc': $scope.$broadcast('export-doc', {});
                break;
            case 'csv': $scope.$broadcast('export-csv', {});
                break;
            default: console.log('no event caught'); 
        }
    }

    $scope.downloadPdf = function () {
        html2canvas(document.getElementById('exportable'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500,
                    }]
                };
                pdfMake.createPdf(docDefinition).download("สรุปยอดตัดเก็บ_เลขสองตัว.pdf");
            }
        });
    };

    $scope.downloadPdfSend = function (){
        html2canvas(document.getElementById('exportableSend'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500,
                    }]
                };
                pdfMake.createPdf(docDefinition).download("สรุปยอดตัดส่ง_เลขสองตัว.pdf");
            }
        });
    }

});