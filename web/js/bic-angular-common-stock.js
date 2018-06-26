'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("bic.common.stock", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model', 'ngRoute'
]);

/**
 * Dialog เพื่อจัดการ Stock Move
 */
app.component('bicStockMoveDialog', {
    templateUrl: '/angular-templates/bic-stockmove-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        ctrl.state = null;

        ctrl.$onInit = function(){
            ctrl.items = ctrl.resolve.items;
        };

        ctrl.ok  = function(){
            ctrl.close({$value:ctrl.items});
        }

        ctrl.cancel = function(){
            ctrl.dismiss({$value:'cancel'});
        }

        ctrl.stateLabel = function(state){
            if(state=='done'){
                return 'label label-success';
            }
            if(state=='draft'){
                return 'label label-warning'
            }
            return 'label label-default';
        }
        
    },
    bindings: {
        close:'&',
        dismiss:'&',
        resolve:'<',
    },
    controllerAs: 'ctrl'
});

/**
 * Dialog เพื่อ QCFORM
 */
app.component('bicStockQcFormDialog', {
    templateUrl: '/angular-templates/bic-stockqcform-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        $scope.model = {};
        var ctrl = this;
        ctrl.state = null;

        ctrl.$onInit = function(){
            $scope.model = ctrl.resolve.model;
            console.log("bicStockQcFormDialog init",$scope.model);
        };

        ctrl.ok  = function(){
            if($scope.qcform.$invalid){
                console.log("form invalid");
                return;
            }
            ctrl.close({$value:$scope.model});
        }

        ctrl.cancel = function(){
            ctrl.dismiss({$value:'cancel'});
        }
        
    },
    bindings: {
        close:'&',
        dismiss:'&',
        resolve:'<',
    },
    controllerAs: 'ctrl'
});