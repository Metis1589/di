(function ($) {
    $.widget('custom.gridMultipleAction', {

        _create: function () {
            var widget = this;
            $(widget.element).find("a").not('.dropdown-toggle').on("click",function(e){
                var el = $(e.target);
                if (el.is('input')) {
                    e.stopImmediatePropagation();
                } else if (el.is('a')) {
                    var table = $(widget.element).parents('body').find('table');
                    var pjax = table.parent().parent();
                    $.post('update-multiple',
                        {
                            ids : pjax.yiiGridView('getSelectedRows'),
                            property: el.attr('data-prop'),
                            value: el.attr('data-value')
                        });
                }
            });
            $(widget.element).find('input').on('keyup', function(e){
                var el = $(e.target);
                var a = el.parent().parent().find('a');
                a.attr('data-value', el.val());
            });

            console.log();
        },

        destroy: function () {
            this._super('_destroy');
        },

    });
} (jQuery));

var initializeGridMultipleAction = function() {
    $('.grid-multiple-action').gridMultipleAction();
}

initializeGridMultipleAction();

$(document).on('pjax:end',   function() { initializeGridMultipleAction();});