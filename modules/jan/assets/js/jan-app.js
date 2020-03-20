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

	    $scope.lottos = [];
        $scope.model = {
            "state": false,
        }
        $scope.modline = {
            "type": "หวยไทย",
            amount_total: 0,
            amount_total_remain: 0,
            discount: 0,
            discount_two: 0,
            discount_three: 0,
            discount_run: 0
        }
        $scope.customers = []

        $scope.clearLotto = function () {
            $scope.model = {
               
                "state": false
            }
        }

        $scope.addLotto = function() {
           
            if ($scope.model.state === false) {

                if($scope.model.number_slowly === undefined || $scope.model.number_slowly === "" || $scope.model.number_slowly === "null") {
                    bootbox.alert('กรุณาใส่ตัวเลข');
                    return false;
                 }
                 if($scope.model.number_slowly.length > 4) {
                     bootbox.alert('กรุณาใส่จำนวนเลขให้ถูกต้อง');
                     return
                }
                // ที่ละตัว
                if($scope.model.number_slowly.length === 2) {
                    var data = {
                        "number": $scope.model.number_slowly,
                        "top_amount": ($scope.model.top_amount)?$scope.model.top_amount:0,
                        "below_amount": ($scope.model.below_amount)?$scope.model.below_amount:0,
                        "otd_amount": ($scope.model.otd_amount)?$scope.model.otd_amount:0
                    }
                    $scope.lottos.push(data)
                   
                }
                if($scope.model.number_slowly.length === 3) {
                    var data = {
                        "number": $scope.model.number_slowly,
                        "top_amount": ($scope.model.top_amount)?$scope.model.top_amount:0,
                        "below_amount": ($scope.model.below_amount)?$scope.model.below_amount:0,
                        "otd_amount": ($scope.model.otd_amount)?$scope.model.otd_amount:0
                    }
                    $scope.lottos.push(data)
                   
                }
                 $scope.sumAmountTotal();
                
            } else {
                 if($scope.model.number_quick === undefined || $scope.model.number_quick === "" || $scope.model.number_quick === "null") {
                    bootbox.alert('กรุณาใส่ตัวเลข');
                    return false;
                }
                // แทงเร็ว
                var result = $scope.model.number_quick[0].split("\n")
                var mydata = {};
                
                angular.forEach(result, function (v, k) {
                    mydata = {
                        "number": v, 
                        "top_amount": ($scope.model.top_amount)?$scope.model.top_amount:0,
                        "below_amount": ($scope.model.below_amount)?$scope.model.below_amount:0,
                        "otd_amount": ($scope.model.otd_amount)?$scope.model.otd_amount:0
                    }
                    
                    $scope.lottos.push(mydata)
                    
                 });
                if ($scope.lottos.length > 0) {
                       
                    $scope.model = {
                        
                        "state": $scope.model.state,
                                    
                    }
                }
                $scope.sumAmountTotal();
            }
        }

        $scope.sumAmountTotal = function () {
          
           var sum = 0;
         
           angular.forEach($scope.lottos, function (v, k) {
              var total = parseInt((v.top_amount)?v.top_amount: 0) + parseInt((v.below_amount)?v.below_amount:0) + parseInt((v.otd_amount)?v.otd_amount:0);
              sum += (parseFloat(total));

            });
            $scope.modline.amount_total = sum;
           
            console.log("amount_total", $scope.modline.amount_total);
           
        }

        $scope.disTwo = function() {
            var sum = 0;
           var two = 0;
           var three = 0;
           var run = 0;
           angular.forEach($scope.lottos, function (v, k) {
              var total = parseInt((v.top_amount)?v.top_amount: 0) + parseInt((v.below_amount)?v.below_amount:0) + parseInt((v.otd_amount)?v.otd_amount:0);
              sum += (parseFloat(total));

              if($scope.modline.discount_two !== undefined || $scope.modline.discount_two !== "null" || $scope.modline.discount_two !== "") {
                two = (parseFloat(sum) * parseFloat($scope.modline.discount_two)) / 100
              } 
                if($scope.modline.discount_three !== undefined || $scope.modline.discount_three !== "null" || $scope.modline.discount_three !== "") {
                three = (parseFloat(sum) * parseFloat($scope.modline.discount_three)) / 100
              } 
               if($scope.modline.discount_run !== undefined || $scope.modline.discount_run !== "null" || $scope.modline.discount_run !== "") {
                run = (parseFloat(sum) * parseFloat($scope.modline.discount_run)) / 100
              } 
              console.log('two', two)
              console.log('three', three)
              console.log('run', run)
            
            });
            $scope.modline.amount_total = sum;
            $scope.modline.discount = (parseFloat((two)?two:0) + parseFloat((three)?three:0) + parseFloat((run)?run:0 ));
            $scope.modline.amount_total_remain = (parseFloat($scope.modline.amount_total) - parseFloat($scope.modline.discount))
           
            console.log("amount_total", $scope.modline.discount);
        }

        $scope.doRemoveLine = function (_line) {
            var index = $scope.lottos.indexOf(_line);
            $scope.lottos.splice(index, 1);
            $scope.sumAmountTotal();
             $scope.disTwo()
        
        }

        $scope.saveAs = function (line) {

            if($scope.lottos.length <= 0) {
                 bootbox.alert('กรุณาคีย์หวยเพื่อแสดงผล');
                return
            }
            if($scope.modline.name === undefined || $scope.modline.name === "null" || $scope.modline.name === "") {
                bootbox.alert('กรุณาใส่ชื่อลูกค้า');
                return
            }
             


             var data = {
                modline: $scope.modline,
                lottos: $scope.lottos
            };
            console.log(data);
            $http.post('/jan/default/form-save', data)
                .then(function (response) {
                    bootbox.confirm('บันทึกรายการเรียบร้อย', function (result) {
                        if(result) {
                            console.log(response.data);
                            navigateToView();
                        }
                });
            });
        }
        function navigateToView() {
            $window.location.href = '/jan/default/';
        }


        $scope.door19 = function () {
           if ($scope.model.state === true) {

                if($scope.model.number_quick === undefined || $scope.model.number_quick === "" || $scope.model.number_quick === "null") {
                    bootbox.alert('กรุณาใส่ตัวเลข');
                    return false;
                }

                var result = $scope.model.number_quick[0].substr(0,1);
                if (result ===  "0") {
                    $scope.model.number_quick = ["00\n01\n02\n03\n04\n05\n06\n07\n08\n09\n10\n20\n30\n40\n50\n60\n70\n80\n90"]
                } 
                if (result ===  "1") {
                    $scope.model.number_quick = ["10\n11\n12\n13\n14\n15\n16\n17\n18\n19\n01\n21\n31\n41\n51\n61\n71\n81\n91"]
                } 
                if (result ===  "2") {
                    $scope.model.number_quick = ["20\n21\n22\n23\n24\n25\n26\n27\n28\n29\n02\n12\n32\n42\n52\n62\n72\n82\n92"]
                } 
                if (result ===  "3") {
                    $scope.model.number_quick = ["30\n31\n32\n33\n34\n35\n36\n37\n38\n39\n03\n13\n23\n43\n53\n63\n73\n83\n93"]
                } 
                if (result ===  "4") {
                    $scope.model.number_quick = ["40\n41\n42\n43\n44\n45\n46\n47\n48\n49\n04\n14\n24\n34\n54\n64\n74\n84\n94"]
                }
                if (result ===  "5") {
                    $scope.model.number_quick = ["50\n51\n52\n53\n54\n55\n56\n57\n58\n59\n05\n15\n25\n35\n45\n65\n75\n85\n95"]
                }
                if (result ===  "6") {
                    $scope.model.number_quick = ["60\n61\n62\n63\n64\n65\n66\n67\n68\n69\n06\n16\n26\n36\n46\n56\n76\n86\n96"]
                }
                if (result ===  "7") {
                    $scope.model.number_quick = ["70\n71\n72\n73\n74\n75\n76\n77\n78\n79\n07\n17\n27\n37\n47\n57\n67\n87\n97"]
                }
                if (result ===  "8") {
                    $scope.model.number_quick = ["80\n81\n82\n83\n84\n85\n86\n87\n88\n89\n08\n18\n28\n38\n48\n58\n68\n78\n98"]
                }
                if (result ===  "9") {
                    $scope.model.number_quick = ["90\n91\n92\n93\n94\n95\n96\n97\n98\n99\n09\n19\n29\n39\n49\n59\n69\n79\n89"]
                }

           }
        }

        // รูดหน้า
        $scope.ruleBefore = function() {
            if ($scope.model.state === true) {

                if($scope.model.number_quick === undefined || $scope.model.number_quick === "" || $scope.model.number_quick === "null") {
                    bootbox.alert('กรุณาใส่ตัวเลข');
                    return false;
                }

                var result = $scope.model.number_quick[0].substr(0,1);
                if (result ===  "0") {
                    $scope.model.number_quick = ["00\n01\n02\n03\n04\n05\n06\n07\n08\n09"]
                }
                if (result ===  "1") {
                    $scope.model.number_quick = ["10\n11\n12\n13\n14\n15\n16\n17\n18\n19"]
                }
                if (result ===  "2") {
                    $scope.model.number_quick = ["20\n21\n22\n23\n24\n25\n26\n27\n28\n29"]
                }
                if (result ===  "3") {
                    $scope.model.number_quick = ["30\n31\n32\n33\n34\n35\n36\n37\n38\n39"]
                }
                if (result ===  "4") {
                    $scope.model.number_quick = ["40\n41\n42\n43\n44\n45\n46\n47\n48\n49"]
                }
                if (result ===  "5") {
                    $scope.model.number_quick = ["50\n51\n52\n53\n54\n55\n56\n57\n58\n59"]
                }
                if (result ===  "6") {
                    $scope.model.number_quick = ["60\n61\n62\n63\n64\n65\n66\n67\n68\n69"]
                }
                if (result ===  "7") {
                    $scope.model.number_quick = ["70\n71\n72\n73\n74\n75\n76\n77\n78\n79"]
                }
                if (result ===  "8") {
                    $scope.model.number_quick = ["80\n81\n82\n83\n84\n85\n86\n87\n88\n89"]
                }
                if (result ===  "9") {
                    $scope.model.number_quick = ["90\n91\n92\n93\n94\n95\n96\n97\n98\n99"]
                } 
            }
        }

        // รูดหลัง
        $scope.ruleAfter = function() {
            if ($scope.model.state === true) {

                if($scope.model.number_quick === undefined || $scope.model.number_quick === "" || $scope.model.number_quick === "null") {
                    bootbox.alert('กรุณาใส่ตัวเลข');
                    return false;
                }

                var result = $scope.model.number_quick[0].substr(0,1);
                if (result ===  "0") {
                    $scope.model.number_quick = ["00\n10\n20\n30\n40\n50\n60\n70\n80\n90"]
                }
                if (result ===  "1") {
                    $scope.model.number_quick = ["01\n11\n21\n31\n41\n51\n61\n71\n81\n91"]
                }
                if (result ===  "2") {
                    $scope.model.number_quick = ["02\n12\n22\n32\n42\n52\n62\n72\n82\n92"]
                }
                if (result ===  "3") {
                    $scope.model.number_quick = ["03\n13\n23\n33\n43\n53\n63\n73\n83\n93"]
                }
                if (result ===  "4") {
                    $scope.model.number_quick = ["04\n14\n24\n34\n44\n54\n64\n74\n84\n94"]
                }
                if (result ===  "5") {
                    $scope.model.number_quick = ["05\n15\n25\n35\n45\n55\n65\n75\n85\n95"]
                }
                if (result ===  "6") {
                    $scope.model.number_quick = ["06\n16\n26\n36\n46\n56\n66\n76\n86\n96"]
                }
                if (result ===  "7") {
                    $scope.model.number_quick = ["07\n17\n27\n37\n47\n57\n67\n77\n87\n97"]
                }
                if (result ===  "8") {
                    $scope.model.number_quick = ["08\n18\n28\n38\n48\n58\n68\n78\n88\n98"]
                }
                if (result ===  "9") {
                    $scope.model.number_quick = ["09\n19\n29\n39\n49\n59\n69\n79\n89\n99"]
                } 
            }

        }

        $scope.TwoGoBack = function (){
             if ($scope.model.state === true) {

                if($scope.model.number_quick === undefined || $scope.model.number_quick === "" || $scope.model.number_quick === "null") {
                    bootbox.alert('กรุณาใส่ตัวเลข');
                    return false;
                }
                var result = $scope.model.number_quick[0]
                var code_temp = result.split('').reverse().join('');
                
                $scope.model.number_quick = [result+"\n"+code_temp].sort()
               
            }
        }

        $scope.door6 = function () {
             if ($scope.model.state === true) {

                if($scope.model.number_quick === undefined || $scope.model.number_quick === "" || $scope.model.number_quick === "null") {
                    bootbox.alert('กรุณาใส่ตัวเลข');
                    return false;
                }
                var a = $scope.model.number_quick[0].substr(-3,1);
                var b = $scope.model.number_quick[0].substr(-2,1);
                var c = $scope.model.number_quick[0].substr(-1);
                var n1 = 0
                var n2 = 0
                var n3 = 0
                var n4 = 0
                var n5 = 0
                var n6 = 0
                if((a == b)||(a == c)||(b == c)){
                    if(a == b){
                        n1 = a+a+c;
                        n2 = a+c+a;
                        n3 = c+a+a;
                    }else if(a == c){
                        n1 = a+b+a;
                        n2 = a+a+b;
                        n3 = b+a+a;
                    }else{
                        n1 = a+b+b;
                        n2 = b+b+a;
                        n3 = b+a+b;
                    }

                } else {
                    n1 = a+b+c;
                    n2 = a+c+b;
                    n3 = b+a+c; 
                    n4 = b+c+a; 
                    n5 = c+a+b; 
                    n6 = c+b+a; 
                }
               
                $scope.model.number_quick = [n1+"\n"+n2+"\n"+n3+"\n"+n4+"\n"+n5+"\n"+n6].sort()
                
            }
        }


        $scope.door3 = function () {
             if ($scope.model.state === true) {

                if($scope.model.number_quick === undefined || $scope.model.number_quick === "" || $scope.model.number_quick === "null") {
                    bootbox.alert('กรุณาใส่ตัวเลข');
                    return false;
                }
                var a = $scope.model.number_quick[0].substr(-3,1);
                var b = $scope.model.number_quick[0].substr(-2,1);
                var c = $scope.model.number_quick[0].substr(-1);
                var n1 = 0
                var n2 = 0
                var n3 = 0
                var n4 = 0
                var n5 = 0
                var n6 = 0
                if((a == b)||(a == c)||(b == c)){
                    if(a == b){
                        n1 = a+a+c;
                        n2 = a+c+a;
                        n3 = c+a+a;
                    }else if(a == c){
                        n1 = a+b+a;
                        n2 = a+a+b;
                        n3 = b+a+a;
                    }else{
                        n1 = a+b+b;
                        n2 = b+b+a;
                        n3 = b+a+b;
                    }

                } else {
                    n1 = a+b+c;
                    n2 = a+c+b;
                    n3 = b+a+c; 
                    n4 = b+c+a; 
                    n5 = c+a+b; 
                    n6 = c+b+a; 
                }
               
                $scope.model.number_quick = [n1+"\n"+n2+"\n"+n3].sort()
                
            }
        }

        $scope.CancelAll = function () {
            $scope.model = {
                "state": $scope.model.state,
            }
            $scope.lottos = [];
            $scope.modline = {
                "type": "หวยไทย",
                amount_total: 0,
                amount_total_remain: 0,
                discount: 0,
                discount_two: 0,
                discount_three: 0,
                discount_run: 0
            }
        }

        $scope.SearchKeyword = function (search){
            if(search === undefined || search === "null" || search === "") {
                bootbox.alert('กรุณาใส่ตัวเลข');
                return
            }
             var data = {
                search: search,
               
            };
            console.log(data);
            $scope.results = [];
            $http.post('/jan/default/form-search', data)
                .then(function (response) {
                   $scope.results = response.data.res;
                   $scope.sum = response.data.sum;
            });
        }
        
        $scope.reportAll = function() {
           
            $http.get('/jan/default/report-all-customer')
                .then(function (response) {
                    console.log(response.data.customers)
                    $scope.customers = response.data.customers
                    $scope.total = response.data.total
                    $scope.dis = response.data.dis
                    $scope.amount = response.data.amount
            });
        }

        $scope.expandSelected = function(line){
            var data = {
                lineId: line.id,
               
            };
            $scope.bill = line.code
            $scope.cus_name = line.name
            $scope.customers_line = [];
            $http.post('/jan/default/report-all-customer-line', data)
                .then(function (response) {
                   $scope.customers_line = response.data.res;
                  
            });
        }

        $scope.BillLimit = function(line) {
            var data = {
                lineId: line.id,
               
            };
            $scope.bill = line.code
            $scope.cus_name = line.name
            $scope.customers_line = [];
            $http.post('/jan/default/report-all-customer-line-limit', data)
                .then(function (response) {
                   $scope.customers_line_limit = response.data.res;
                  
            });
        }

        $scope.clearBill = function () {

            bootbox.confirm('คุณต้องการลบบิลทั้งหมดหรือไม่?', function (result) {
                if (result) {
                   $http.get('/jan/default/clear-bill')
                        .then(function (response) {
                           
                            navigateToView();
                    });
                }
            })
        }

        $scope.keydown = function ($event, nextId) {
            if($event.keyCode === 13) {
                if ($scope.model.state === false) {
                    // ที่ละตัว
                    
                    if($scope.model.number_slowly.length === 2) {
                        if(nextId === 2) {
                           angular.element(document.querySelector('#f_' + 2))[0].focus();
                        } else if (nextId === 3) {
                            angular.element(document.querySelector('#f_' + 3))[0].focus();
                        } else if (nextId === 5) {
                            angular.element(document.querySelector('#f_' + 5))[0].focus();
                            
                        } else if (nextId === 1) {
                            angular.element(document.querySelector('#f_' + 1))[0].focus();
                            if ($scope.lottos.length > 0) {
                       
                                 $scope.model = {
                                   
                                    "state": $scope.model.state,
                                    
                                }
                            }
                        }
                       
                    }

                    if($scope.model.number_slowly.length === 3) {
                        if(nextId === 2) {
                           angular.element(document.querySelector('#f_' + 2))[0].focus();
                        } else if (nextId === 4) {
                            angular.element(document.querySelector('#f_' + 4))[0].focus();
                        } else if (nextId === 5) {
                            angular.element(document.querySelector('#f_' + 5))[0].focus();
                            
                        } else if (nextId === 1) {
                            angular.element(document.querySelector('#f_' + 1))[0].focus();
                            if ($scope.lottos.length > 0) {
                       
                                 $scope.model = {
                                    
                                    "state": $scope.model.state,
                                    
                                }
                            }
                        }
                    }

                } else {
                    // เร็ว
                }
               
            }
        }
 });