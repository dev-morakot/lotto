'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("PartnerApp", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model'
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
    $http.get('/purchase/purchase-order/resource-for-form-ajax')
            .then(function (response) {
                resources = response.data;
            });


    return {
        initial: function () {
            return $http.get('/resource/res-partner/resource-for-form-ajax');
        },
        get: function (offset, limit) {
            return items.slice(offset, offset + limit);
        },
        total: function () {
            return items.length;
        },
        loadAddressLines: function (_id) {
            var params = {id: _id};
            return $http.get('/resource/res-partner/load-address-lines', {params: params});
        },
        saveAddressLine: function (_id, _line) {
            var params = {id: _id, line: _line};
            return $http.post('/resource/res-partner/save-address-line', params);
        },
        removeAddressLine: function (_id, _line) {
            var params = {id: _id, line: _line};
            return $http.post('/resource/res-partner/remove-address-line', params);
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
app.controller("PartnerFormController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {
    // Resources
    $scope.suppliers = [];
    $scope.paymentTerms = [];
    $scope.categories = [];
    $scope.shipAddrs = [];
    $scope.pickingTypes = [];
    $scope.termTexts = [];
    $scope.taxes = [];
    $scope.taxTypes = [];
    $scope.countries = [];
    $scope.provinces = [];
    $scope.districts = [];
    $scope.subdistricts = [];
    // Ui
    $scope.datepickerOptions = {
        minDate: new Date(),
        showWeeks: true
    };
    $scope.disabledVat = false;
    // model
    $scope.myId = $("#myId").val();
    $scope.model = {
        id: -1, state: "draft", date_order: new Date(), amount_total: 0,
        amount_before_tax: 0, discount_amount: ""
    };
    $scope.modelLines = [];
    // editable model
    $scope.modline = {mode: "insert", product_qty: "", price_unit: ""};
    $scope.availableUoms = [];

    //
    console.log("initial");
    loadAvailableUom("");
    Resource.initial().then(function (response) {
        var resources = response.data;
        console.log(resources);
        $scope.countries = resources.countries;
        $scope.provinces = resources.provinces;
        $scope.districts = resources.districts;
        $scope.subdistricts = resources.subdistricts;
        if ($("#myId").val() == "") {

        } else {
            //$scope.loadModel($("#myId").val());
        }
    });



    $scope.loadModel = function (_id) {
        console.log({'log': 'loadModel', msg: _id});
        $http.get('/purchase/purchase-order/load-po-json?id=' + _id)
                .then(function (response) {
                    //$scope.model = response.data.po;
                    $scope.model = modelParser(response.data.po);
                    $scope.modelLines = response.data.polines;
                    console.log($scope.modelLines);
                    angular.forEach($scope.modelLines, function (item, key) {
                        item.date_planned = uibDateParser.parse(item.date_planned, 'yyyy-MM-dd');
                    });
                    $scope.calculationStack();
                });

    };



    /////////
    // - Scope function
    ////////
    $scope.modProductChange = function (_product) {
        console.log("modify line product change");
        console.log(_product);
        $scope.modline.product = _product;
        // set default line name
        $scope.modline.name = _product.name;
        // set default price_unit
        $scope.modline.price_unit = _product.standard_price;
        // set default 
        $scope.modline.product_uom = _product.uom_id;
        $scope.modline.selected_uom = $filter('filter')($scope.availableUoms, {id: _product.uom_id})[0];
        $scope.sumLineTotalAmount($scope.modline);
        // load supplier product name if available
        var params = {product_id: _product.id, sup_id: $scope.model.partner_id};
        $.get("/product/product-product/product-supplier-info-json", params)
                .done(function (info) {
                    console.log(info);
                    var sup_info = info.sup_info
                    if (sup_info) {
                        $scope.modline.name = sup_info.product_name_sup;
                    }
                });
    };

    $scope.modProductRemove = function () {
        $scope.modline.product = undefined;
        $scope.modline.product_id = undefined;
        $scope.modline.name = "";
    };

    $scope.sumLineTotalAmount = function (_modline) {
        // parseFloat if NaN then 0

        $scope.modline.line_total_amount = (_modline.product_qty * _modline.price_unit) - _modline.discount_amount;

    };

    // Purchase Order Line Form
    $scope.openAddPoLine = function () {
        // open Line form section
        console.log("openAddPrLine");
        clearHelpError();
        if (!$scope.model.partner_id) {
            bootbox.alert("โปรดเลือกผู้จัดจำหน่าย");
            return;
        }
        $scope.hideModifyLine = false;
        $scope.modline = {id: -1, mode: "insert", product_uom: 1, product_qty: "", price_unit: "", discount_amount: "", amount_before_tax: ""}; // new

    };

    $scope.doSelectedLine = function (_modline) {
        console.log("doSeletedLine");
        clearHelpError();
        $scope.modline_index = $scope.modelLines.indexOf(_modline); //keep the beforemodify
        $scope.modline = angular.copy(_modline); // copy obj to make change
        $scope.modline.mode = "edit";
        console.log($scope.modline);
        // SHow the form
        $scope.hideModifyLine = false;


    };

    function clearHelpError() {
        $scope.prod_desc_error = false;
        $scope.prod_date_required_error = false;
    }

    clearHelpError();
    function formLineValidate() {
        var isPass = true;
        var $date_planned = $("#modline-date_planned");
        if ($date_planned.val().trim() == "") {
            addErrorHelper($date_planned, 'โปรดเลือกวันรับสินค้า');
            isPass = false;
        }
        var $name = $("#modline-name");
        if ($name.val().trim() == "") {
            addErrorHelper($name, 'โปรดระบุรายละเอียด');
            isPass = false;
        }
        var $uom = $("#modline-selected_uom");
        if ($uom.find(":selected").val() == "?" || $uom.find(":selected").val() == "") {
            addErrorHelper($uom, 'โปรดระบุหน่วย');
            isPass = false;
        }
        return isPass;
    }

    $scope.savePoLine = function () {
        console.log($scope.modline);
        clearErrorMsg();
        if (!formLineValidate()) {

            return;
        }

        if (!angular.isNumber($scope.modline.line_total_amount)) {
            $scope.modline.line_total_amount = 0;
        }

        if ($scope.modline.mode === "insert") {
            $scope.modelLines.push(angular.copy($scope.modline));
        } else if ($scope.modline.mode === "edit") {
            // replace current row with change
            $scope.modelLines[$scope.modline_index] = angular.copy($scope.modline);
        }
        $scope.hideModifyLine = true;
        var min_date = $scope.calculateMinPlannedDate();
        $scope.model.minimum_planned_date = min_date;
        $scope.calculationStack();
    };

    $scope.doRemoveLine = function (_modline) {
        var index = $scope.modelLines.indexOf(_modline);
        $scope.modelLines.splice(index, 1);
        console.log($scope.modelLines);
        $scope.calculationStack();
    };

    $scope.calculationStack = function () {
        sumAmountBeforeTax();
        $scope.sumAmountTotal();
    };

    $scope.changeUom = function (_uom) {
        if (_uom) {
            $scope.modline.product_uom = _uom.id;
        }
    };

    function loadAvailableUom(_product_id, _onsuccess) {
        console.log("loadAvailableUom");
        $http.get('/product/product-product/available-prod-uom-json?product_id=' + _product_id)
                .then(function (response) {

                    $scope.availableUoms = response.data;
                    if (_onsuccess) {
                        _onsuccess(response.data);
                    }
                });
    }

    $scope.calculateMinPlannedDate = function () {
        var min_date = null;
        angular.forEach($scope.modelLines, function (line, key) {
            var current = moment(line.date_planned);
            if (!min_date) {
                min_date = current;
            }

            if (min_date.isSameOrAfter(current)) {
                min_date = current;
            }
        });
        var result = uibDateParser.parse(min_date.format('YYYY-MM-DD'), 'yyyy-MM-dd');
        return result;
    };

    $scope.sumAmountTotal = function () {
        console.log('sumAmountTotal');

        //        if (!selected_tax) {
        //            //wait for load success
        //            return;
        //        }

        var amount_before_tax = parseFloat($scope.model.amount_before_tax);
        var discount_amount = parseFloat($scope.model.discount_amount) || 0;
        var amount_after_discount = amount_before_tax - discount_amount;
        $scope.model.amount_after_discount = amount_after_discount.toFixed(2) || 0;

        var amount_tax = 0;
        var amount_total = 0;
        var amount_untaxed = 0;
        if ($scope.model.tax_type == 'no_tax') {
            //no vat
            $scope.model.tax_id = null;
            $scope.disabledVat = true;
            amount_tax = 0;
            amount_total = amount_after_discount;
            amount_untaxed = amount_after_discount;
        } else {
            $scope.disabledVat = false;
            var selected_tax = $filter('filter')($scope.taxes, {id: $scope.model.tax_id})[0];
            var tax_rate = 0;
            if (!selected_tax) {
                selected_tax = $scope.taxes[0];
                $scope.model.tax_id = selected_tax.id;
            }
            tax_rate = selected_tax.rate;
            if ($scope.model.tax_type == 'tax_in') {
                //amount_tax = amount_after_discount * tax_rate / (1 + tax_rate);
                amount_tax = amount_after_discount * (tax_rate / 100) / (1 + (tax_rate / 100));
                amount_total = amount_after_discount;
                amount_untaxed = amount_total - amount_tax;
            } else {
                // tax_ex
                amount_tax = amount_after_discount * (tax_rate / 100);
                amount_total = amount_tax + amount_after_discount;
                amount_untaxed = amount_after_discount;
            }
        }
        $scope.model.amount_tax = amount_tax.toFixed(2);
        $scope.model.amount_untaxed = amount_untaxed.toFixed(2);
        $scope.model.amount_total = amount_total.toFixed(2);
    };

    function sumAmountBeforeTax() {
        var _amount_total = 0;
        angular.forEach($scope.modelLines, function (line, key) {
            _amount_total += parseFloat(line.line_total_amount);
        });
        $scope.model.amount_before_tax = _amount_total.toFixed(2);
    }

    function formValidate() {
        var isPass = true;
        var value = $("#check_partner").val();
        if (value.trim() == "") {
            addErrorHelper($("#check_partner"), 'โปรดเลือกผู้จัดจำหน่าย');
            isPass = false;
        }

        var $term = $("#purchaseorder-payment_term_id");
        if ($term.val().trim() == "") {
            addErrorHelper($term, 'โปรดเลือกเงื่อนไขการชำระ');
            isPass = false;
        }

        if ($scope.modelLines.length == 0) {
            bootbox.alert("ระบุรายการขอซื้ออย่างน้อย 1 รายการ");
            isPass = false;
        }

        var $min_date_planned = $("#purchaseorder-minimum_planned_date");
        if ($min_date_planned.val().trim() == "") {
            addErrorHelper($min_date_planned, 'โปรดเลือกวันรับสินค้า');
            isPass = false;
        }

        return isPass;
    }

    function addErrorHelper($el, message) {
        $el.parents(".form-group").find(".help-block").html(message).addClass("has-error");
        $el.parents(".form-group").addClass("has-error");
    }

    function clearErrorMsg() {
        $(".form-group").removeClass("has-error");
        $(".form-group .help-block").removeClass("has-error");
        $(".form-group .help-block").empty();
    }

    /*
     * Form Submit
     * @returns {undefined}
     */
    $scope.formSave = function () {
        console.log("formSave");
        clearErrorMsg();
        if (!formValidate()) {
            return;
        }

        //        if($scope.formPurchase.$invalid){
        //            alert("form invalid");
        //            return;
        //        }
        var model = $scope.model;
        model.date_order_str = moment(model.date_order).format('DD/MM/YYYY');
        model.minimum_planned_date_str = moment(model.minimum_planned_date).format('DD/MM/YYYY');

        //date parser line
        angular.forEach($scope.modelLines, function (item, key) {
            item.date_planned_str = moment(item.date_planned).format('DD/MM/YYYY');
        });
        console.log($scope.model);
        var data = {
            model: model,
            modelLines: $scope.modelLines
        };
        $http.post('/purchase/purchase-order/save-po', data)
                .then(function (response) {
                    console.log(response);
                    var po = response.data.po;
                    $scope.loadModel(po.id);

                    navigateToView(po.id);

                }, function errorCallback(response) {
                    //erroe
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                    bootbox.alert(response.data);
                });
    };

    function modelParser(modelinput) {
        modelinput.date_order = uibDateParser.parse(modelinput.date_order, 'yyyy-MM-dd');
        modelinput.minimum_planned_date = uibDateParser.parse(modelinput.minimum_planned_date, 'yyyy-MM-dd');
        return modelinput;
    }

    function navigateToView(id) {
        $window.location.href = '/purchase/purchase-order/view?id=' + id;
    }

    /*
     * Scope Function UI
     */
    $scope.datepicker1 = {
        opened: false
    };

    $scope.datepicker2 = {
        opened: false
    };

    $scope.datepicker3 = {
        opened: false
    };

    $scope.openDatepicker1 = function () {
        $scope.datepicker1.opened = true;
    };

    $scope.openDatepicker2 = function () {
        $scope.datepicker2.opened = true;
    };

    $scope.openDatepicker3 = function () {
        $scope.datepicker3.opened = true;
    };

    $scope.previewTermText = function () {
        console.log("previewTermText");
        var term_text_id = $scope.model.term_text_id;
        var termText = $filter('filter')($scope.termTexts, {id: term_text_id})[0];
        console.log(termText);

        var dialog = bootbox.dialog({
            title: termText.name,
            message: termText.content
        });
    };

    $scope.previewPrLines = function (_id) {
        console.log({log: 'loadPrLines', id: _id});

        var params = {id: _id};
        $http.get('/purchase/purchase-order/load-pr-lines', {params: params})
                .then(function (response) {
                    bootbox.dialog({
                        title: 'รายการขอซื้อ (PR)',
                        message: response.data
                    });
                });
    };

    // WATCHING
    $scope.$watchCollection('modelLines', function (newValue, oldValue) {
        console.log("array change");

    });

    $scope.$watch('modline.product_id', function (newValue, oldValue) {
        console.log("modline product_id change");

    });

    $scope.refreshProducts = function (product) {
        var params = {q: product, limit: 20};
        return $http.get('/product/product-product/product-list-json', {params: params}
        ).then(function (response) {
            $scope.products = response.data;
        });
    };

    $scope.refreshSuppliers = function (supplier) {
        var params = {q: supplier, limit: 20, is_supplier: true};
        return $http.get('/resource/res-partner/partner-list-json', {params: params})
                .then(function (response) {
                    $scope.suppliers = response.data;
                });
    };
    $scope.model = {addrform: 'root'};
});

app.controller("AddressFormController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {

    $scope.addr = {id: -1};
    $scope.lines = [];
    $scope.showAddressForm = false;
    
    /*
     * function
     */
    $scope.refreshAddressLines = function() {
        Resource.loadAddressLines($scope.myId).then(
                function (response) {
                    console.log(response);
                    $scope.lines = response.data;
                });
    };
    
    /*
     * init 
     */
    $scope.refreshAddressLines();

    /*
     * Scope function
     */
    
    $scope.openAddressForm = function(){
        $scope.addr = {};
        $scope.showAddressForm = true;
    };
    
    $scope.saveAddressLine = function () {
        if ($scope.addrForm.$valid) {
            Resource.saveAddressLine($scope.myId, $scope.addr).then(
                    function (response) {
                        console.log(response);
//                        if (response.data) {
//                            $scope.lines.push(response.data);
//                        }
                         $scope.refreshAddressLines();
                         bootbox.alert("บันทึกเรียบร้อย");
                         $scope.showAddressForm = false;
                    });
        }
    };

    $scope.removeAddressLine = function (_line) {
        Resource.removeAddressLine($scope.myId, _line).then(
                function (response) {
                    console.log(response);
                    if (response.data == true) {
                        var index = $scope.lines.indexOf(_line);
                        $scope.lines.splice(index, 1);
                    }
                });
    };
    
    $scope.modifyAddressLine = function(_line){
        $scope.addr = angular.copy(_line);
        $scope.showAddressForm = true;
    };

});

