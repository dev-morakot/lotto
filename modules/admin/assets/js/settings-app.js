'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("SettingsApp", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model','bic.common'
]);

app.config(['$httpProvider', function ($httpProvider) {
        $httpProvider.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
        $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
    }]);


app.factory("Resource", function ($http) {


    return {
        initial: function () {
            return $http.get('/admin/settings/resource-for-form-ajax');
        },
        loadAddressLines: function (_id) {
            var params = {id: _id};
            return $http.get('/admin/settings/load-address-lines', {params: params});
        },
        saveAddressLine: function (_id, _line) {
            var params = {id: _id, line: _line};
            return $http.post('/admin/settings/save-address-line', params);
        },
        removeAddressLine: function (_id, _line) {
            var params = {id: _id, line: _line};
            return $http.post('/admin/settings/remove-address-line', params);
        }
    };
});

/*
 * Controller
 */
app.controller("AddressFormController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {
    $scope.myId = $("#myId").val();
    $scope.addr = {id: -1};
    $scope.lines = [];
    $scope.showAddressForm = false;
    //
    $scope.countries = [];
    $scope.provinces = [];
    $scope.districts = [];
    $scope.subdistricts = [];
    
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
    Resource.initial().then(function (response) {
        var resources = response.data;
        console.log(resources);
        $scope.countries = resources.countries;
        $scope.provinces = resources.provinces;
        $scope.districts = resources.districts;
        $scope.subdistricts = resources.subdistricts;
        
    });

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

