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

app.filter('formatDate', function () {
    return function (input) {
        // use moment.js
        var date = moment(input);

        return date.format('DD/MM/YYYY');
    };
});

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
        $filter, uibDateParser) {

	 $scope.lotto_user = [];
	 $scope.id = $("#id").val()
	$scope.dis = $("#dis").val()

	  var data = {
            id: $scope.id,
           
        };
        $http.post('/resource/res-doc-report/get-lotto-user', data)
            .then(function (response) {
                console.log(response.data);
                $scope.lotto_user = response.data.res;
               	$scope.sum = response.data.sum;
               	$scope.total = ($scope.sum - $scope.dis)
               	$scope.createdate = response.data.date
            });

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

    $scope.exportData = function () {
        var blob = new Blob([document.getElementById('exportable').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, "สรุปยอดตัดเก็บ_เลขสามตัว.xls");
    };


    $scope.downloadPdfSend = function (){
        html2canvas(document.getElementById('exportableSend'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 300,
                    }],
                    pageSize: 'A4',
            		pageMargins: [20,20,20,20],
                };
                pdfMake.createPdf(docDefinition).open("สรุปยอดซื้อตามรายชื่อลูกค้า.pdf");
            }
        });
    }
});