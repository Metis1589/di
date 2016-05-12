/**
 * Created by jarik on 6/16/15.
 */

dineinApp.directive('dineinTooltip',function($timeout){
    return {
        scope:{
            header:'=',
            message:'@'
        },
        controller:function($scope){
            $scope.show = false;
            this.open = function (){
                $timeout(function(){$scope.show = true;},0);
            };
            this.close = function(){
                $timeout(function(){$scope.show = false;},0);
            }
        },
        restricted:'E',
        replace:true,
        link:function(scope,elem,attrs,ctrl){
            elem.on('mouseenter',function(){
                console.dir('enter');
                if(scope.show == false){
                    ctrl.open();
                }
                console.dir(scope.show);
            });
            elem.on('mouseleave',function(){
                console.dir('leave');
                if( scope.show == true){
                    ctrl.close();
                }
                console.dir(scope.show);
            })

        },
        template:'<div class="dinein-tooltip help-button"><div ng-hide="!show" class="tooltip-block"><span class="tooltip-title">{{header}}</span><span class="tooltip-message">{{message}}</span></div></div>'
    };
});
