var app = angular.module('myProduct', ['ui.bootstrap']);

	app.config(['$httpProvider', function ($httpProvider) {
    	$httpProvider.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
    	$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    	$httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
    	$httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
	}]);

	app.factory('productFactory', function($http) {
		var factory = {};

		factory.getProduct = function(){
			return $http.get("/product/product-product/get-product-json");
		};
		return factory;
	});

	app.controller('MyController', [
		'$scope' , '$http' , '$filter', 'productFactory',
		function ($scope, $http, $filter , productFactory) {
		
		
		Product();
		function Product(){
			productFactory.getProduct()
				.success(function(data) {
					$scope.products = data;
				});
		}
	}]);