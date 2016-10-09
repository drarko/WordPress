(function ($) {
    'use strict';
    $(document).ready(function () {
    
        $('#query-posts input[name="layout"],#query-portfolio input[name="portfolio_layout"]').change(function (e) {
            var $val = $(this).val(),
                    $post_type = $(this).parents('#query-posts').length > 0 ? '' : 'portfolio_',
                    $masonary = $('#' + $post_type + 'disable_masonry').closest('.themify_field_row'),
                    $post_gutter = $post_type == '' ? $('#post_gutter') : $('#portfolio_content_layout'),
                    $post_gutter = $post_gutter.closest('.themify_field_row'),
                    $category = $('#' + $post_type + 'query_category').val();


            // SlideUp/animation doesn't work when element is hidden
            if (!$category) {
                $masonary.hide();
                
                $post_gutter.hide();
                return;
            }
            if ($val === 'list-post' || $val === 'zig-zag') {
                $masonary.slideUp();
                $post_gutter.slideUp();
            }
            else {
                $masonary.slideDown();
                $post_gutter.slideDown();
            }
        });
        $('input[name="header_design"]').change(function (e) {
                var $val = $(this).val(),
                        $header_wrap = $('#header_wrap-transparent'),
                        $header_wrap_label = $header_wrap.next('label'),
                        $menu_overlay = $('#menu_style').closest('.themify_field_row');
                if($val==='header-leftpane' || $val==='header-rightpane'){
                        $header_wrap.fadeOut();
                        $header_wrap_label.fadeOut();
                        $menu_overlay.fadeOut();
                }
                else if($val!='none'){
                        $header_wrap.fadeIn();
                        $header_wrap_label.fadeIn();
                        $menu_overlay.fadeIn();
                }
        });
        $('#query_category,#portfolio_query_category').change(function () {
            var $post_type = $(this).closest('#query_category').length > 0 ? '' : 'portfolio_';
            $('input[name="' + $post_type + 'layout"],#' + $post_type + 'more_posts').trigger('change');
        });

        $('#portfolio_more_posts, #more_posts').change(function (e) {
            var $val = $(this).val(),
                    $post_type = $(this).parents('#query-posts').length > 0 ? '' : 'portfolio_',
                    $pagination = $('#' + $post_type + 'hide_navigation'),
                    $category = $('#' + $post_type + 'query_category').val();

            $pagination = $pagination.closest('.themify_field_row');
            if (!$category) {
                $pagination.hide();
                return;
            }
            if ($val === 'infinite' || !$('#' + $post_type + 'query_category').val()) {
                $pagination.slideUp();
            }
            else {
                $pagination.slideDown();
            }
        });
        $('input[name="header_design"]').trigger('change');
        $('#query_category,#portfolio_query_category').trigger('change');
    });

}(jQuery));