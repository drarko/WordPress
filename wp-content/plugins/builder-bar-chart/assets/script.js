(function ($) {

    function bar_charts_waypoint() {
        $('.module-bar-chart .bc-chart li').each(function () {
            var $this = $(this);
            $(this).waypoint(function () {
                var bar = $this.find('.bc-bar'),
                        data_height = bar.attr("data-height");
                bar.addClass('animate');
                bar.css("height", data_height + "%");
            }, {
                offset: '100%',
                triggerOnce: true
            });
        });
    }

    function do_bar_charts() {
        Themify.LoadAsync(themify_vars.url + '/js/waypoints.min.js', bar_charts_waypoint, null, null, function () {
            return ('undefined' !== typeof $.fn.waypoint);
        });
    }

    $('body').on('builder_load_module_partial', do_bar_charts);
    $('body').on('builder_toggle_frontend', do_bar_charts);
    $(document).ready(do_bar_charts);

})(jQuery);