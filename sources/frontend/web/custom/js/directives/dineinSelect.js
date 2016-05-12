/**
 * Created by jarik on 5/18/15.
 */


dineinApp.directive('dineinSelect',function(){
    return {
        replace : true,
        restrict:'E',
        scope:{
            items:'=',
            ngModel:'=',
            placeholder:'=',
            onSelect:'&',
            onOpen:'&',
            name:'@',
            disabled:'=',
            errRequired:'@',
            repeatEnd:'&'
        },
        controller:function($scope,$element,$rootScope){
            var self = this;
            $scope.keys = Object.keys($scope.items);
            if(angular.isUndefined($scope.name)){
                $scope.name = 'select_'+Date.now();
            }
            this.triggerMenu = function(){
                if(!$scope.disabled){
                    if($($element).hasClass('opened')){
                        self.closeMenu();
                    }else{
                        if(angular.isFunction($scope.onOpen)){
                            $scope.onOpen();
                        }
                        $($element).find('ul').slideDown(500);
                        $($element).addClass('opened');
                    }
                }
            };
            this.closeMenu = function (){
                $($element).find('ul').slideUp(500);
                $($element).removeClass('opened');
            };
            $scope.setValue = function($event,key,value){
                $event.stopPropagation();
                $scope.ngModel = key;
                $scope.onSelect({
                    key:key,
                    value:value
                });
                self.closeMenu();
            };
        },
        link:function($scope,$elem,attrs,ctrl){
            $scope.required = 'required' in attrs;
            $elem.on('click',function(e){
                e.stopImmediatePropagation();
                $($elem).trigger('dineinSelect.click',{name:$scope.name});
                ctrl.triggerMenu(e);
            });
            $(document).on('dineinSelect.click',function(e,args){
                if(args && 'name' in args && args.name != $scope.name){
                    ctrl.closeMenu();
                }
            });
            $(document).on('click',function(e){
                ctrl.closeMenu();
            });
        },
        templateUrl:'/custom/js/templates/dineinSelectTemplate.html'
    }
});
