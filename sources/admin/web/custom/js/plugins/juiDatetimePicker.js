//for reference see http://eternicode.github.io/bootstrap-datepicker/
(function ($) {
    $.widget('custom.juiDatetimePicker', {

        _create: function () {
            var year = new Date().getFullYear();
            var widget = this;
            widget.t = null;
            $(widget.element).datetimepicker({
                "changeMonth":true,
                "changeYear":true,
                "dateFormat":"yy-mm-dd",
                todayHighlight: widget.element.data('today-highlight'),
                yearRange: widget.element.data('year-range'),
                autoclose: true
            });
        },
        
        destroy: function () {
            this._super('_destroy');
        },

    });
} (jQuery));

var initializejuiDatetimePicker = function() {
    $('.datetime-jui-picker').juiDatetimePicker();
}

$(document).ready(function () {
    initializejuiDatetimePicker();
});

$(document).on('pjax:end',   function() { initializejuiDatetimePicker();});