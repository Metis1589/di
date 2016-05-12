(function ($) {
    $.widget('custom.checkboxStyle', {

        _create: function () {
            var widget = this;
            $(this.element).iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            $(this.element).unbind('ifChecked');
            $(this.element).unbind('ifUnchecked');
            $(this.element).bind('ifChecked', widget.onChange(true));
            $(this.element).bind('ifUnchecked', widget.onChange(false));
        },

        onChange: function(checked) {
            var widget = this;
            return function() {
                $(widget.element).attr('checked', checked);
                $(widget.element).change();
            }
        },

        destroy: function () {
            this._super('_destroy');
        },

    });
} (jQuery));

var initializeCheckBoxStyle = function() {
    $('.i-checks').checkboxStyle();
}

initializeCheckBoxStyle();

$(document).on('pjax:end',   function() { initializeCheckBoxStyle();});