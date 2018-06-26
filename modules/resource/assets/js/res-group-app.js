'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("ResGroupApp", ['ngSanitize', 'ui.select',
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
app.controller("ResGroupFormController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {
    // Resources
    $scope.users = [];
    $scope.group_users = [];
    // Ui
    $scope.group_id = $("#model_id").val();
    $scope.model = {};
    //
    console.log("initial");
    var params = {};
    $http.get('/resource/res-users/user-list-json',{params:params})
            .then(function (response) {
                var resources = response.data;
                console.log(resources);
                $scope.users = resources;
            });
            
    var params = {group_id:$scope.group_id};
    $http.get('/resource/res-group/list-group-users',{params:params})
            .then(function (response) {
                var resources = response.data;
                console.log(resources);
                $scope.group_users = resources;
            });

    /////////
    // - Scope function
    ////////
    $scope.addUserToGroup = function(){
        var uid = $scope.model.user_id;
        var params = {group_id:$scope.group_id,user_id:uid};
        $http.post('/resource/res-group/add-group-user',params)
            .then(function (response) {
                var user = response.data;
                console.log(user);
                $scope.group_users.push(user);
            });
    };
    
    $scope.deleteGroupUser = function(user){
        var uid = user.id;
        var params = {group_id:$scope.group_id,user_id:uid};
        $http.post('/resource/res-group/delete-group-user',params)
            .then(function (response) {
                var resources = response.data;
                console.log(resources);
                var index = $scope.group_users.indexOf(user);
                $scope.group_users.splice(index, 1);    
            });
    };

});

