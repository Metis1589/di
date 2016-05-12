(function ($) {
    $.widget('custom.restaurantGroupTree', {

        _create: function () {
            $(this.element)
                .on('loaded.jstree', function(event, data) {
                    data.instance.open_all();
                })
                .on('changed.jstree', function (e, data) {
                    console.log(data.node.data.url);
                    $.pjax.reload({
                        container:"#restaurant-table",
                        url: data.node.data.url,
                        timeout: 50000
                    });
            }).jstree();
        },
        
        destroy: function () {
            this._super('_destroy');
        },

    });
} (jQuery));

var initializeRestaurantGroupTree = function() {
    $('.restaurant-group-tree').restaurantGroupTree();
}

initializeRestaurantGroupTree();