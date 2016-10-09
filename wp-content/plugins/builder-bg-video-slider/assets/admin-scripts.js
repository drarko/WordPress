(function($, window, document, undefined) {
	"use strict";

	var SliderVideosFront = {
		init: function() {
			var self = SliderVideosFront;
			self.bindEvents();
		},

		bindEvents: function() {
			var self = SliderVideosFront;
			$(document).on('tfb.live_styling.after_create', self.bindLiveStyling);
		},

		removeSliderVideos: function($component) {
			var componentType = ThemifyBuilderCommon.getComponentType($component),
				$sliderVideos = null;

			if (componentType === 'row') {
				$sliderVideos = $component.find('.tb_slider_videos');
			}

			$sliderVideos.siblings('.tb_slider_videos_helper').remove();
			$sliderVideos.remove();
		},

		bindLiveStyling: function(e, liveStylingInstance) {
			var self, getSliderVideos, getOptions, insertToHTML, shouldContinue, liveStylingCallback;
			self = SliderVideosFront;

			getSliderVideos = function(options) {
				var $elmt = liveStylingInstance.$liveStyledElmt;

				return $.post(
					themifyBuilder.ajaxurl,
					{
						nonce : themifyBuilder.tfb_load_nonce,
						action: 'tfb_slider_videos_live_styling',
						tfb_slider_videos_data: $.extend({},
							options,
							{
								'row_order': ThemifyBuilderCommon.getComponentOrder($elmt),
								'builder_id': ThemifyBuilderCommon.getComponentBuilderId($elmt)
							}
						)
					}
				);
			};

			getOptions = function() {
				var videos = [],
					i = 0;

				jQuery('[name="background_slider_videos_video"]').each(function(){
					videos[i] = [];
					videos[i][0] = jQuery(this).val();
					videos[i][1] = jQuery(jQuery('[name="background_slider_videos_image"]').get(i)).val();
					i++;
				});

				videos = JSON.stringify(videos);

				return {
					type: $('[name="background_type"]:checked').val(),
					autoplay: $('#background_slider_videos_autoplay').val(),
					progressbar: $('#background_slider_videos_progressbar').val(),
					controls: $('#background_slider_videos_controls').val(),
					mute: $('#background_slider_videos_mute').val(),
					videos: videos
				};
			};

			insertToHTML = function(sliderVideos) {
				var $elmt = liveStylingInstance.$liveStyledElmt,
					type = ThemifyBuilderCommon.getComponentType($elmt);

				if (type === 'row') {
					$elmt.find('.row_inner').prepend(sliderVideos);
				}
			};

			shouldContinue = function() {
				var options = getOptions();

				if (options.type !== 'slidervideos') {
					return false;
				}

				if (options.videos.length === 0) {
					return false;
				}

				return true;
			},

			liveStylingCallback = function() {
				var $backgroundType = $(this).filter('[name="background_type"]');

				// BG: IMAGE
				if ($backgroundType.length == 1 && $backgroundType.val() == 'slidervideos') {
					// Remove BG: SLIDER
					ThemifyLiveStyling.removeBgSlider(liveStylingInstance.$liveStyledElmt);
					// Remove BG: VIDEO
					ThemifyLiveStyling.removeBgVideo(liveStylingInstance.$liveStyledElmt);
					// Remove BG: IMAGE
					liveStylingInstance.setLiveStyle({'background-image':'none'},['']);
				}

				// Remove BG: SLIDER VIDEOS
				self.removeSliderVideos(liveStylingInstance.$liveStyledElmt);

				if (!shouldContinue()) {
					return;
				}

				getSliderVideos(getOptions()).done(function(sliderVideos) {
					if (sliderVideos.length < 10) {
						return;
					}

					var $sliderVideos = $(sliderVideos);
					insertToHTML($sliderVideos);

					$('body').trigger('builder_slider_videos_loaded');
				});
			};

			liveStylingInstance.$context.on(
				'change',
				'[name="background_type"],' +
					'#background_slider_videos_autoplay,' +
					'#background_slider_videos_progressbar,' +
					'#background_slider_videos_controls,' +
					'#background_slider_videos_mute,' +
					'[name="background_slider_videos_video"],' +
					'[name="background_slider_videos_image"]',
				liveStylingCallback
			);

			liveStylingInstance.$context.on(
				'click',
				'#background_slider_videos .themify_builder_delete_row,' +
					'#background_slider_videos .themify_builder_duplicate_row',
				liveStylingCallback
			);

			liveStylingInstance.$context.on(
				'sortupdate',
				'#background_slider_videos',
				liveStylingCallback
			);
		}
	};

	SliderVideosFront.init();
}(jQuery, window, document));
