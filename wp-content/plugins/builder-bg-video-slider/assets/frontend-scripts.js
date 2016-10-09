var SliderVideos,
	SV_VisibilityHelper;

(function($, window, document, undefined) {
	"use strict";

	SV_VisibilityHelper = {
		browserPrefixes: ['moz', 'ms', 'o', 'webkit'],

		getHiddenPropertyName: function(prefix) {
			return (prefix ? prefix + 'Hidden' : 'hidden');
		},

		getVisibilityEvent: function(prefix) {
			return (prefix ? prefix : '') + 'visibilitychange';
		},

		getBrowserPrefix: function() {
			var self = SV_VisibilityHelper;

			for (var i = 0; i < self.browserPrefixes.length; i++) {
				if (self.getHiddenPropertyName(self.browserPrefixes[i]) in document) {
					return self.browserPrefixes[i];
				}
			}

			return null;
		}
	};

	SliderVideos = {
		hiddenPropertyName: SV_VisibilityHelper.getHiddenPropertyName(SV_VisibilityHelper.getBrowserPrefix()),
		visibilityEventName: SV_VisibilityHelper.getVisibilityEvent(SV_VisibilityHelper.getBrowserPrefix()),
		isVisible: false,
		videos: {},

		init: function() {
			var self = SliderVideos;
			self.bindEvents();
		},

		bindEvents: function() {
			var self = SliderVideos;

			if (window.loaded) {
				self.windowLoad();
			} else {
				$(window).load(self.windowLoad);
			}

			$(document).on('builder_load_on_ajax', 'body', self.windowLoad);
			$(document).on('builder_slider_videos_loaded', 'body', self.windowLoad);

			$(document).on(self.visibilityEventName, self.handleVisibilityChange);
			$(document).focus(function(){self.handleVisibilityChange(true)});
			$(document).blur(function(){self.handleVisibilityChange(false)});
			$(window).focus(function(){self.handleVisibilityChange(true)});
			$(window).blur(function(){self.handleVisibilityChange(false)});
		},

		windowLoad: function() {
			var self = SliderVideos;
			self.moveSlider();
			self.makeSlider();
			self.sliderEvents();
			self.handleVisibilityChange();
		},

		windowFocus: function() {
			var self = SliderVideos;

			if (self.isVisible) return;
			self.isVisible = true;

			$('body').addClass('tb_slider_videos_window_focus');

			$('.tb_slider_videos.tb_slider_videos_waiting_to_run_next_slide').each(function() {
				var $this = $(this);
				$this.removeClass('tb_slider_videos_waiting_to_run_next_slide');
				$this.data('swiper').slideNext();
			});
		},

		windowBlur: function() {
			var self = SliderVideos;

			if (!self.isVisible) return;
			self.isVisible = false;

			$('body').removeClass('tb_slider_videos_window_focus');
		},

		handleVisibilityChange: function(forcedFlag) {
			var self = SliderVideos;

			if (typeof forcedFlag === "boolean") {
				if (forcedFlag) {
					return self.windowFocus();
				}

				return self.windowBlur();
			}

			if (document[self.hiddenPropertyName]) {
				return self.windowBlur();
			}

			return self.windowFocus();
		},

		moveSlider: function() {
			$('.tb_slider_videos:not(.tb_slider_videos_moved)').each(function() {
				var row = $(this).closest('.module_row'),
					helpers = $(this).siblings('.tb_slider_videos_helper').detach();
				$(this).detach().addClass('tb_slider_videos_moved').appendTo(row);
				helpers.addClass('tb_slider_videos_helper_moved').appendTo(row);
			});
		},

		makeSlider: function() {
			var self = SliderVideos,
			$slider = $('.tb_slider_videos');

			if ($slider.length > 0) {
				Themify.LoadAsync(
					themify_vars.url+'/js/jquery.imagesloaded.min.js',
					function() {
						Themify.LoadAsync(
							themify_vars.url+'/js/video.js',
							function() {
								Themify.LoadAsync(
									themify_vars.url+'/js/bigvideo.js',
									function() {
										Themify.LoadAsync(
											tb_slider_videos_vars.url + 'assets/swiper.jquery.min.js',
											function() { self.makeSliderCallBack($slider) }.bind(this),
											null,
											null,
											function() { return ('undefined' !== typeof $.fn.swiper) }
										);
									},
									null,
									null,
									function() { return ('undefined' !== typeof $.BigVideo) }
								);
							},
							null,
							null,
							function() { return ('undefined' !== typeof $.fn.imagesLoaded) }
						);
					}
				);
			}
		},

		makeSliderCallBack: function ($slider) {
			$.each($slider, function (i, elm) {
				var self = SliderVideos,
					$this = $(elm),
					sliderIndex = $this.attr('data-index'),
					$nav = $this.siblings('.tb_slider_videos_nav');

				if (typeof self.videos[sliderIndex] == 'undefined') {
					self.videos[sliderIndex] = {};
				}

				$this.swiper({
					effect: 'fade',
					fade: {crossFade:true},
					speed: 500,
					setWrapperSize: true,
					loop: true,
					pagination: $this.siblings('.tb_slider_videos_pagination'),
					paginationClickable: true,
					prevButton: $nav.children('.tb_slider_videos_nav_arrow_prev'),
					nextButton: $nav.children('.tb_slider_videos_nav_arrow_next'),
					
					onInit: function(swiper) {
						$(swiper.container).closest('.themify_builder_row').css({'position': 'relative', 'z-index': 0, 'background': 'none'});
					},
					
					onSlideChangeStart: function(swiper) {
						if ($('body').hasClass('builder-is-mobile')) {
							return;
						}

						var $swiperContainer = $(swiper.container),
							sliderIndex = $swiperContainer.attr('data-index'),
							progressbar = $swiperContainer.attr('data-progressbar'),
							controls = $swiperContainer.attr('data-controls'),
							mute = $swiperContainer.attr('data-mute'),
							autoplay = $swiperContainer.attr('data-autoplay'),
							$video,
							slideIndex;

						// CURRENT SLIDE

						$video = $($swiperContainer.find('.tb_slider_videos_slide').get(swiper.previousIndex)),
						slideIndex = $video.attr('data-index');

						// progressbar

						if (progressbar == 'show') { // show/hide
							$swiperContainer.siblings('.tb_slider_videos_progressbar').find('.vjs-progress-control-'+ slideIndex).fadeOut(500);
						}
						else {
							$swiperContainer.siblings('.tb_slider_videos_progressbar').hide();
						}

						// controls

						var $nav = $swiperContainer.siblings('.tb_slider_videos_nav'),
							$play = $nav.children('.tb_slider_videos_nav_control_play'),
							$pause = $nav.children('.tb_slider_videos_nav_control_pause');
							
						if (controls == 'hide') { // show/hide
							$nav.hide();
							//$swiperContainer.siblings('.tb_slider_videos_pagination').hide();
						}

						// pause video

						if (typeof self.videos[sliderIndex][slideIndex] != 'undefined' && self.videos[sliderIndex][slideIndex] != null) {
							self.videos[sliderIndex][slideIndex].getPlayer().pause();
						}

						// NEXT SLIDE

						$video = $($swiperContainer.find('.tb_slider_videos_slide').get(swiper.activeIndex)),
						slideIndex = $video.attr('data-index');

						if (typeof self.videos[sliderIndex][slideIndex] == 'undefined' || self.videos[sliderIndex][slideIndex] == null) {
							self.videos[sliderIndex][slideIndex] = new $.BigVideo({
								//useFlashForFirefox: true,
								doLoop: false,
								ambient: mute == 'yes', // yes/no
								container: $video,
								poster: $video.attr('data-image')
							});

							self.videos[sliderIndex][slideIndex].init();
							var $player = self.videos[sliderIndex][slideIndex].getPlayer();

							// ready

							$player.on('loadedmetadata', function() {
								var $this = $(this.el());

								if ($this.length > 0 && $this.find('video').length > 0) {
									$this.find('video').css('opacity', '1');
								}
							});

							// controls

							$player.on(['waiting', 'seeking'], function() {
								$play.addClass('loading');
								$pause.addClass('loading');
							});

							$player.on(['canplay', 'canplaythrough', 'playing', 'ended', 'seeked'], function() {
								$play.removeClass('loading');
								$pause.removeClass('loading');
							});

							$player.on('play', function() {
								$play.hide();
								$pause.show();
							});

							$player.on('pause', function() {
								$play.show();
								$pause.hide();
							});

							// progressbar

							if (progressbar == 'show') { // show/hide
								$player.on('loadedmetadata', function() {
									var $this = $(this.el()),
										$progressbar = $this.find('.vjs-progress-control').detach(),
										slideIndex = $this.closest('.tb_slider_videos_slide').attr('data-index'),
										$progressbarContainer = $this.closest('.tb_slider_videos').siblings('.tb_slider_videos_progressbar');
									
									$progressbar.addClass('vjs-progress-control-'+ slideIndex).hide();
									$progressbarContainer.append($progressbar);
									$progressbar.fadeIn(500);
								});
							}

							// clean code

							$player.on('loadedmetadata', function() {
								$(this.el()).find('.vjs-poster, .vjs-text-track-display, .vjs-loading-spinner, .vjs-big-play-button, .vjs-control-bar, .vjs-error-display').remove();
							});

							// jump next video

							$player.on('ended', function() {
								if ($(this.el()).closest('.tb_slider_videos_slide.swiper-slide-active').length == 1) {
									if ($('body').hasClass('tb_slider_videos_window_focus')) {
										swiper.slideNext();
									}
									else {
										$swiperContainer.addClass('tb_slider_videos_waiting_to_run_next_slide');
									}
								}
							});

							// autoplay

							if (autoplay == 'yes') { // yes/no
								self.videos[sliderIndex][slideIndex].show($video.attr('data-video'));
							}
							else {
								$video.attr('data-video-waiting', $video.attr('data-video'));
							}
						}
					},
					
					onSlideChangeEnd: function(swiper) {
						if ($('body').hasClass('builder-is-mobile')) {
							return;
						}

						var $swiperContainer = $(swiper.container),
							sliderIndex = $swiperContainer.attr('data-index'),
							$video,
							slideIndex;

						// PREVIOUS SLIDE

						$video = $($swiperContainer.find('.tb_slider_videos_slide').get(swiper.previousIndex)),
						slideIndex = $video.attr('data-index');

						// remove video and helpers

						if (typeof self.videos[sliderIndex][slideIndex] != 'undefined' && self.videos[sliderIndex][slideIndex] != null) {
							//self.videos[sliderIndex][slideIndex].getPlayer().dispose();
							self.videos[sliderIndex][slideIndex].getPlayer().pause();
							setTimeout(function() {
								//self.videos[sliderIndex][slideIndex].getPlayer().dispose();
								delete self.videos[sliderIndex][slideIndex].getPlayer();
								self.videos[sliderIndex][slideIndex] = null;
							}, 0);
						}

						$swiperContainer.find('.tb_slider_videos_slide:not(.swiper-slide-active)').html('');
						$swiperContainer.siblings('.tb_slider_videos_progressbar').find('.vjs-progress-control-'+ slideIndex).remove();
					}
				});
			});
		},

		sliderEvents: function() {
			var self = SliderVideos;
			$(document).off('click', '.tb_slider_videos_nav_control_play:not(.loading)', self.sliderEventsPlay);
			$(document).on('click', '.tb_slider_videos_nav_control_play:not(.loading)', self.sliderEventsPlay);
			$(document).off('click', '.tb_slider_videos_nav_control_pause:not(.loading)', self.sliderEventsPause);
			$(document).on('click', '.tb_slider_videos_nav_control_pause:not(.loading)', self.sliderEventsPause);
		},

		sliderEventsPlay: function() {
			var self = SliderVideos,
				$this = $(this),
				$videos = $this.closest('.tb_slider_videos_nav').siblings('.tb_slider_videos'),
				$video = $videos.find('.tb_slider_videos_slide.swiper-slide-active'),
				sliderIndex = $videos.attr('data-index'),
				slideIndex = $video.attr('data-index');

			if (typeof self.videos[sliderIndex][slideIndex] != 'undefined' && self.videos[sliderIndex][slideIndex] != null && $video.attr('data-video-waiting')) {
				self.videos[sliderIndex][slideIndex].show($video.attr('data-video-waiting'));
				$video.removeAttr('data-video-waiting');
			}
			else if (typeof self.videos[sliderIndex][slideIndex] != 'undefined' && self.videos[sliderIndex][slideIndex] != null) {
				self.videos[sliderIndex][slideIndex].getPlayer().play();
			}

			$this.hide();
			$this.siblings('.tb_slider_videos_nav_control_pause').show();
		},

		sliderEventsPause: function() {
			var self = SliderVideos,
				$this = $(this),
				$videos = $this.closest('.tb_slider_videos_nav').siblings('.tb_slider_videos'),
				$video = $videos.find('.tb_slider_videos_slide.swiper-slide-active'),
				sliderIndex = $videos.attr('data-index'),
				slideIndex = $video.attr('data-index');

			if (typeof self.videos[sliderIndex][slideIndex] != 'undefined' && self.videos[sliderIndex][slideIndex] != null) {
				self.videos[sliderIndex][slideIndex].getPlayer().pause();
			}

			$this.hide();
			$this.siblings('.tb_slider_videos_nav_control_play').show();
		}
	};

	SliderVideos.init();
}(jQuery, window, document));
