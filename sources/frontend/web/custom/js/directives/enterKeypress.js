/**
 * Created by jarik on 6/2/15.
 */


dineinApp.directive('enterKeypress',function($timeout){
    return {
        restrict:'A',
        scope:{
            enterKeypress:'&'
        },
        link:function($scope,$elem,attr){
            $elem.on('keypress',function(e){
                if(e.keyCode == 13){
                    $timeout(function(){
                        $scope.enterKeypress(e);
                    },0);
                }
            });

        }
    }
});