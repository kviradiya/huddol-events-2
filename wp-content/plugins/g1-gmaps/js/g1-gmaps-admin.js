(function ($, context) {
	var $map;       // map dom wrapper
	var map;        // g1Map object

	$(document).ready(function () {
		$map = $('#g1gmap-preview .g1gmap');
		map = $map.data('g1Map');

		// page
		hidePageButtons();
		handlePagePublishAction();

		// map
		handleChangeMapCenterAction();
		listenOnMapChangeEvent();

		// makers
		listenOnMarkerChangeEvent();
		listenOnMarkerDragEvent();
		listenOnMarkerToggleEvent();

		handleAddMarkerAction();
		handleSaveMarkerAction();
		handleSaveAllMarkersAction();
		handleRemoveMarkerAction();
		handleLocateMarkerAction();

		// fields
		handleRangeInput();
		handleColorPickerInput();
		handleGeoCodingField();
		handleMediaUploadField();
	});

	function hidePageButtons() {
		$('#save-post').hide();
		$('#post-preview').hide();
	}

	function handlePagePublishAction() {
		$('#publish').click(function (e) {
			var $unsavedMarkers = $('.g1gmap-marker .g1gmap-save-marker.g1gmap-needs-update');

			if ($unsavedMarkers.length > 0) {
				if (!confirm(g1_gmap_i18n.unsaved_markers_confirm_msg)) {
					e.preventDefault();

					$(this).removeClass('button-primary-disabled');
					$('#publishing-action .spinner').hide();
				}
			}
		});
	}

	function handleChangeMapCenterAction() {
		// set map lat/long based on current position
		$('#g1gmap-preview .g1gmap-change-map-center').click(function (event) {
			event.preventDefault();

			var $lat = $('#g1-gmaps-map-center-point-metabox input[name=map_lat]');
			var $long = $('#g1-gmaps-map-center-point-metabox input[name=map_long]');

			$lat.val(map.getLatitude());
			$long.val(map.getLongitude());
		});

		// move map to current lat/long
		$('.g1gmap-move-map-to-center').click(function (event) {
			event.preventDefault();

			var lat = $('#g1-gmaps-map-center-point-metabox input[name=map_lat]').val();
			var long = $('#g1-gmaps-map-center-point-metabox input[name=map_long]').val();

			if (!lat || !long) {
				return;
			}

			map.moveToPosition(lat, long);
		});
	}

	function listenOnMapChangeEvent() {
		$('#g1-gmaps-map-size-metabox').live('keyup change g1Change', function (event) {
			var $target = $(event.target);
			var $wrapper = $(this);

			var width = $wrapper.find('[name=map_width]').val();
			var height = $wrapper.find('[name=map_height]').val();
			var full = $wrapper.find('[name=map_full_width]').is(':checked');

			// remove leading zeros
			width = width.replace(/^0+/, '');
			height = height.replace(/^0+/, '');

			if (!height || (!width && !full)) {
				return;
			}

			height += 'px';

			if (full) {
				width = '100%';
			} else {
				width += 'px';
			}

			$map.animate({
				'width' : width,
				'height': height
			}, 500);
		});

		$('#g1-gmaps-map-center-point-metabox').live('keyup change g1Change', function (event) {
			var $target = $(event.target);
			var $wrapper = $(this);

			// lat/long changed
			if ($target.is('[name=map_lat]') || $target.is('[name=map_long]')) {
				var lat = $wrapper.find('[name=map_lat]').val();
				var long = $wrapper.find('[name=map_long]').val();

				if (!lat || !long) {
					return;
				}

				map.moveToPosition(lat, long);
			}

			// zoom changed
			if ($target.is('.handle, .progress, .slider, [name=map_zoom]')) {
				var zoom = $wrapper.find('input[name=map_zoom]').val();

				if (zoom) {
					map.setZoom(parseInt(zoom, 10));
				}
			}
		});

		$('#g1-gmaps-map-appearance-ui-metabox, #g1-gmaps-map-appearance-colors-metabox').live('keyup change g1Change', function (event) {
			var $target = $(event.target);
			var $wrapper = $(this);

			// color parts
			if ($target.is('.handle, .progress, .slider, .g1-color-part, .g1-switch-colors')) {
				var useCustomColors = $wrapper.find('[name=map_custom_colors] option:selected').val();

				if (useCustomColors === 'none') {
					map.resetStyles();
				} else {
					var $colorHue = $wrapper.find('[name=map_color_hue]');
					var $colorSaturation = $wrapper.find('[name=map_color_saturation]');
					var $colorLightness = $wrapper.find('[name=map_color_lightness]');
					var $colorGamma = $wrapper.find('[name=map_color_gamma]');

					setTimeout(function () {
						var hue = $colorHue.val(); // 0-360 degrees
						var saturation = $colorSaturation.val();
						var lightness = $colorLightness.val();
						var gamma = $colorGamma.val();

						var hueHex = context.g1_gmaps_hsl_to_rgb(hue, 100, 0);

						map.setHSLColor(hueHex, saturation, lightness, gamma);
					}, 100);
				}
			}

			// invert lightness
			if ($target.is('[name=map_invert_lightness]')) {
				if ($target.is(':checked')) {
					map.invertLightness(true);
				} else {
					map.invertLightness(false);
				}
			}

			// map type
			if ($target.is('[name=map_type]')) {
				map.setType($target.find('option:selected').val());
			}

			// map type control
			if ($target.is('[name=map_type_control]')) {
				map.setTypeControl($target.find('option:selected').val());
			}

			// zoom control
			if ($target.is('[name=map_zoom_control]')) {
				map.setZoomControl($target.find('option:selected').val());
			}

			// pan control
			if ($target.is('[name=map_pan_control]')) {
				map.setPanControl($target.find('option:selected').val());
			}

			// street view control
			if ($target.is('[name=map_street_view_control]')) {
				map.setStreetViewControl($target.find('option:selected').val());
			}

			// scale control
			if ($target.is('[name=map_scale_control]')) {
				map.setScaleControl($target.find('option:selected').val());
			}

			// overview control
			if ($target.is('[name=map_overview_control]')) {
				map.setOverviewControl($target.find('option:selected').val());
			}

			// scroll wheel to zoom
			if ($target.is('[name=map_scroll_wheel_to_zoom]')) {
				map.setScrollWheelZoom($target.find('option:selected').val());
			}

			// double click to zoom
			if ($target.is('[name=map_double_click_to_zoom]')) {
				map.setDoubleClickZoom($target.find('option:selected').val());
			}

			// draggable
			if ($target.is('[name=map_draggable]')) {
				map.setDraggable($target.find('option:selected').val());
			}
		});
	}

	function listenOnMarkerChangeEvent() {
		$('.g1gmap-marker').live('keyup change', function (e) {
			// elements
			var $wrapper = $(this);
			var $marker = $wrapper.find('input[name=marker_id]');
			var markerId = $marker.val();
			var $markerInfo = $('#g1gmap-marker-info-' + markerId);
			var $saveButton = $wrapper.find('.g1gmap-save-marker');

			// show that some changes have taken place
			if (e.type === 'change') {
				$saveButton.addClass('g1gmap-needs-update');
			}

			// data
			var lat = sanitizeCoord($wrapper.find('[name=marker_lat]').val());
			var long = sanitizeCoord($wrapper.find('[name=marker_long]').val());
			var iconPath = $.trim($wrapper.find('[name=marker_icon_path]').val());
			var info = $wrapper.find('[name=marker_info]').val();
			var visibility = $wrapper.find('[name=marker_visibility] option:selected').val();

			// marker instance
			var marker = map.getMarker(markerId);

			// change marker position
			if (lat && long) {
				if (marker) {
					var pos = new google.maps.LatLng(lat, long);

					marker.setPosition(pos);
				}
			}

			// remove marker icon
			if (iconPath === '') {
				marker.setIcon('');
			} else {
				marker.setIcon(iconPath);
			}

			// change marker visibility
			if ($(e.target).is('[name=marker_visibility]')) {
				marker.setVisible(visibility === 'standard');
				$markerInfo.hide();
			}

			// change marker info
			if ($(e.target).is('[name=marker_info]')) {
				$markerInfo.html(info);

				if (!info) {
					$markerInfo.hide();
				}
			}
		});
	}

	function listenOnMarkerDragEvent() {
		map.listenOnMarkersDragEnd(function (marker_id, marker, event) {
			var newPos = event.latLng;
			var lat = newPos.lat();
			var long = newPos.lng();

			var $marker = $('.g1gmap-marker input[value="' + marker_id + '"]');
			var $wrapper = $marker.parents('.g1gmap-marker');
			var $lat = $wrapper.find('input[name=marker_lat]');
			var $long = $wrapper.find('input[name=marker_long]');

			$lat.val(lat);
			$long.val(long);
		});
	}

	function listenOnMarkerToggleEvent () {
		$('.g1gmap-marker-toggle').live('click', function (e) {
			$(this).parents('li').toggleClass('g1gmap-marker-on g1gmap-marker-off');
		});
	}

	function handleAddMarkerAction() {
		$('.g1gmap-add-marker').click(function (event) {
			event.preventDefault();

			var $wrapper = $(this).parents('#g1gmap-new-marker');
			var $map = $wrapper.find('input[name=map_id]');
			var $nonce = $wrapper.find('input[name=update_nonce]');
			var $lat = $wrapper.find('input[name=marker_lat]');
			var $long = $wrapper.find('input[name=marker_long]');
			var $label = $wrapper.find('input[name=marker_label]');

			var lat = sanitizeCoord($lat.val());
			var long = sanitizeCoord($long.val());
			var label = $.trim($label.val());

			if (!lat || !long || !label) {
				alert(g1_gmap_i18n.required_fields_missing_msg);
				return;
			}

			if (!isCorrectCoord(lat) || !isCorrectCoord(long)) {
				alert(g1_gmap_i18n.invalid_coordinates_msg);
				return;
			}

			// ajax call
			var xhr = $.ajax({
				'type': 'POST',
				'url' : ajaxurl,
				'data': {
					'action'   : 'g1gmap_add_marker',
					'security' : $nonce.val(),
					'ajax_data': {
						'map_id': $map.val(),
						'lat'   : lat,
						'long'  : long,
						'label' : label
					}
				}
			});

			// success response
			xhr.done(function (response) {
				if (response === '0' && response === '-1') {
					alert(g1_gmap_i18n.add_marker_error_msg);
					return;
				}

				var $res = $(response);

				$res.insertBefore($wrapper);

				$wrapper.find('.g1gmap-marker-toggle').trigger('click');
				$res.find('.g1gmap-marker-toggle').trigger('click');

				handleGeoCodingField();
				handleMediaUploadField();

				// clear fields
				$lat.val('');
				$long.val('');
				$label.val('');

				// add new marker to map

				var $marker = $res.find('input[name=marker_id]');

				var markerConfig = {
					'id'  : $marker.val(),
					'lat' : lat,
					'long': long,
					'visibility': 'standard'
				};

				map.addMarker(markerConfig);
			});
		});
	}

	function handleSaveMarkerAction() {
		$('.g1gmap-save-marker').live('click', function (event) {
			event.preventDefault();

			// elements
			var $wrapper = $(this).parents('.g1gmap-marker');
			var $saveButton = $wrapper.find('.g1gmap-save-marker');
			var $nonce = $('#g1gmap-new-marker').find('input[name=update_nonce]');

			// data
			var markerId = $wrapper.find('input[name=marker_id]').val();
			var lat = sanitizeCoord($wrapper.find('input[name=marker_lat]').val());
			var long = sanitizeCoord($wrapper.find('input[name=marker_long]').val());
			var label = $.trim($wrapper.find('input[name=marker_label]').val());
			var iconId = $wrapper.find('input[name=marker_icon_id]').val();
			var iconPath = $.trim($wrapper.find('input[name=marker_icon_path]').val());
			var info = $.trim($wrapper.find('textarea[name=marker_info]').val());
			var infoState = $wrapper.find('select[name=marker_info_state]').find('option:selected').val();
			var visibility = $wrapper.find('select[name=marker_visibility]').find('option:selected').val();

			// validation
			if (!lat || !long || !label) {
				alert(g1_gmap_i18n.required_fields_missing_msg);
				return;
			}

			// indicate saving action
			$saveButton.addClass('g1gmap-marker-saving');

			// ajax call
			var xhr = $.ajax({
				'type': 'POST',
				'url' : ajaxurl,
				'data': {
					'action'   : 'g1gmap_save_marker',
					'security' : $nonce.val(),
					'ajax_data': {
						'id'        : markerId,
						'lat'       : lat,
						'long'      : long,
						'label'     : label,
						'icon_id'   : iconId,
						'icon_path' : iconPath,
						'info'      : info,
						'info_state': infoState,
						'visibility': visibility
					}
				}
			});

			// success response
			xhr.done(function (response) {
				$wrapper.find('.g1gmap-save-marker')
					.removeClass('g1gmap-needs-update')
					.removeClass('g1gmap-marker-saving');

				if (response === '0' && response === '-1') {
					alert(g1_gmap_i18n.save_marker_error_msg);
					return;
				}
			});
		});
	}

	function handleSaveAllMarkersAction() {
		$('.g1gmap-save-all-markers').on('click', function (event) {
			event.preventDefault();

			$('.g1gmap-marker .g1gmap-save-marker').trigger('click');
		});
	}

	function handleRemoveMarkerAction() {
		$('.g1gmap-remove-marker').live('click', function (event) {
			event.preventDefault();

			if (!confirm(g1_gmap_i18n.remove_marker_confirm_msg)) {
				return;
			}

			var $wrapper = $(this).parents('.g1gmap-marker');
			var $marker = $wrapper.find('input[name=marker_id]');
			var $nonce = $('#g1gmap-new-marker').find('input[name=update_nonce]');
			var markerId = $marker.val();

			// ajax call
			var xhr = $.ajax({
				'type': 'POST',
				'url' : ajaxurl,
				'data': {
					'action'   : 'g1gmap_remove_marker',
					'security' : $nonce.val(),
					'ajax_data': {
						'id': markerId
					}
				}
			});

			// success response
			xhr.done(function (response) {
				if (response === '0' && response === '-1') {
					alert(g1_gmap_i18n.remove_marker_error_msg);
					return;
				}

				// remove edit form
				$wrapper.remove();

				// remove info window
				$('#g1gmap-marker-info-' + markerId).remove();

				// remove marker
				map.removeMarker(markerId);
			});
		});
	}

	function handleLocateMarkerAction() {
		$('.g1gmap-locate-marker').live('click', function (event) {
			event.preventDefault();

			var locateId = $(this).data('g1-locate-for');

			var $lat = $('[data-g1-lat-for="' + locateId + '"]');
			var $long = $('[data-g1-long-for="' + locateId + '"]');

			var lat = sanitizeCoord($lat.val());
			var long = sanitizeCoord($long.val());

			if (lat && long) {
				map.moveToPosition(lat, long);
			}
		});
	}

	function handleRangeInput() {
		$('input[type="range"]').rangeinput({
			progress: true
		});
	}

	function handleColorPickerInput() {
		var options = {
			// you can declare a default color here,
			// or in the data-default-color attribute on the input
			// defaultColor: false,
			// a callback to fire whenever the color changes to a valid color
			change: function (event, ui) {
				// we need to use custom event because native 'change' event blocks picker
				$(this).trigger('g1Change');
			},
			// a callback to fire when the input is emptied or an invalid color
			clear : function () {
				// we need to use custom event because native 'change' event blocks picker
				$(this).trigger('g1Change');
			}
			// hide the color picker controls on load
			// hide: true
			// show a group of common colors beneath the square
			// or, supply an array of colors to customize further
			// palettes: true
		};

		$('.g1-colorpicker-field').wpColorPicker(options);
	}

	function handleMediaUploadField() {
		$('.g1gmap-media-upload-field:not(.g1-events-bound)').each(function () {
			var $field = $(this);
			var $clearButton = $('.g1gmap-clear-button', $field);
			var $value = $('input.g1gmap-media-upload-input', $field);
			var $imagePath = $('.g1gmap-media-upload-image-path', $field);
			var $preview = $('.g1gmap-media-upload-preview', $field);

			$value.val() ? $clearButton.show() : $clearButton.hide();

			$field.addClass('g1gmap-events-bound');

			$('.g1gmap-clear-button', $field).click(function () {
				$value.val('');
				$imagePath.val('');
				$clearButton.hide();
				$preview.empty();
				$(this).trigger('change');

				return false;
			});
		});

		$('.g1gmap-media-upload-button').live('click', function () {
			var $field_wrapper = $(this).parents('.g1gmap-media-upload-field');
			var $input = $field_wrapper.find('.g1gmap-media-upload-input');
			var $imagePath = $field_wrapper.find('.g1gmap-media-upload-image-path');
			var $preview = $field_wrapper.find('.g1gmap-media-upload-preview');
			var $clearButton = $field_wrapper.find('.g1gmap-clear-button');

			var frame = wp.media.frames.file_frame = wp.media(
				{
					title   : 'Select media',
					button  : {
						text: 'Save'
					},
					multiple: false
				}
			);

			frame.on('open', function () {
				var id = $input.val();

				if (id) {
					var selection = frame.state().get('selection');
					var attachment = wp.media.attachment(id);

					if (attachment) {
						attachment.fetch();
					}

					if (selection) {
						selection.add(attachment ? [ attachment ] : []);
					}
				}
			});

			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();

				var path = '';
				var $img = $('<img>');

				if (typeof attachment != 'undefined') {
					if (typeof attachment.sizes !== 'undefined' && typeof attachment.sizes.full !== 'undefined') {
						$img.attr('src', attachment.sizes.full.url);
						path = attachment.sizes.full.url;
					} else {
						$img.attr('src', attachment.url);
						path = attachment.url;
					}
				}

				$input.val(attachment.id);
				$imagePath.val(path);
				$preview.html($img);
				$clearButton.show();

				// in some cases, wp media upload doesn't close properly
				// so we close it manually
				$('.media-modal-close:first').trigger('click');
				$field_wrapper.trigger('change');
			});

			frame.open();

			return false;
		});
	}

	function handleGeoCodingField() {
		$('.g1gmap-geocoding-field:not(.g1gmap-geocoding-loaded)').each(function () {
			var $field = $(this);
			var geocodingId = $field.data('g1-geocode-for');

			var $lat = $('[data-g1-lat-for=' + geocodingId + ']');
			var $long = $('[data-g1-long-for=' + geocodingId + ']');
			var $label = $('[data-g1-label-for=' + geocodingId + ']');

			var $locationAddress = $field;
			var $encodeButton = $field.parent().find('.button');

			$field.addClass('g1gmap-geocoding-loaded');

			var successCallback = function (results) {
				$('#g1gmap-geocoding-results').remove();

				if (results.length > 0) {
					var $resultsBox = $('<div id="g1gmap-geocoding-results">');
					var $suggestionList = $('<ul>');

					for (var i in results) {
						var result = results[i];
						var location = result.geometry.location;

						var $suggestion = $('<li>');
						var $link = $('<a href="#">' + result.formatted_address + '</a>');

						(function (latLng, address) {
							$link.click(function (event) {
								event.preventDefault();

								$lat.val(latLng.lat());
								$long.val(latLng.lng());
								$label.val(address.formatted_address);

								$lat.trigger('change');

								$resultsBox.remove();
							});
						})(location, result);

						$suggestion.append($link);
						$suggestionList.append($suggestion);
					}

					var $cancelButton = $('<a href="#">Cancel</a>');

					$cancelButton.on('click', function (e) {
						e.preventDefault();

						$resultsBox.remove();
					});

					$resultsBox.append($suggestionList);
					$resultsBox.append($cancelButton);

					$locationAddress.after($resultsBox);
				}
			};

			var failureCallback = function (status) {
				$('.g1gmap-geocoding-results').remove();
				alert(g1_gmap_i18n.geocoding_failure_msg + status);
			};

			$encodeButton.on('click', function () {
				map.findAddress($locationAddress.val(), successCallback, failureCallback);
			});

			$locationAddress.on('keypress', function (e) {
				var keycode = (e.keyCode ? e.keyCode : e.which);

				if (keycode == '13') {
					e.preventDefault();
					$encodeButton.trigger('click');
				}
			});
		});
	}

	function isCorrectCoord(value) {
		var regex = /\-?[0-9]+\.?[0-9]*/;

		return value.match(regex);
	}

	function sanitizeCoord(value) {
		value = $.trim(value);
		value = value.replace(',', '.');

		return value;
	}
})(jQuery, window);