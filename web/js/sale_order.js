var app = angular.module('myApp', ['ui.select','ui.bootstrap','ngSanitize']);

app.filter('isVatIcon', function() {
	return function(input) {
		if(input == 'default'){
			return 'glyphicon glyphicon-ok';
		} else {
			return '';
		}
	}
});

app.directive('fileModel', ['$parse', function ($parse) {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			var model = $parse(attrs.fileModel);
			var modelSetter = model.assign;

			element.bind('change', function(){
				scope.$apply(function(){
					modelSetter(scope, element[0].files[0]);
				});
			});
		}
	};
}]);

app.service('fileUpload', ['$http', function ($http) {
	this.uploadFileToUrl = function(po_file, uploadUrl, partner_id, date_schedule , note) {
		var fd = new FormData();
		fd.append('po_file', po_file);
		fd.append('partner_id', partner_id);
		fd.append('date_schedule', date_schedule);
		fd.append('note', note);
		//fd.append('state', state);
		$http.post(uploadUrl, fd, {
			transformRequest: angular.identity,
			headers: {'Content-Type': undefined, 'Process-Data': false}
		})
		/*$http({
			url: uploadUrl,
			method: "POST",
			data: fd,
			transformRequest: angular.identity,
			headers: {'Content-Type': undefined}
		})*/
		.success(function() {
			console.log("สำเร็จ");
		})
		.error(function() {
			console.log("ล้มเหลว");
		});
		
		
	}
}]);


app.controller("mySaleOrder", ['$scope', '$http', '$filter', '$interval', 'fileUpload' ,
	function ($scope, $http, $filter, $interval, fileUpload){

	$scope.categoryProduct = [];
	$scope.person = {};
	$scope.tempProduct = [];
	$scope.accounts = [];
	$scope.qty = 1;
	$scope.amount = "0.070";
	
	// Date Picker
	$scope.open = function(event) {
		event.preventDefault();
		event.stopPropagation();
		$scope.opened = true;
	};

	// Account Tax
	getAccountTax();
	function getAccountTax(){
		$http({
			url: '/sale/sale-order/get-account-tax',
			method: "GET",
			headers: {"Content-Type": 'application/x-www-form-urlencoded'}
		}).then(function(response) {
			$scope.accounts = response.data;
		});
	}

	getPartner();
	function getPartner(){
		$http({
			url: '/sale/sale-order/get-partner-json',
			method: "GET",
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response) {
			$scope.partner = response.data;
		});
	}

	$scope.browseProduct = function(){
		$http({
			url: '/sale/sale-order/get-product-category-json',
			method: "GET",
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response) {
			var getValue = response.data;
			$scope.categoryProduct = getValue;
			
		});
	}

	$scope.searchProduct = function(filterProduct)  {
		if(filterProduct == undefined) {
			filterProduct = {};
		}
		console.log(filterProduct);
		$http({
			url: '/sale/sale-order/get-find-product',
			method: "POST",
			data: filterProduct,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response) {
			console.log(response.data);
			$scope.products = response.data;
		});
	}

	$scope.saleProduct = function(product) {
		
		$scope.getProductInfo = {
			'id':product.id,
			'name':product.name,
			'default_code':product.default_code,
			'uom_name':product.uom_name,
			'uom_id':product.uom_id
		}

		$scope.tempProduct.push($scope.getProductInfo);
	}

	$scope.submitForm = function($params, tempProduct) {

		var po_file = $params.po_file;

		var uploadUrl = "/sale/sale-order/get-sale-save";
		var partner_id = $params.partner_id.id;
		var note = $params.note;
		var date_schedule = $filter('date')($params.date_schedule, 'yyyy-MM-dd');

		fileUpload.uploadFileToUrl(po_file, uploadUrl, partner_id, date_schedule, note);
		
		console.log(tempProduct);
		
	}

	$scope.removeRow = function(info) {
		var index = -1;
		var comArr = eval($scope.tempProduct);
		for(var i = 0; i < comArr.length; i++) {
			if(comArr[i].info === info) {
				index = i;
				break;
			}
		}
		$scope.tempProduct.splice(index, 1);
	}

	$scope.computePrice = function(saleTemp) {
		$scope.sumTotalPrice = 0;
		var sum = 0;

		angular.forEach($scope.tempProduct, function(value, key) {
			var price = value.price;
			var qty = value.qty;
			var dis = value.discount_amount;

			var totalPerRow = (Number(price) * Number(qty) - Number(dis));
			sum += Number(totalPerRow);
		});

		var format = '0,0.00';
		$scope.sumTotalPrice = sum;
		$scope.sumTotalPriceText = numeral(sum).format(format);


	}

	// vat
	$scope.isVat = function(){

		if($scope.person.cust_tax_id == $scope.amount) {
			$scope.vat = "tset";
			console.log($scope.vat);
		} 

		//$scope.computeVatOfSaleTemp();
	}

	$scope.getTotal = function(){
    	var total = 0;
   		angular.forEach($scope.tempProduct, function(item) {
   			total += (item.qty * item.price - item.discount_amount);
   		});
    	return total;
	}

}]);

app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
    $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
    $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
}]);

app.filter('propsFilter', function() {
  return function(items, props) {
    var out = [];

    if (angular.isArray(items)) {
      var keys = Object.keys(props);
        
      items.forEach(function(item) {
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