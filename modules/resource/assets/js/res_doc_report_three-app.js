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
    
    $scope.three_top = [];
    $scope.three_below = [];
    $scope.three_otd = [];

    // ส่วนตัดเก็บ สามตัวบน
    $scope.amount_three_top = [];
    $scope.amount_total_three_top = {};

    // ส่วนตัดเก็บ สามตัวล่าง
    $scope.amount_three_below = [];
    $scope.amount_total_three_below = {};

    // ส่วนตัดเก็บ สามตัวโต๊ด
    $scope.amount_three_otd = [];
    $scope.amount_total_three_otd = {};

    // ส่วนตัดส่ง สามตัวบน
    $scope.line_total_three_top = [];
    $scope.total_three_top = {};

    // ส่วนตัดส่ง 3ตัวล่าง
    $scope.line_total_three_below = [];
    $scope.total_three_below = {};

    // ส่วนตัดส่ง 3ตัวโต๊ด
    $scope.line_total_three_otd = [];
    $scope.total_three_otd = {};

    // top
    $http.get('/resource/res-doc-report/get-three-top')
        .then(function (response) {
            $scope.three_top = response.data.arr;
            $scope.sum_top = response.data.sum;
        });
    // below
    $http.get('/resource/res-doc-report/get-three-below')
        .then(function (response) {
            $scope.three_below = response.data.arr;
            $scope.sum_below = response.data.sum;
        });

    // Otd
    $http.get('/resource/res-doc-report/get-three-otd')
        .then(function (response) {
            $scope.three_otd = response.data.arr;
            $scope.sum_otd = response.data.sum;
        });


    // ส่วนตัดเก็บ
    $scope.SaveCut = function (){
        console.log("save cut", {msg_top: $scope.three_top, msg_below: $scope.three_below, msg_otd: $scope.three_otd});
        var data = {
            three_top: $scope.three_top,
            three_below: $scope.three_below,
            three_otd: $scope.three_otd
        };
        $http.post('/resource/res-doc-report/save-cut-three', data)
            .then(function (response) {
                console.log(response.data);
                $scope.amount_three_top = response.data.res_amount_three_top;
                $scope.amount_total_three_top = response.data.sum_three_top;

                $scope.amount_three_below = response.data.res_amount_three_below;
                $scope.amount_total_three_below = response.data.sum_three_below;

                $scope.amount_three_otd = response.data.res_amount_three_otd;
                $scope.amount_total_three_otd = response.data.sum_three_otd;
            });
    }

    // ส่วนตัดส่ง
    $scope.SaveSend = function (){
        var data = {
            three_top: $scope.three_top,
            three_below: $scope.three_below,
            three_otd: $scope.three_otd
        };
        $http.post('/resource/res-doc-report/save-send-three', data)
            .then(function (response) {
                console.log(response.data);
                $scope.line_total_three_top = response.data.res_three_top;
                $scope.total_three_top = response.data.sum_three_top;

                $scope.line_total_three_below = response.data.res_three_below;
                $scope.total_three_below = response.data.sum_three_below;
                
                $scope.line_total_three_otd = response.data.res_three_otd;
                $scope.total_three_otd = response.data.sum_three_otd;

                console.log($scope.line_total_three_top.length);
            });
    }

    $scope.exportData = function () {
        var blob = new Blob([document.getElementById('exportable').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, "สรุปยอดตัดเก็บ_เลขสามตัว.xls");
    };

    $scope.exportDataSend = function (){
        var blob = new Blob([document.getElementById('exportableSend').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, "สรุปยอดตัดส่ง_เลขสามตัว.xls");
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
                pdfMake.createPdf(docDefinition).download("สรุปยอดตัดเก็บ_เลขสามตัว.pdf");
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
                pdfMake.createPdf(docDefinition).download("สรุปยอดตัดส่ง_เลขสามตัว.pdf");
            }
        });
    }
});