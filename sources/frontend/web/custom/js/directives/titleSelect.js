/**
 * Created by jarik on 5/29/15.
 */
dineinApp.directive('titleSelect',function(){
    return{
        restrict:'E',
        templateUrl: 'title-select',
        replace:true,
        scope:{
            ngModel:'=',
            placeholder:'=',
            disabled:'=',
            name:'@',
            errRequired:'@'
        },
        controller:function($scope,$element){
            if(angular.isUndefined($scope.inputName)){
                $scope.name = 'name_'+Date.now();
            }
            $scope.required = false;
            this.triggerMenu = function(){
                function closeTitleMenu(){
                    $($element).find('ul').slideUp(500);
                    $($element).removeClass('opened');
                }
                if(!$scope.disabled){
                    if($($element).hasClass('opened')){
                        closeTitleMenu();
                    }else{
                        $($element).find('ul').slideDown(500);
                        $($element).addClass('opened');
                    }
                }
            }
        },
        link:function($scope,$elem,attrs,ctrl){
            $scope.required = 'required' in attrs;
            $elem.on('click',function(e){
                e.stopImmediatePropagation();
                ctrl.triggerMenu(e);
            });
        }
    }
});