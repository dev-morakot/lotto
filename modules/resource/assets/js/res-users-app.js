'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("ResUsersApp", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model', 'ngRoute', 'bic.common'
]);

app.config(['$httpProvider', function ($httpProvider) {
        $httpProvider.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
        $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
    }]);

/*
 * Controller
 */
app.controller("ResUsersManageController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, $routeParams) {
    console.log($location.search());
    var ctrl = this;
    ctrl.res_users = [];
    ctrl.usersearch = "";
    //
    ctrl.center_users = [];


    
    ctrl.refreshResUsers = function () {
        var _params = {q: ctrl.usersearch};
        $http.get('/resource/res-users/res-user-list-json', {params: _params})
                .then(function (response) {
                    var data = response.data;
                    console.log(data);
                    ctrl.res_users = data;
                });
    };
    
    ctrl.refreshCenterUser = function(){
        var _params = {q: ctrl.usersearch};
        $http.get('/resource/res-users/center-user-list-json', {params: _params})
                .then(function (response) {
                    var data = response.data;
                    console.log(data);
                    ctrl.center_users = data;
                });
    };

    ctrl.refreshResUsers();
    ctrl.refreshCenterUser();

    ctrl.refresh = function(){
        ctrl.refreshResUsers();
        ctrl.refreshCenterUser();
    };
    
    ctrl.addToCenter = function(_user){
        console.log(_user);
        var _params = {id: _user.id};
        $http.get('/resource/res-users/add-to-res-user', {params: _params})
                .then(function (response) {
                    var data = response.data;
            console.log(data);
                    if(data.status==='success'){
                        ctrl.refresh();
                    } else {
                        bootbox.alert("ซ้ำ");
                    }
                },function errorCallback(response){
                    console.log(response);
                    bootbox.alert({message:response.data.name});
                });
    };


});

