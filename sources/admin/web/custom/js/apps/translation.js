
dineinApp.controller('translationController', function ($scope, $http, $element) {

    $scope.translationFormIsSubmitting = false;
    $scope.submitError = '';
    $scope.translations = {};

    var translationInputs = $($element).find('input');
    for(var i = 0; i < translationInputs.length; i++) {
        var input = $(translationInputs[i]);
        var isoCode = input.data('iso-code');
        var value = input.data('value');
        $scope.translations[isoCode] = value;
    }

    // save edits
    $scope.save = function (labelCode) {
        $scope.translationFormIsSubmitting = true;
        $http.post('/language/save-translations', {
            label_code: labelCode,
            translations: $scope.translations
        }).success(function (response) {

            if (response.error != undefined) {
                $scope.submitError = response.error;
            } else {
                $scope.delivery = response;
                $scope.submitError != '';
                closePopup();
            }

            $scope.translationFormIsSubmitting = false;

        }).error(function (data) {
            $scope.translationFormIsSubmitting = false;
            $scope.submitError = data;
        });
    };

    $scope.hasSubmitError = function () {
        return $scope.submitError != '';
    }

    var closePopup = function() {
        $('.modal').click();
    }
});

dineinApp.controller('translationeditorController', function ($scope, $http, $element) {

    $scope.translationFormIsSubmitting = false;
    $scope.submitError = '';
    $scope.translations = {};

    var appLanguage = $('#app-language').val();
    var appLanguageEditor;

    var translationInputs = $($element).find('input');
    var editors = CKEDITOR.instances;
    var editor;
    var j=0;
    for(var i in editors){
        if(j){
            var input = $('#'+i);
            var isoCode = input.data('iso-code');
            var value = editors[i].getData();
            $scope.translations[isoCode] = value;
            if(isoCode==appLanguage){
                appLanguageEditor = editors[i];
                editors[i].on('change', function() {
                    editor.setData(appLanguageEditor.getData());
                    $scope.translations[appLanguage] = appLanguageEditor.getData();
                });
            }
        }
        else{
            editor = editors[i];
        }
        j++;
    }

    // save edits
    $scope.save = function (labelCode) {
        $scope.translationFormIsSubmitting = true;
        var j=0;
        for(var i in editors){
            if(j){
                var input = $('#'+i);
                var isoCode = input.data('iso-code');
                var value = editors[i].getData();
                $scope.translations[isoCode] = value;
            }
            j++;
        }
        $http.post('/language/save-translations', {
            label_code: labelCode,
            translations: $scope.translations
        }).success(function (response) {

            if (response.error != undefined) {
                $scope.submitError = response.error;
            } else {
                $scope.delivery = response;
                $scope.submitError != '';
                closePopup();
            }

            $scope.translationFormIsSubmitting = false;

        }).error(function (data) {
            $scope.translationFormIsSubmitting = false;
            $scope.submitError = data;
        });
    };

    $scope.hasSubmitError = function () {
        return $scope.submitError != '';
    }

    var closePopup = function() {
        $('.modal').click();
    }
});
