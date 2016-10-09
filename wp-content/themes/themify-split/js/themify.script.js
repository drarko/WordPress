/* Themify Theme Scripts - http://themify.me/ */
/* global ThemifyBuilderModuleJs, themifyScript, tbLocalScript, ThemifyMediaElement, qp_max_pages */

var LayoutAndFilter = {},FixedHeader = {};

;
(function ($) {

    'use strict';

    var t, n, r, s = $.event, i = {_: 0}, h = 0;
    t = s.special.throttledresize = {setup: function () {
            $(this).on("resize", t.handler);
        }, teardown: function () {
            $(this).off("resize", t.handler);
        }, handler: function (o, a) {
            var l = this, d = arguments;
            n = !0, r || (setInterval(function () {
                h++, (h > t.threshold && n || a) && (o.type = "throttledresize", s.dispatch.apply(l, d), n = !1, h = 0), h > 9 && ($(i).stop(), r = !1, h = 0);
            }, 30), r = !0);
        }, threshold: 0};

    var $body = $('body');
    themifyScript.isTouch = $body.hasClass('touch');
    themifyScript.splitScroll = themifyScript.splitScroll ? true : false;
    themifyScript.isTablet = $body.hasClass('istablet');
    themifyScript.splitScrollThreshold = parseInt(themifyScript.splitScrollThreshold);
    themifyScript.isRtl = $body.hasClass('rtl');

    var isSingle = $body.hasClass('single'),
            isSplitScroll = 'function' === typeof $.fn.multiscroll && themifyScript.splitScroll && $('.themify_builder_row').length > 0 && '' !== $.trim($('.page-content .themify_builder_content').html()),
            isSplitScrollActive = false,
            sectionClass = ($(window).width() <= 680 ? '.themify_builder_row:not(.hide-mobile)' : ($(window).width() > 1024 ? '.themify_builder_row:not(.hide-desktop)' : '.themify_builder_row:not(.hide-tablet)')),
            sectionsWrapper = '.themify_builder_content',
            navigateTo = '',
            load = true,
            firstSplitInit = true,
            toIndex = -1,
            alreadyVisible = [];
    if (isSplitScroll) {
        Themify.LoadCss(themifyScript.url + '/js/splitscroll.css');
    }
    // Get builder rows anchor class to ID
    function getClassToId($section) {
        var classes = $section.prop('class').split(' '),
                expr = new RegExp('^tb_section-', 'i'),
                spanClass = null;
        for (var i = 0; i < classes.length; i++) {
            if (expr.test(classes[i])) {
                spanClass = classes[i];
            }
        }
        if (spanClass === null) {
            return '';
        }
        return spanClass.replace('tb_section-', '');
    }

    function disableSplitScroll(byWidth) {
        byWidth = byWidth || false;

        if (!$body.hasClass('split-scroll')) {
            return;
        }

        if ('function' === typeof $.fn.multiscroll && 'function' === typeof $.fn.multiscroll.destroy) {
            $.fn.multiscroll.destroy();
        }
        $('html, body').css({
            'overflow': 'visible',
            'height': 'initial'
        });

        $('#right-content, #multiscroll-nav').remove();

        if (byWidth) {
            $('.row_inner_wrapper').unwrap();
            $('.ms-section').unwrap().unwrap();
        }

        $('#multiscroll').remove();

        $body.removeClass('split-scroll').addClass('split-scroll-off');

        isSplitScrollActive = false;
    }

    function ScrollToHash(section_id) {
        if (section_id && '' !== section_id) {
            var sectionEl = '.tb_section-' + section_id.replace('#', '');
            if ($(sectionEl).length > 0) {
                if (isSplitScroll && isSplitScrollActive) {
                    var index_el = $(sectionEl).index(sectionClass) + 1;
                    $.fn.multiscroll.moveTo(index_el);
                } else {
                    var offset = $(sectionEl).offset().top;
                    if (typeof tbScrollHighlight !== 'undefined' && tbScrollHighlight.fixedHeaderSelector !== 'undefined' && $(tbScrollHighlight.fixedHeaderSelector).length > 0) {
                        offset -= $(tbScrollHighlight.fixedHeaderSelector).outerHeight(true);
                    }
                    themeScrollTo(offset);
                }
                if ($(window).width() <= 1200 && $body.hasClass('mobile-menu-visible')) {
                    $('#menu-icon-close').trigger('click');
                }
            }
        }
    }

    function getRowAnchorFromRow($row) {
        var rowClasses = $row.attr('class'),
                regExp = /(?:^|\s)tb_section-(\S+)/g,
                matches = regExp.exec(rowClasses),
                rowAnchor = '';

        if (matches !== null) {
            rowAnchor = matches.pop();
        }

        return rowAnchor;
    }

    function refreshSplitScroll() {
        if ($(window).width() >= themifyScript.splitScrollThreshold) {
            if (!isSplitScrollActive) {
                createSplitScroll();
            }
        } else if ($(window).width() < themifyScript.splitScrollThreshold && isSplitScrollActive) {
            disableSplitScroll(true);
        }
    }

    function createSplitScroll() {
        if ('function' !== typeof $.fn.multiscroll || !themifyScript.splitScroll || $('.themify_builder_row').length <= 0 || '' === $.trim($('.page-content .themify_builder_content').html())) {
            return;
        }

        if ($body.hasClass('themify_builder_active')) {
            return;
        }

        var $builderContent = $('.page-content').find('.themify_builder_content:first'),
                splitScrollContainer = $('<div id="multiscroll"></div>'),
                leftContent = $('<div id="' + (themifyScript.isRtl ? 'right' : 'left') + '-content"></div>'),
                rightContent = $('<div id="' + (themifyScript.isRtl ? 'left' : 'right') + '-content"></div>'),
                navigationTooltips = [];

        function hideRightContent() {
            if (!themifyScript.isRtl) {
                leftContent.css('overflow', 'visible');
                rightContent.css('visibility', 'hidden');
            } else {
                rightContent.css('overflow', 'visible');
                leftContent.css('visibility', 'hidden');
            }
        }

        function showRightContent() {
            if (!themifyScript.isRtl) {
                leftContent.css('overflow', 'hidden');
                rightContent.css('visibility', 'visible');
            } else {
                rightContent.css('overflow', 'hidden');
                leftContent.css('visibility', 'visible');
            }
        }

        $body.removeClass('split-scroll-off').addClass('split-scroll');

        splitScrollContainer.append(leftContent.add(rightContent));

        $builderContent.prepend(splitScrollContainer);

        $builderContent.find(sectionClass).each(function () {
            var rowLeft = $(this);
            var rowRight = null;

            if (!themifyScript.isTablet) {
                rowRight = rowLeft.clone();

                // Resolve carousel conflict after clone
                var tempDate = new Date(),
                        tempCounter = 0,
                        tempId = 'temp-id-' + tempDate.getTime() + '-';
                rowRight.find('.themify_builder_slider').each(function () {
                    tempCounter++;
                    $(this).attr('data-id', tempId + tempCounter);
                    $(this).closest('.module').attr('id', tempId + tempCounter);
                });
            } else {
                rowRight = $('<div>', {class: 'ms-section'});
            }

            navigationTooltips.push(getRowAnchorFromRow(rowLeft).replace(/-/g, ' '));

            rowRight.removeClass(function (index, classes) {
                return (classes.match(/(^|\s)tb_section-\S+/g) || []).join(' ');
            });

            leftContent.append(rowLeft);
            rightContent.append(rowRight);
        });


        setTimeout(function () {
            // pause video if has any
            if (!themifyScript.isRtl) {
                rightContent.find('[data-fullwidthvideo] video').trigger('pause');
            } else {
                leftContent.find('[data-fullwidthvideo] video').trigger('pause');
            }

            // Fix iframe issue on safari @github:issue#3065
            if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
                $('#multiscroll .module iframe[src*="youtube.com"], #multiscroll .module iframe[src*="vimeo.com"]').each(function () {
                    this.src = this.src;
                });
            }
        }, 5000);

        splitScrollContainer.multiscroll({
            sectionSelector: sectionClass,
            leftSelector: '#left-content',
            rightSelector: '#right-content',
            css3: true,
            navigation: true,
            navigationTooltips: navigationTooltips,
            afterRender: function () {
                hideRightContent();

                var $section = $(sectionClass + '.active', $(sectionsWrapper)),
                        section_id = $section.is('[class*="tb_section-"]') ? getClassToId($section) : $section.prop('id'),
                        $aSectionHref = $('#main-nav').find('a[href="#' + section_id + '"]');

                if ($aSectionHref.length > 0) {
                    $aSectionHref.closest('li').addClass('current_page_item').siblings().removeClass('current_page_item');
                }

                $body.trigger('themify_onepage_after_render', [$section, section_id]);

                if (themifyScript.isRtl) {
                    var nav = $('#multiscroll-nav');
                    nav.css("margin-top", "-" + nav.height() / 2 + "px");
                }
            },
            afterLoad: function () {
                hideRightContent();

                var $section = $(sectionClass + '.active', $(sectionsWrapper)),
                        section_id = $section.is('[class*="tb_section-"]') ? getClassToId($section) : $section.prop('id'),
                        $aSectionHref = $('#main-nav').find('a[href="#' + section_id + '"]');

                if ($aSectionHref.length > 0) {
                    $aSectionHref.closest('li').addClass('current_page_item').siblings().removeClass('current_page_item');
                }

                if ('undefined' === typeof alreadyVisible[toIndex]) {
                    alreadyVisible[toIndex] = true;
                    var $rightRows = rightContent.find(sectionClass),
                            $leftRows = leftContent.find(sectionClass);
                    $rightRows.eq($rightRows.length - (toIndex + 1)).find('.module').css('visibility', 'visible');
                    $leftRows.eq(toIndex).find('.module').css('visibility', 'visible');
                }

                $body.trigger('themify_onepage_afterload', [$section, section_id]);
            },
            onLeave: function () {

                if ('undefined' === typeof alreadyVisible[toIndex]) {
                    var $rightRows = rightContent.find(sectionClass),
                            $leftRows = leftContent.find(sectionClass);
                    $rightRows.eq($rightRows.length - (toIndex + 1)).find('.module').css('visibility', 'hidden');
                    $leftRows.eq(toIndex).find('.module').css('visibility', 'hidden');
                }

                showRightContent();
            }

        });

        isSplitScrollActive = true;

        if (themifyScript.isTablet) {
            makeFullPageScroll(leftContent, sectionClass);
        }
    }

    function makeFullPageScroll($leftContent, sectionClass) {
        $leftContent.css('width', '100%');

        $leftContent.find(sectionClass).each(function () {
            var $section = $(this);
            $section.get(0).style.setProperty('width', '100%', 'important');

        });
    }

    // Scroll to Element //////////////////////////////
    function themeScrollTo(offset) {
        $('body,html').animate({scrollTop: Math.ceil(offset)}, 800);
    }

    // Make map full height //////////////////////////
    function makeFullHeightMap() {
        var $map = $('.module-map.fullheight-map');
        if ($map.length > 0) {
            $map.find('[id^="themify_map_canvas_"], > div[data-map], .gm-style').css('height', $(window).height());
        }
    }
    // Fixed Header /////////////////////////
    FixedHeader = {
        headerHeight: 0,
        init: function() {
            FixedHeader.calculateHeaderHeight();
            if(isSplitScroll){
                FixedHeader.activate($(sectionClass + '.active', $(sectionsWrapper)).index()>0);
                $('body').on('themify_onepage_afterload',function (event, $section, section_id){
                        FixedHeader.activate($(sectionClass + '.active', $(sectionsWrapper)).index()>0);
                });
            }
            else{
                FixedHeader.activate(false);
                $(window).on('scroll touchstart.touchScroll touchmove.touchScroll', function(e){
                        FixedHeader.activate(false);
                });
            }
        },
        activate: function($hard) {
            var $window = $(window),
                scrollTop = $window.scrollTop(),
                $headerWrap = $('#headerwrap');

            if($hard || (scrollTop >= FixedHeader.headerHeight )) {
                if ( ! $headerWrap.hasClass( 'fixed-header' ) ) {
                        FixedHeader.scrollEnabled();
                }
            } else if ( $headerWrap.hasClass( 'fixed-header' ) ) {
                FixedHeader.scrollDisabled();
            }
        },
        scrollDisabled: function() {
                $('#headerwrap').removeClass('fixed-header');
        },
        scrollEnabled: function() {
                $('#headerwrap').addClass('fixed-header');
        },
        calculateHeaderHeight : function(){
                FixedHeader.headerHeight = $('#headerwrap').outerHeight(true);
        }
    };


    // Entry Filter /////////////////////////
    LayoutAndFilter = {
        masonryActive: false,
        filterActive: false,
        scrolling: false,
        init: function () {
            themifyScript.disableMasonry = $('body').hasClass('masonry-enabled') ? '' : 'disable-masonry';
            if ('disable-masonry' !== themifyScript.disableMasonry) {
                $('.post-filter + .portfolio.list-post,.loops-wrapper.grid4,.loops-wrapper.grid3,.loops-wrapper.grid2,.loops-wrapper.portfolio.grid4,.loops-wrapper.portfolio.grid3,.loops-wrapper.portfolio.grid2').prepend('<div class="grid-sizer">').prepend('<div class="gutter-sizer">');
                this.enableFilters();
                this.filter();
                this.filterActive = true;
            }
        },
        enableFilters: function () {
            var $filter = $('.post-filter');
            if ($filter.find('a').length > 0 && 'undefined' !== typeof $.fn.isotope) {
                $filter.find('li').each(function () {
                    var $li = $(this),
                            $entries = $li.parent().next(),
                            cat = $li.attr('class').replace(/(current-cat)|(cat-item)|(-)|(active)/g, '').replace(' ', '');
                    if ($entries.find('.portfolio-post.cat-' + cat).length <= 0) {
                        $li.hide();
                    } else {
                        $li.show();
                    }
                });
            }
        },
        filter: function () {
            var $filter = $('.post-filter');
            if ($filter.find('a').length > 0 && 'undefined' !== typeof $.fn.isotope) {
                $filter.addClass('filter-visible').on('click', 'a', function (e) {
                    e.preventDefault();
                    var $li = $(this).parent(),
                            $entries = $li.parent().next();
                    if ($li.hasClass('active')) {
                        $li.removeClass('active');
                        $entries.isotope({
                            masonry: {
                                columnWidth: '.grid-sizer',
                                gutter: '.gutter-sizer'
                            },
                            filter: '.portfolio-post',
                            isOriginLeft: !$('body').hasClass('rtl')
                        });
                    } else {
                        $li.siblings('.active').removeClass('active');
                        $li.addClass('active');
                        $entries.isotope({
                            filter: '.cat-' + $li.attr('class').replace(/(current-cat)|(cat-item)|(-)|(active)/g, '').replace(' ', ''),
                            isOriginLeft: !$('body').hasClass('rtl')
                        });
                    }
                });
            }
        },
        reset: function () {
            $('.post-filter').find('li.active').find('a').addClass('previous-active').trigger('click');
            this.scrolling = true;
        },
        restore: function () {
            //$('.previous-active').removeClass('previous-active').trigger('click');
            var $first = $('.newItems').first(),
                    self = this,
                    to = $first.offset().top - ($first.outerHeight(true) / 2),
                    speed = 800;

            if (to >= 800) {
                speed = 800 + Math.abs((to / 1000) * 100);
            }
            $('html,body').stop().animate({
                scrollTop: Math.ceil(to)
            }, speed, function () {
                self.scrolling = false;
            });
        },
        layout: function () {
            if ('disable-masonry' !== themifyScript.disableMasonry) {
                $('.post-filter + .portfolio.list-post,.loops-wrapper.portfolio.grid4,.loops-wrapper.portfolio.grid3,.loops-wrapper.portfolio.grid2,.loops-wrapper.portfolio-taxonomy').isotope({
                    masonry: {
                        columnWidth: '.grid-sizer',
                        gutter: '.gutter-sizer'
                    },
                    itemSelector: '.portfolio-post',
                    isOriginLeft: !$('body').hasClass('rtl')
                }).addClass('masonry-done');

                $('.loops-wrapper.grid4,.loops-wrapper.grid3,.loops-wrapper.grid2').not('.portfolio-taxonomy,.portfolio')
                        .isotope({
                            masonry: {
                                columnWidth: '.grid-sizer',
                                gutter: '.gutter-sizer'
                            },
                            itemSelector: '.loops-wrapper > article',
                            isOriginLeft: !$('body').hasClass('rtl')
                        })
                        .addClass('masonry-done')
                        .isotope('once', 'layoutComplete', function () {
                            $(window).trigger('resize');
                        });

                $('.woocommerce.archive').find('#content').find('ul.products').isotope({
                    layoutMode: 'packery',
                    itemSelector: '.product',
                    isOriginLeft: !$('body').hasClass('rtl')
                }).addClass('masonry-done');

                this.masonryActive = true;
            }
        },
        reLayout: function () {
            var self = this;
            $('.loops-wrapper').each(function () {
                var $loopsWrapper = $(this);
                if ('object' === typeof $loopsWrapper.data('isotope')) {
                    if (self.masonryActive) {
                        $loopsWrapper.isotope('layout');
                    }
                }
            });
            var $gallery = $('.gallery-wrapper.packery-gallery');
            if ($gallery.length > 0 && 'object' === typeof $gallery.data('isotope')) {
                if (self.masonryActive) {
                    $gallery.isotope('layout');
                }
            }
        }
    };

    function doInfinite($container, selector) {

        if ('undefined' !== typeof $.fn.infinitescroll) {

            // Get max pages for regular category pages and home
            var scrollMaxPages = parseInt(themifyScript.maxPages);

            // Get max pages for Query Category pages
            if (typeof qp_max_pages !== 'undefined') {
                scrollMaxPages = qp_max_pages;
            }

            // infinite scroll
            $container.infinitescroll({
                navSelector: '#load-more a:last', // selector for the paged navigation
                nextSelector: '#load-more a:last', // selector for the NEXT link (to page 2)
                itemSelector: selector, // selector for all items you'll retrieve
                loadingText: '',
                donetext: '',
                loading: {img: themifyScript.loadingImg},
                maxPage: scrollMaxPages,
                behavior: 'auto' !== themifyScript.autoInfinite ? 'twitter' : '',
                pathParse: function (path) {
                    return path.match(/^(.*?)\b2\b(?!.*\b2\b)(.*?$)/).slice(1);
                },
                bufferPx: 10,
                pixelsFromNavToBottom: 30
            }, function (newElements) {
                // call Isotope for new elements
                var $newElems = $(newElements);

                // Mark new items: remove newItems from already loaded items and add it to loaded items
                $('.newItems').removeClass('newItems');
                $newElems.addClass('newItems');

                if ('reset' === themifyScript.resetFilterOnLoad) {
                    // Make filtered elements visible again
                    LayoutAndFilter.reset();
                }
                if ('function' === typeof $.fn.imagesLoaded) {
                    $newElems.hide().imagesLoaded(function () {

                        $newElems.css({'margin-left': 0}).fadeIn();

                        $('.wp-audio-shortcode, .wp-video-shortcode').not('div').each(function () {
                            var $self = $(this);
                            if ($self.closest('.mejs-audio').length > 0) {
                                ThemifyMediaElement.init($self);
                            }
                        });

                        // Apply lightbox/fullscreen gallery to new items
                        Themify.InitGallery();
                        if (LayoutAndFilter.masonryActive && 'object' === typeof $container.data('isotope')) {
                            $container.isotope('appended', $newElems);
                        }
                        if (LayoutAndFilter.filterActive) {
                            // If new elements with new categories were added enable them in filter bar
                            LayoutAndFilter.enableFilters();

                            if ('scroll' === themifyScript.scrollToNewOnLoad) {
                                LayoutAndFilter.restore();
                            }
                        }

                        $('#infscr-loading').fadeOut('normal');
                        if (1 === scrollMaxPages) {
                            $('#load-more, #infscr-loading').remove();
                        }

                        /**
                         * Fires event after the elements and its images are loaded.
                         *
                         * @event infiniteloaded.themify
                         * @param {object} $newElems The elements that were loaded.
                         */
                        $('body').trigger('infiniteloaded.themify', [$newElems]);

                        $(window).trigger('resize');
                    });
                }
                scrollMaxPages = scrollMaxPages - 1;
                if (1 < scrollMaxPages && 'auto' !== themifyScript.autoInfinite) {
                    $('.load-more-button').show();
                }
            });

            // disable auto infinite scroll based on user selection
            if ('auto' === themifyScript.autoInfinite) {
                $('#load-more, #load-more a').hide();
            }
        }
    }

    // DOCUMENT READY
    $(document).ready(function () {
        FixedHeader.init();
        // Mobile Menu
        $('#menu-icon').themifySideMenu({
            close: '#menu-icon-close'
        });
        var $overlay = $('<div class="body-overlay">');
        $body.append($overlay).on('sidemenushow.themify', function () {
            $overlay.addClass('body-overlay-on');
        }).on('sidemenuhide.themify', function () {
            $overlay.removeClass('body-overlay-on');
        }).on('click.themify touchend.themify', '.body-overlay', function () {
            $('#menu-icon').themifySideMenu('hide');
        });

        $(window).resize(function () {
            if ($('#menu-icon').is(':visible') && $('#mobile-menu').hasClass('sidemenu-on')) {
                $overlay.addClass('body-overlay-on');
            }
            else {
                $overlay.removeClass('body-overlay-on');
            }
        });
        // touch dropdown menu
        if ($('body').hasClass('touch') && typeof jQuery.fn.themifyDropdown != 'function') {
            Themify.LoadAsync(themify_vars.url + '/js/themify.dropdown.js', function () {
                $('#main-nav').themifyDropdown();
            });
        }

        if (isSingle) {
            // Image Background
            if ('function' === typeof $.fn.backstretch && themifyScript.backImage && '' !== themifyScript.backImage) {
                $('.single').find('.featured-area').backstretch(themifyScript.backImage);
            }

            // Video Background
            if ('false' === themifyScript.isTouch && 'function' === typeof $.BigVideo && themifyScript.backVideo && '' !== themifyScript.backVideo) {
                var videoBackground = new $.BigVideo({
                    doLoop: true,
                    ambient: true,
                    container: $('.single-post, .single-portfolio').find('.featured-area'),
                    id: 0,
                    poster: themifyScript.backImage
                });
                videoBackground.init();
                videoBackground.show(themifyScript.backVideo);
            }
        }

        /////////////////////////////////////////////
        // Initialize Packery Layout and Filter
        /////////////////////////////////////////////
        LayoutAndFilter.init();

        // Top spacing
        var $wpadminbar = $('#wpadminbar'), topHeight, abHeight;
        if ($wpadminbar.length > 0) {
            abHeight = isSplitScroll ? 0 : $wpadminbar.outerHeight(true);
            topHeight = Math.floor($('#headerwrap').outerHeight(true) - abHeight);

            $(window).on('throttledresize', function () {
                abHeight = isSplitScroll ? 0 : $wpadminbar.outerHeight(true);
                topHeight = Math.floor($('#headerwrap').outerHeight(true));
                $('#pagewrap').css('paddingTop', topHeight - abHeight);
                $('.sidebar-none .featured-area').css('paddingTop', topHeight);
            });
        } else {
            topHeight = Math.floor($('#headerwrap').outerHeight(true));

            $(window).on('throttledresize', function () {
                topHeight = Math.floor($('#headerwrap').outerHeight(true));
                $('#pagewrap').css('paddingTop', topHeight);
                $('.sidebar-none .featured-area').css('paddingTop', topHeight);
            });
        }
        $(window).trigger('throttledresize');

        /////////////////////////////////////////////
        // Split Scroll
        /////////////////////////////////////////////
        if (isSplitScroll) {
            // Get rid of wow js animation since animation is managed with multiscroll js
            if ('undefined' !== typeof Themify) {
                Themify.wowInit2 = Themify.wowInit;
                Themify.wowInit = function () {
                };
            }
            if (firstSplitInit) {
                $.each(tbLocalScript.animationInviewSelectors, function (index, selector) {
                    $(selector).css('visibility', 'hidden');
                });
                firstSplitInit = false;
            }
            createSplitScroll();
        }
        $body.on('builder_toggle_frontend', function (event, is_edit) {
            if ('function' === typeof $.fn.multiscroll) {
                if (1 === is_edit && isSplitScrollActive) {
                    disableSplitScroll();
                } else {
                    createSplitScroll();
                }
            }
        });
        if ($(window).width() < themifyScript.splitScrollThreshold) {
            disableSplitScroll(true);
            $body.addClass('split-scroll-off');
        }

        // Recalculate each waypoint trigger point for modules that use waypoints
        $body.on('themify_onepage_afterload themify_onepage_after_render', function () {
            if ($.fn.waypoint) {
                Waypoint.refreshAll();
            }
        });
        // Don't let Chrome autoplay the video background
        $body.on('themify_onepage_after_render', function () {
            if ('undefined' !== typeof ThemifyBuilderModuleJs && ThemifyBuilderModuleJs.fwvideos && ThemifyBuilderModuleJs.fwvideos.length > 0) {
                $.each(ThemifyBuilderModuleJs.fwvideos, function (i, video) {
                    video.getPlayer().play();
                });
            }
        });
        $body.on('themify_onepage_afterload', function (e, $panel) {
            // Layout again once the panel is in viewport
            LayoutAndFilter.reLayout();
            // Trigger wow display for elements in this panel
            if (tbLocalScript && tbLocalScript.animationInviewSelectors) {
                $(tbLocalScript.animationInviewSelectors).each(function (i, selector) {
                    $(selector, $panel).each(function () {
                        Themify.wow.show(this);
                    });
                });
            }
        });
        $body.on('click', 'li[data-tooltip]', function (e) {
            var $self = $(e.currentTarget);
            toIndex = $self.index();
        });

        /////////////////////////////////////////////
        // Load Transition
        /////////////////////////////////////////////
        if ('1' === themifyScript.pageLoaderEffect) {
            $(themifyScript.protectLinks).addClass('themify-no-exit');

            $('<div class="split-panel split-left" />').prependTo($body);
            $('<div class="split-panel split-right" />').prependTo($body);

            $body.on('click', themifyScript.doNotTriggerExit, function (e) {
                // Check that user isn't pressing ctrl in Win/*nix or cmd in OS X
                if (!e.ctrlKey && !e.metaKey) {
                    load = true;
                    var navigateTo = e.currentTarget.href ? e.currentTarget.href : $(e.target).prop('href'), // e.target.href for IE
                            parseURL = document.createElement('a');
                    parseURL.href = navigateTo;
                    if (parseURL.hostname !== window.location.hostname && parseURL.pathname !== window.location.pathname.slice(0, -1)) {
                        load = typeof navigateTo !== 'undefined' && navigateTo.indexOf('mailto:') === -1 && navigateTo.indexOf('tel:') === -1 && navigateTo.indexOf('fax:') === -1 && navigateTo.indexOf('javascript:') === -1;
                        if (load) {
                            $body.removeClass('ready-view');
                            e.preventDefault();
                            $(this).delay(2000).fadeIn(1000, function () {
                                window.location = navigateTo;
                            });
                        }
                        else if ('undefined' === typeof navigateTo) {
                            $body.removeClass('ready-view');
                        }
                    }
                }
            });
        }

        $body.on('click', 'a[href*="#"]:not([href="#"])', function (e) {
            var section_id = $(e.currentTarget).prop('hash');
            var sectionEl = '.tb_section-' + section_id.replace('#', '');
            if ($(sectionEl).length > 0) {
                e.preventDefault();
                ScrollToHash(section_id);
            }
        });

        var didResize = false;
        $(window).on('resize', function () {
            didResize = true;
        });
        setInterval(function () {
            if (didResize) {
                didResize = false;

                makeFullHeightMap();
                refreshSplitScroll();
            }
        }, 250);

        $(window).trigger('resize');

        // Scroll to top or expand footer.
        $('.back-top a').on('click', function (e) {
            e.preventDefault();
            if ($body.hasClass('split-scroll')) {
                $('.split-scroll #footerwrap').toggleClass('expanded');
            } else {
                $('body,html').animate({scrollTop: 0}, 800);
            }
        });

    }); // End document ready

    // WINDOW LOAD
    $(window).load(function () {


        // Hide split loader and setup animation
        if ('1' === themifyScript.pageLoaderEffect) {
            $('.split-loader').fadeOut(300, function () {
                if ('undefined' !== typeof Themify && 'undefined' !== typeof Themify.wowInit2) {
                    Themify.wowInit2();
                }
                $(this).remove();
                $body.addClass('ready-view').removeClass('hidden-view');
            });
        }

        // If there's hash in URL and a matching menu item/link doesn't exist, go to it manually
        if (window.location.hash) {
            var hash = window.location.hash.replace('#', '');
            if ($('a[href="' + hash + '"]').length <= 0) {
                $('[data-tooltip="' + hash + '"] > a').trigger('click');
                ScrollToHash(hash);
            }
        }

        /////////////////////////////////////////////
        // Entry Filter Layout
        /////////////////////////////////////////////
        if ('function' === typeof $.fn.imagesLoaded) {
            $body.imagesLoaded(function () {
                LayoutAndFilter.layout();
                $(window).resize();
            });
        }

        ///////////////////////////////////////////
        // Initialize infinite scroll
        ///////////////////////////////////////////
        doInfinite($('#loops-wrapper'), '.post');

        var $products = $('.woocommerce.archive').find('#content').find('ul.products');
        if ($body.hasClass('post-type-archive-product') && $products.length > 0) {
            doInfinite($products, '.product');
        }

        $body.attr('onunload', '');

    }); // End window load

    /**
     * Called when user navigates away of the current view.
     * Publicly accessible through themifyScript.onBrowseAway
     */
    themifyScript.onBrowseAway = function (e) {
        if (load) {
            if ('' === navigateTo) {
                $body.addClass('hidden-view').removeClass('ready-view');
            } else {
                $body.addClass('hidden-view');
            }
        }
    };

    if ('1' === themifyScript.pageLoaderEffect) {
        window.addEventListener('beforeunload', themifyScript.onBrowseAway);
    }

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            $body.addClass('ready-view').removeClass('hidden-view');
        }
    });

    /* disable split scrolling when lightbox is active */
    if ('function' === typeof $.fn.multiscroll) {
        $(document).on('mfpOpen', function () {
            $.fn.multiscroll.setMouseWheelScrolling(false);
            $.fn.multiscroll.setKeyboardScrolling(false);
        })
                .on('mfpClose', function () {
                    $.fn.multiscroll.setMouseWheelScrolling(true);
                    $.fn.multiscroll.setKeyboardScrolling(true);
                });
    }

})(jQuery);
