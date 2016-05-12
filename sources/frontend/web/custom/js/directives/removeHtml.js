dineinApp.directive('removeHtml',function() {

    var linkFn = function(scope,element,attrs) {
        var removeHtml = function(html) {
            if (html == undefined) {
                return "";
            }
            var tmp = document.createElement("DIV");
            tmp.innerHTML = html;
            console.log(html,tmp.textContent, tmp.innerText);
            return tmp.textContent || tmp.innerText || "";
        };

        var sanitise = function(value) {
            var oldValue = scope.$eval(attrs.ngModel);
            var newValue = removeHtml(oldValue);
            scope[attrs.ngModel] = newValue;
            scope.$apply();
        }
        element.bind('keypress', function(event) {
            console.log('keypress');
            if(event.which === 13) {
                sanitise();
            }
        });
        $(element).focusout(sanitise);
    };

    return {
        link:linkFn,
        require: "ngModel",
    };
});