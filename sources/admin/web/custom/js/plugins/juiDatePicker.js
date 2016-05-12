//for reference see http://eternicode.github.io/bootstrap-datepicker/
(function ($) {
    $.widget('custom.juiDatePicker', {

        _create: function () {
            var year = new Date().getFullYear();
            var widget = this;
            widget.t = null;
            $(widget.element).datepicker({
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

var initializejuiDatePicker = function() {
    $('.date-jui-picker').juiDatePicker();
}

$(document).ready(function () {
    initializejuiDatePicker();
});

$(document).on('pjax:end',   function() { initializejuiDatePicker();});