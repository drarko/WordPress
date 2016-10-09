; //defensive semicolon
//////////////////////////////
// Test if touch event exists
//////////////////////////////
function is_touch_device() {
    return jQuery('body').hasClass('touch');
}

function getParameterByName(name, url) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(url);
    if (results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

// Begin jQuery functions
(function ($) {

    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    $(window).load(function () {
        /////////////////////////////////////////////
        // Product slider
        /////////////////////////////////////////////

        function ThemifyProductSlider() {
            // Parse data from wp_localize_script
            themifyShop.autoplay = parseInt(themifyShop.autoplay);
            themifyShop.speed = parseInt(themifyShop.speed);
            themifyShop.scroll = parseInt(themifyShop.scroll);
            themifyShop.visible = parseInt(themifyShop.visible);
            themifyShop.wrap = themifyShop.wrap ? true : false;
            themifyShop.play = themifyShop.autoplay != 0;
            $('.product-slides').carouFredSel({
                responsive: true,
                prev: '#product-slider .carousel-prev',
                next: '#product-slider .carousel-next',
                pagination: "#product-slider .carousel-pager",
                width: '100%',
                circular: themifyShop.wrap,
                infinite: themifyShop.wrap,
                auto: {
                    play: themifyShop.play,
                    pauseDuration: themifyShop.autoplay * 1000,
                    duration: themifyShop.speed
                },
                swipe: true,
                scroll: {
                    items: themifyShop.scroll,
                    duration: themifyShop.speed
                },
                items: {
                    visible: {
                        min: 1,
                        max: themifyShop.visible
                    },
                    width: 150
                },
                onCreate: function () {
                    $('.product-sliderwrap').css({
                        'height': 'auto',
                        'visibility': 'visible'
                    });
                }
            });
        }
        if ($('.product-slides').length > 0) {
            if (!$.fn.carouFredSel) {
                Themify.LoadAsync(themify_vars.url + '/js/carousel.js', ThemifyProductSlider);
            }
            else {
                ThemifyProductSlider();
            }
        }
    });

    $(document).ready(function () {

        /////////////////////////////////////////////
        // Check is_mobile
        /////////////////////////////////////////////
        $('body').addClass(is_touch_device() ? 'is_mobile' : 'is_desktop');

        /////////////////////////////////////////////
        // Toggle Cart
        /////////////////////////////////////////////
        $('body').on('click', '#cart-tag', function (e) {
            e.preventDefault();
            $('#cart-wrap').css('visibility', 'visible').fadeToggle();
        });

        /////////////////////////////////////////////
        // Toggle sorting nav
        /////////////////////////////////////////////
        $("body").on("click", '.sort-by', function (e) {
            if ($(this).next().is(':visible')) {
                $(this).next().slideUp();
                $(this).removeClass('active');
            }
            else {
                $(this).next().slideDown();
                $(this).addClass('active');
            }
            e.preventDefault();
        });

        $("body").on("hover", '.orderby-wrap', function (e) {
            if (e.type == 'mouseenter' && !$(this).find('.orderby').is(':visible')) {
                $(this).find('.orderby').slideDown();
                $(this).find('.sort-by').addClass('active');
            }
            if (e.type == 'mouseleave' && $(this).find('.orderby').is(':visible') && $(this).find('.sort-by').hasClass('active')) {
                $(this).find('.orderby').slideUp();
                $(this).find('.sort-by').removeClass('active');
            }
            e.preventDefault();
        });

        $('body').on('wc_fragments_refreshed', function () {
            $('.is_desktop #cart-wrap').hide().css('visibility', 'visible');
        });

        /////////////////////////////////////////////
        // Add to cart ajax
        /////////////////////////////////////////////
        if (woocommerce_params.option_ajax_add_to_cart == 'yes') {

            // Ajax add to cart
            var $loadingIcon;
            $('body').on('adding_to_cart', function (e, $button, data) {
                var cart = $('#cart-wrap');
                // hide cart wrap
                cart.hide();
                $('#cart-loader').addClass('loading');
                // This loading icon
                $loadingIcon = $('.loading-product', $button.closest('.product')).first();
                $loadingIcon.show();
            }).on('added_to_cart removed_from_cart', function (e, fragments, cart_hash) {
                $('.is_desktop #cart-wrap').hide().css('visibility', 'visible');

                if (typeof $loadingIcon !== 'undefined') {
                    // Hides loading animation
                    $loadingIcon.hide(300, function () {
                        $(this).addClass('loading-done');
                    });
                    $loadingIcon
                            .fadeIn()
                            .delay(500)
                            .fadeOut(300, function () {
                                $(this).removeClass('loading-done');
                            });
                }

                // close lightbox
                if ($('.mfp-content.themify_product_ajax').is(':visible')) {
                    $.magnificPopup.close();
                }
                $('form.cart').find(':submit').removeAttr('disabled');
            });

            // remove item ajax
            $(document).on('click', '.remove-item-js', function () {
                $('#cart-loader').addClass('loading');

                // AJAX add to cart request
                var $thisbutton = $(this);

                var data = {
                    action: 'theme_delete_cart',
                    remove_item: $thisbutton.attr('data-product-key')
                };

                // Ajax action
                $.post(woocommerce_params.ajax_url, data, function (response) {

                    var this_page = window.location.toString();
                    this_page = this_page.replace('add-to-cart', 'added-to-cart');

                    fragments = response.fragments;
                    cart_hash = response.cart_hash;

                    // Block fragments class
                    if (fragments) {
                        $.each(fragments, function (key, value) {
                            $(key).addClass('updating');
                        });
                    }

                    // Block widgets and fragments
                    $('.shop_table.cart, .updating, .cart_totals, .widget_shopping_cart').fadeTo('400', '0.6').block({message: null, overlayCSS: {background: 'transparent url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});

                    // Changes button classes
                    if ($thisbutton.parent().find('.added_to_cart').size() == 0)
                        $thisbutton.addClass('added');

                    // Replace fragments
                    if (fragments) {
                        $.each(fragments, function (key, value) {
                            $(key).replaceWith(value);
                        });
                    }

                    // Unblock
                    $('.widget_shopping_cart, .updating').stop(true).css('opacity', '1').unblock();

                    // Cart page elements
                    $('.shop_table.cart').load(this_page + ' .shop_table.cart:eq(0) > *', function () {

                        $("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" id="add1" class="plus" />').prepend('<input type="button" value="-" id="minus1" class="minus" />');

                        $('.shop_table.cart').stop(true).css('opacity', '1').unblock();

                        $('body').trigger('cart_page_refreshed');
                    });

                    $('.cart_totals').load(this_page + ' .cart_totals:eq(0) > *', function () {
                        $('.cart_totals').stop(true).css('opacity', '1').unblock();
                    });

                    // Trigger event so themes can refresh other areas
                    $('body').trigger('removed_from_cart', [fragments, cart_hash]);

                });

                return false;
            });

            // Ajax add to cart in single page
            ajax_add_to_cart_single_page();

        }

        function lightboxCallback(context) {
            $("a.variable-link", context).each(function () {
                $(this).magnificPopup({
                    type: 'ajax',
                    callbacks: {
                        updateStatus: function (data) {
                            ajax_variation_callback();
                            $('.mfp-content').addClass('themify_product_ajax themify_variable_product_ajax');
                        }
                    }
                });
            });
        }
        // ajax variation lightbox
        function ajax_variation_lightbox(context) {
            if ($("a.variable-link", context).length > 0) {
                Themify.LoadCss(themify_vars.url + '/css/lightbox.css', null);
                Themify.LoadAsync(themify_vars.url + '/js/lightbox.js', function () {
                    lightboxCallback(context)
                    return ('undefined' !== typeof $.fn.magnificPopup);
                });
            }
        }
        // Initial ajax variation lightbox
        ajax_variation_lightbox(document);

        // reply review
        $('.reply-review').click(function () {
            $('#respond').slideToggle('slow');
            return false;
        });

        // add review
        $('.add-reply-js').click(function () {
            $(this).hide();
            $('#respond').slideDown('slow');
            $('#cancel-comment-reply-link').show();
            return false;
        });
        $('#reviews #cancel-comment-reply-link').click(function () {
            $(this).hide();
            $('#respond').slideUp();
            $('.add-reply-js').show();
            return false;
        });

        /*function ajax add to cart in single page */
        function ajax_add_to_cart_single_page() {
            var submitClicked = false;
            $(document).on('click', '.single_add_to_cart_button', function (event) {
                if (!$(this).closest('.product').hasClass('product-type-external')) {
                    event.preventDefault();
                    submitClicked = true;
                    $('form.cart').submit();
                }
            }).on('submit', 'form.cart', function (event) {
                if (submitClicked) {
                    var data = $(this).serializeObject(),
                            data2 = {action: 'theme_add_to_cart'};
                    $.extend(true, data, data2);

                    // Trigger event
                    $('body').trigger('adding_to_cart', [$(this), data]);

                    // Ajax action
                    $.post(woocommerce_params.ajax_url, data, function (response) {

                        submitClicked = false;

                        if (!response)
                            return;

                        if (themifyShop.redirect) {
                            window.location.href = themifyShop.redirect;
                        }
                        var this_page = window.location.toString();
                        this_page = this_page.replace('add-to-cart', 'added-to-cart');

                        fragments = response.fragments;
                        cart_hash = response.cart_hash;

                        // Block fragments class
                        if (fragments) {
                            $.each(fragments, function (key, value) {
                                $(key).addClass('updating');
                            });
                        }

                        // Block widgets and fragments
                        $('.shop_table.cart, .updating, .cart_totals, .widget_shopping_cart').fadeTo('400', '0.6').block({message: null, overlayCSS: {background: 'transparent url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});

                        // Replace fragments
                        if (fragments) {
                            $.each(fragments, function (key, value) {
                                $(key).replaceWith(value);
                            });
                        }

                        // Unblock
                        $('.widget_shopping_cart, .updating').stop(true).css('opacity', '1').unblock();

                        // Cart page elements
                        $('.shop_table.cart').load(this_page + ' .shop_table.cart:eq(0) > *', function () {

                            $("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" id="add1" class="plus" />').prepend('<input type="button" value="-" id="minus1" class="minus" />');

                            $('.shop_table.cart').stop(true).css('opacity', '1').unblock();

                            $('body').trigger('cart_page_refreshed');
                        });

                        $('.cart_totals').load(this_page + ' .cart_totals:eq(0) > *', function () {
                            $('.cart_totals').stop(true).css('opacity', '1').unblock();
                        });

                        // Trigger event so themes can refresh other areas
                        $('body').trigger('added_to_cart', [fragments, cart_hash]);

                    });
                    return false;
                }
            });
        }

        /**
         * Limit the number entered in the quantity field.
         * @param $obj The quantity field object.
         * @param max_qty The max quantity allowed per the inventory current stock.
         */
        function limitQuantityByInventory($obj, max_qty) {
            var qty = $obj.val();
            if (qty > max_qty) {
                $obj.val(max_qty);
            }
        }

        // Limit number entered manually in quantity field in single view
        if ($('body').hasClass('single-product')) {
            $('.entry-summary').on('keyup', 'input[name="quantity"][max]', function () {
                limitQuantityByInventory($('input[name="quantity"]'), parseInt($(this).attr('max'), 10));
            });
        }

        $(document).on('click', '.plus, .minus', function () {

            // Get values
            var $qty = $(this).closest('.quantity').find('.qty'),
                    currentVal = parseFloat($qty.val()),
                    max = parseFloat($qty.prop('max')),
                    min = parseFloat($qty.prop('min')),
                    step = parseFloat($qty.prop('step'));

            // Format values
            if (!currentVal) {
                currentVal = 1;
            }
            if (!max) {
                max = false;
            }
            if (!min) {
                min = false;
            }
            if (!step) {
                step = 1;
            }
            // Change the value
            if ($(this).hasClass('plus')) {
                currentVal = max && currentVal >= max ? max : currentVal + step;
            } else {
                currentVal = min && currentVal <= min ? min : (currentVal > step ? currentVal - step : currentVal);
            }
            // Trigger change event
            $qty.val(currentVal).trigger('change');
        });

        $(document).on('keyup', 'form.cart input[name="quantity"]', function () {
            var $max = parseFloat($(this).prop('max'));
            if ($max > 0) {
                limitQuantityByInventory($(this), parseInt($max, 10));
            }
        });

        /* function ajax variation callback */
        function ajax_variation_callback() {
            var themify_product_variations = $.parseJSON($('#themify_product_vars').text());

            //check if two arrays of attributes match
            function variations_match(attrs1, attrs2) {
                var match = true;
                for (name in attrs1) {
                    var val1 = attrs1[name];
                    var val2 = attrs2[name];

                    if (val1.length != 0 && val2.length != 0 && val1 != val2) {
                        match = false;
                    }
                }

                return match;
            }

            //show single variation details (price, stock, image)
            function show_variation(variation) {
                var img = $('div.images img:eq(0)');
                var link = $('div.images a.zoom:eq(0)');
                var o_src = $(img).attr('original-src');
                var o_link = $(link).attr('original-href');

                var variation_image = variation.image_src;
                var variation_link = variation.image_link;

                if (true == variation.is_in_stock) {
                    $('.variations_button').show();
                } else {
                    $('.variations_button').hide();
                }
                $('.single_variation').html(variation.price_html + variation.availability_html);

                if (!o_src) {
                    $(img).attr('original-src', $(img).attr('src'));
                }

                if (!o_link) {
                    $(link).attr('original-href', $(link).attr('href'));
                }

                if (variation_image && variation_image.length > 1) {
                    $(img).attr('src', variation_image);
                    $(link).attr('href', variation_link);
                } else {
                    $(img).attr('src', o_src);
                    $(link).attr('href', o_link);
                }

                if (variation.sku) {
                    $('.product_meta').find('.sku').text(variation.sku);
                } else {
                    $('.product_meta').find('.sku').text('');
                }
                $('.single_variation_wrap').slideDown('200').trigger('variationWrapShown').trigger('show_variation');
            }

            //disable option fields that are unavaiable for current set of attributes
            function update_variation_values(variations) {

                // Loop through selects and disable/enable options based on selections
                $('.variations select').each(function (index, el) {

                    current_attr_select = $(el);

                    // Disable all
                    current_attr_select.find('option:gt(0)').attr('disabled', 'disabled');

                    // Get name
                    var current_attr_name = current_attr_select.attr('name');

                    // Loop through variations
                    for (num in variations) {
                        var attributes = variations[num].attributes;

                        for (attr_name in attributes) {
                            var attr_val = attributes[attr_name];

                            if (attr_name == current_attr_name) {
                                if (attr_val) {

                                    // Decode entities
                                    attr_val = $("<div/>").html(attr_val).text();

                                    // Add slashes
                                    attr_val = attr_val.replace(/'/g, "\\'");
                                    attr_val = attr_val.replace(/"/g, "\\\"");

                                    // Compare the meercat
                                    current_attr_select.find('option[value="' + attr_val + '"]').removeAttr('disabled');

                                } else {
                                    current_attr_select.find('option').removeAttr('disabled');
                                }
                            }
                        }
                    }
                });
            }

            //search for matching variations for given set of attributes
            function find_matching_variations(settings) {
                var matching = [];

                for (var i = 0; i < themify_product_variations.length; i++) {
                    var variation = themify_product_variations[i];
                    if (variations_match(variation.attributes, settings)) {
                        matching.push(variation);
                    }
                }
                return matching;
            }

            //when one of attributes is changed - check everything to show only valid options
            function check_variations(exclude) {
                var all_set = true;
                var current_settings = {};

                $('.variations select').each(function () {

                    if (exclude && $(this).attr('name') == exclude) {

                        all_set = false;
                        current_settings[$(this).attr('name')] = '';

                    } else {
                        if ($(this).val().length == 0)
                            all_set = false;

                        // Encode entities
                        value = $(this).val().replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

                        // Add to settings array
                        current_settings[$(this).attr('name')] = value;
                    }

                });

                var matching_variations = find_matching_variations(current_settings);

                if (all_set) {
                    var variation = matching_variations.pop();
                    if (variation) {
                        $('form input[name="variation_id"]').val(variation.variation_id);
                        show_variation(variation);
                    } else {
                        // Nothing found - reset fields
                        $('.variations select').val('');
                    }
                } else {
                    update_variation_values(matching_variations);
                }
            }

            $('.variations select').change(function () {
                $('form input[name="variation_id"]').val('');
                $('.single_variation_wrap').hide();
                $('.single_variation').text('');
                check_variations();
                $(this).blur();
                if ($().uniform && $.isFunction($.uniform.update)) {
                    $.uniform.update();
                }
            }).change();

            // Quantity buttons
            $("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" id="add1" class="plus" />').prepend('<input type="button" value="-" id="minus1" class="minus" />');

        }

    });

}(jQuery));