//for reference see http://eternicode.github.io/bootstrap-datepicker/
(function ($) {
    $.widget('custom.kartikGridView', {

        _create: function () {
            var widget = this;
            $(widget.element).off('click');
            var clearButton = widget._getClearButton();
            clearButton.off('click');
            clearButton.on('click', widget._onClearButtonClick());
        },
        
        _getClearButton: function() {
            return $(this.element).find('.grid-eraser');
        },
        
        _onClearButtonClick: function() {
            var widget = this;
            return function() {
                $(widget.element).find('thead').find('.form-control').val('');
                $(widget.element).yiiGridView('applyFilter');
            }
        },
        
        destroy: function () {
            this._super('_destroy');
        },
    });
} (jQuery));

var initializeGridView = function() {
    $('.grid-view').kartikGridView();
}

$(document).ready(function () {
    initializeGridView();
});

$(document).on('pjax:end',   function() { initializeGridView();});