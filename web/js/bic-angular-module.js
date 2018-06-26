'use strict';

var app = angular.module("bic.module", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model', 'ngRoute'
]);

/**
 * <div bic-doc-status state='saleDetail.state' list='<?php echo $array; ?>'></div> 
 * 
 */
app.directive('bicDocStatus', function () {
    return {
        restrict: "AE",
        transclude: true,
        scope: {
            state: "=",
            list: "="
        },
        template:
                "<style> .color { color: {{ cssStyle }}} .cssStyleColor { color: {{ cssColor }}} </style>\
			<p> {{ msgStyle }} <span ng-bind-html='Msghtml'></span> </p>\ ",
        controller: function ($scope) {
            var ctrl = this;
            this.msg = "สถานะเอกสาร :";
            $scope.$watch('state', function (newValue) {
                this.statuses = newValue;
            }.bind(this));
            
            console.log("module ctrl",$scope.list);
        },
        controllerAs: 'ctrl',
        link: function (scope, elem, attrs, ctrl) {
            scope.cssStyle = 'blue';
            scope.cssColor = 'grey';
            scope.msgStyle = ctrl.msg;
            scope.Msghtml = "";
            
            

            scope.$watch('state', function (value) {
                if (value) {
                    updateView(value,scope.list);
                }
            });
            
            scope.$watch('list',function(value){
                if(value){
                    updateView(scope.state,value);
                }
            });
            
            function updateView(_currentState,_stateList){
                scope.Msghtml = "";
                angular.forEach(_stateList, function (v, k) {
                        if (v.id == _currentState) {
                            scope.ColorStyle = "<b class='color'>" + v.name + "</b>";
                        } else {
                            scope.ColorStyle = "<span class='cssStyleColor'>" + v.name + "</span>";
                        }
                        scope.Msghtml = scope.Msghtml + " > " + scope.ColorStyle;
                    });
            }
        }
    };
});