(function ($) {
    $.widget('custom.timepicker', {

        _create: function () {
            $(this.element).timeEntry({
                show24Hours : $(this.element).data('show24hours'),
                showSeconds : $(this.element).data('showseconds'),
                defaultTime: new Date(0, 0, 0, 0, 0, 0)
            });
            $(this.element).addClass('form-control');
        },
        
        destroy: function () {
            this._super('_destroy');
        },

    });
} (jQuery));

var initializeTimePicker = function() {
    $('.time-picker').timepicker();
}

initializeTimePicker();

$(document).on('pjax:end',   function() { initializeTimePicker();});

$('[data-toggle]').on('click', function() {
    $('.time-picker-tab').timepicker();

})