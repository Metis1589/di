/**
 * Created by jarik on 5/27/15.
 */

dineinApp.directive('resizeNotifier',function($rootScope,$timeout){
    return {
        restrict:'AE',
        link:function(scope,element,attrs){
            var windowEl = $(window);
            windowEl.on('resize',function(){
                $rootScope.$emit('WINDOW_RESIZED',{
                    width:windowEl.width(),
                    height:windowEl.height()
                });
                $rootScope.$broadcast('WINDOW_RESIZED',{
                    width:windowEl.width(),
                    height:windowEl.height()
                });
            })
        }
    }
});