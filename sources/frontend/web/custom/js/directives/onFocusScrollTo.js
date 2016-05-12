/**
 * Created by jarik on 7/10/15.
 */


dineinApp.directive('onFocusScrollTo',function($window,$timeout){
    return {
        restrict:'A',
        scope:{
            scrollToOffsetTop:'='
        },
        controller:function($scope){
            $scope.scrolled = false;
        },
        link:function($scope,$elem,attrs){

            $elem.on('focus',function(){
                $timeout(function(){
                    if($scope.scrolled === false){
                        var offsetTop = $($elem).offset().top;
                        $scope.scrollToOffsetTop =
                             angular.isUndefined($scope.scrollToOffsetTop)
                             || !angular.isNumber($scope.scrollToOffsetTop)
                             || isNaN($scope.scrollToOffsetTop)
                             || !isFinite($scope.scrollToOffsetTop)
                                  ? 0 : $scope.scrollToOffsetTop;
                        $window.scrollTo(0,offsetTop - $scope.scrollToOffsetTop);
                        $scope.scrolled = true;
                    }
                },150,false);

            });
            $elem.on('blur',function(){
                $scope.scrolled = false;
            });
        }
    };
});