/* global jQuery */
/* global window */

(function ($, context) {
	"use strict";

	function getWindowWidth () {
		if (typeof window.innerWidth !== 'undefined') {
			return window.innerWidth;
		}

		return jQuery(window).width();
	}

	var windowWidth = getWindowWidth();
	var isPhone =  windowWidth <= 767;
	var isTablet =  windowWidth > 767 && windowWidth <= 1024;
	var isDesktop =  !isPhone && !isTablet;
	var isAndroid =  navigator.userAgent.indexOf('Android') !== -1;
	var isIOS = navigator.userAgent.match(/(iPad|iPhone|iPod)/g) !== null;
	var isIE = navigator.userAgent.match(/msie/ig) !== null;

	var scrollChangeObservers = [];

	function scrollChange (obj) {
		for (var i = 0; i < scrollChangeObservers.length; i++) {
			scrollChangeObservers[i].notifyOnScrollChange(obj);
		}
	}

	// class
	var g1Map = function ($mapWrapper, options) {
		var that = {}; // defines public scope
		var $map;
		var map = null;
		var mapStyle = {
			'stylers': []
		};
		var markersConfig = [];
		var markers = {};
		var callbacks = {};
		var geocoder = null;

		var MARKER_DRAG_END = 'marker_drag_end';

		var globalOptions = {
			'zoom': 10
		};

		var $scrollChangeObserver;
		var lastScrollMoveValue;

		function init() {
			$map = $mapWrapper.find('.g1gmap-inner');

			initOptions();
			prepareMarkers();
			loadMap();

			return that;
		}

		// private scope
		// =============

		function initOptions() {
			options = $.extend(true, {}, globalOptions, options || {}); // override globals with options from slider initialization call

			var config = $map.data('g1gmap-config');

			options = $.extend(true, options, config);
		}

		function prepareMarkers() {
			$mapWrapper.find('.g1gmap-marker-config').each(function () {
				var markerConfig = $(this).data('g1gmap-marker-config');

				markerConfig.info = $(this).html();

				markersConfig.push(markerConfig);

				$(this).remove();
			});
		}

		function loadMap() {
			var lat = options.lat;
			var long = options.long;

			if (!lat || !long) {
				return;
			}

			var mapOptions = {
				center                   : new google.maps.LatLng(lat, long),
				zoom                     : parseInt(that.getZoom(), 10),
				mapTypeId                : that.getMapTypeId(),
				draggable                : (options.draggable === 'standard'),
				scrollwheel              : (options.scroll_wheel_to_zoom === 'standard'),
				disableDoubleClickZoom   : (options.double_click_to_zoom === 'none'),
				mapTypeControl           : (options.type_control !== 'none'),
				mapTypeControlOptions    : {
					style   : (options.type_control === 'horizontal' ? google.maps.MapTypeControlStyle.HORIZONTAL_BAR : google.maps.MapTypeControlStyle.DROPDOWN_MENU),
					position: google.maps.ControlPosition.TOP_RIGHT
				},
				panControl               : (options.pan_control === 'standard'),
				zoomControl              : (options.zoom_control !== 'none'),
				zoomControlOptions       : {
					style: (options.zoom_control === 'large' ? google.maps.ZoomControlStyle.LARGE : google.maps.ZoomControlStyle.SMALL)
				},
				scaleControl             : (options.scale_control === 'standard'),
				streetViewControl        : (options.street_view_control === 'standard'),
				overviewMapControl       : (options.overview_control !== 'none'),
				overviewMapControlOptions: {
					opened: (options.overview_control === 'opened')
				}
			};

			map = new google.maps.Map($map.get(0), mapOptions);

			google.maps.event.addListener(map, 'tilesloaded', function () {
				$map.addClass('g1gmap-map-loaded');
			});

			applyInitialMapStyle();
			loadMarkers();
			enableParallax();
		}

		function enableParallax () {
			var enabled = options.parallax !== 'none';
			var allowedForDevice = !isPhone;
			var scriptLoaded = typeof skrollr !== 'undefined';
			var smoothScrollEnabled = !isIOS;

			var useParallax = enabled && allowedForDevice && scriptLoaded && smoothScrollEnabled;

			if (!useParallax) {
				return;
			}

			// animation listener
			$scrollChangeObserver = $mapWrapper.find('.g1gmap-observer');

			var mapHeight = parseInt($map.css('height'), 10);

			// you can redefine these values via css
			var offset = parseInt($scrollChangeObserver.css('margin-top'), 10);
			var scrollHeight = parseInt($scrollChangeObserver.css('height'), 10);
			// ---

			var halfScrollHeight = parseInt(scrollHeight / 2, 10);

			lastScrollMoveValue = -halfScrollHeight;

			$scrollChangeObserver.attr('data-'+ offset +'-bottom-top', 'top: -'+ halfScrollHeight +'px;');
			$scrollChangeObserver.attr('data-'+ offset +'-center-center', 'top: 0px;');
			$scrollChangeObserver.attr('data-'+ offset +'-top-bottom', 'top: '+ halfScrollHeight +'px;');
			$scrollChangeObserver.attr('data-anchor-target', '#' + $map.attr('id'));

			$('body').append($scrollChangeObserver);

			map.panBy(0, -halfScrollHeight);

			// refresh skrollr to apply it on new elements
			var s = skrollr.get();

			if (!s) {
				s = skrollr.init({
					forceHeight: false,
					easing: 'linear'
				});
			}

			s.refresh();

			// add listener
			scrollChangeObservers.push(that);

			// listen on skrollr change
			s.on('render', function (obj) {
				scrollChange(obj);
			});
		}

		function notifyOnScrollChange (obj) {
			var moveValue = parseInt($scrollChangeObserver.css('top'), 10);

			if (isNaN(moveValue)) {
				return;
			}

			var offset = lastScrollMoveValue - moveValue;

			lastScrollMoveValue = moveValue;

			if (offset !== 0) {
				map.panBy(0, -offset);
			}
		}

		function applyInitialMapStyle() {
			if (options.custom_colors === 'none') {
				return;
			}

			that.invertLightness(options.invert_lightness === 'standard');

			var hueHex = hsl_to_rgb(options.color_hue, 100, 50);

			that.setHSLColor(hueHex, options.color_saturation, options.color_lightness, options.color_gamma);
		}

		function loadMarkers() {
			for (var i in markersConfig) {
				addMarker(markersConfig[i]);
			}
		}

		function addMarker(config) {
			var markerConfig = {
				position : new google.maps.LatLng(config.lat, config.long),
				map      : map,
				animation: google.maps.Animation.DROP,
				draggable: true
			};

			if (config.icon_path) {
				markerConfig.icon = config.icon_path;
			}

			var marker = new google.maps.Marker(markerConfig);

			marker.setVisible(config.visibility === 'standard');

			markers[config.id] = marker;

			addMarkerInfoWindow(marker, config);

			handleMarkerEvents(marker, config.id);
		}

		function addMarkerInfoWindow(marker, config) {
			var info = config.info;

			var $boxText =
				$('<div id="g1gmap-marker-info-' + config.id + '" class="g1gmap-marker-bubble-inner">').html(info);

			var myOptions = {
				boxClass              : 'g1gmap-marker-bubble',
				content               : $boxText.get(0),
				disableAutoPan        : true,
				maxWidth              : 0,
				alignBottom           : false,
				pixelOffset           : new google.maps.Size(0, 0),
				zIndex                : null,
				closeBoxURL           : "",
				infoBoxClearance      : new google.maps.Size(1, 1),
				isHidden              : false,
				pane                  : "floatPane",
				enableEventPropagation: false
			};

			var handleInfoBoxClose = function () {
				myOptions.boxClass = 'g1gmap-marker-bubble';
				ib.setOptions(myOptions);
			};

			var handleInfoBoxOpen = function () {
				info = $('#g1gmap-marker-info-' + config.id).text();

				if (!info) {
					return;
				}

				$(myOptions.content).toggle();
			};

			InfoBox.prototype.getCloseClickHandler_ = function () {
				return handleInfoBoxClose;
			};

			var ib = new InfoBox(myOptions);
			ib.open(map, marker);

			if (!info || config.info_state === 'none') {
				$(myOptions.content).hide();
			}

			// listeners
			google.maps.event.addListener(marker, 'click', function () {
				handleInfoBoxOpen();
			});
		}

		function handleMarkerEvents(marker, id) {
			google.maps.event.addListener(marker, 'dragend', function (event) {
				if (typeof callbacks[MARKER_DRAG_END] === 'function') {
					callbacks[MARKER_DRAG_END](id, marker, event);
				}
			});
		}

		function getGeocoder() {
			if (!geocoder) {
				geocoder = new google.maps.Geocoder();
			}

			return geocoder;
		}

		function deleteStyler(styler) {
			var i = findStylerIndex(styler);

			if (i != -1) {
				mapStyle.stylers.splice(i, 1);
			}
		}

		function findStylerIndex(styler) {
			if (!styler) {
				return -1;
			}

			for (var i = 0; i < mapStyle.stylers.length; i++) {
				for (var p in mapStyle.stylers[i]) {
					if (p === styler) {
						return i;
					}
				}
			}

			return -1;
		}

		function applyMapStyle() {
			// we handle now only one map style
			map.setOptions({ 'styles': [
				mapStyle
			]});
		}

		function getMapTypeIdByName(name) {
			switch (name) {
				case 'satellite':
					return google.maps.MapTypeId.SATELLITE;

				case 'hybrid':
					return google.maps.MapTypeId.HYBRID;

				case 'terrain':
					return google.maps.MapTypeId.TERRAIN;

				case 'roadmap':
					return google.maps.MapTypeId.ROADMAP;
			}

			return null;
		}

		// public API
		// ==========

		that.getMap = function () {
			return map;
		};

		that.getMapCenterPosition = function () {
			return map.getCenter();
		};

		that.getMarkers = function () {
			return markers;
		};

		that.getMarker = function (id) {
			if (typeof markers[id] !== 'undefined') {
				return markers[id];
			}

			return null;
		};

		that.addMarker = function (config) {
			addMarker(config)
		};

		that.removeMarker = function (id) {
			var marker = that.getMarker(id);

			if (marker) {
				marker.setMap(null);
			}
		};

		that.getLatitude = function () {
			return map.getCenter().lat();
		};

		that.getLongitude = function () {
			return map.getCenter().lng();
		};

		that.getZoom = function () {
			return options.zoom;
		};

		that.setZoom = function (value) {
			options.zoom = value;

			map.setZoom(value);
		};

		that.getMapTypeId = function () {
			return getMapTypeIdByName(options.type);
		};

		that.getColor = function () {
			return options.color;
		};

		that.listenOnMarkersDragEnd = function (callback) {
			callbacks[MARKER_DRAG_END] = callback;
		};

		that.moveToMarker = function (marker_id) {
			var marker = that.getMarker(marker_id);

			if (marker) {
				map.panTo(marker.getPosition());
			}
		};

		that.moveToPosition = function (lat, long) {
			var pos = new google.maps.LatLng(lat, long);

			map.panTo(pos);
		};

		that.findAddress = function (address, successCallback, failureCallback) {
			if (address.length === 0) {
				return;
			}

			getGeocoder().geocode({ 'address': address}, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					successCallback(results);
				} else {
					failureCallback(status);
				}
			});
		};

		that.invertLightness = function (state) {
			deleteStyler('invert_lightness');

			mapStyle.stylers.push({ 'invert_lightness': state });

			applyMapStyle();
		};

		that.resetStyles = function () {
			deleteStyler('hue');
			deleteStyler('saturation');
			deleteStyler('lightness');
			deleteStyler('gamma');

			applyMapStyle();
		};

		that.setHSLColor = function (h, s, l, g) {
			if (h && h.indexOf('#') === -1) {
				h = '#' + h;
			}

			s = parseInt(s, 10);
			l = parseInt(l, 10);
			g = parseFloat(g);

			deleteStyler('hue');
			deleteStyler('saturation');
			deleteStyler('lightness');
			deleteStyler('gamma');

			if (h) {
				mapStyle.stylers.push({ 'hue': h });
			}

			if (s) {
				mapStyle.stylers.push({ 'saturation': s });
			}

			if (l) {
				mapStyle.stylers.push({ 'lightness': l });
			}

			if (g) {
				mapStyle.stylers.push({ 'gamma': g });
			}

			applyMapStyle();
		};

		that.setType = function (type) {
			var mapTypeId = getMapTypeIdByName(type);

			if (mapTypeId) {
				map.setMapTypeId(mapTypeId);
			}
		};

		that.setTypeControl = function (type) {
			switch (type) {
				case 'horizontal':
					map.set('mapTypeControl', true);
					map.set('mapTypeControlOptions', {
						'style': google.maps.MapTypeControlStyle.HORIZONTAL_BAR
					});
					break;

				case 'dropdown':
					map.set('mapTypeControl', true);
					map.set('mapTypeControlOptions', {
						'style': google.maps.MapTypeControlStyle.DROPDOWN_MENU
					});
					break;

				case 'none':
					map.set('mapTypeControl', false);
					break;
			}
		};

		that.setScaleControl = function (state) {
			map.set('scaleControl', state === 'standard');
		};

		that.setOverviewControl = function (type) {
			switch (type) {
				case 'opened':
					map.set('overviewMapControl', true);
					map.set('overviewMapControlOptions', {
						'opened': true
					});
					break;

				case 'collapsed':
					map.set('overviewMapControl', true);
					map.set('overviewMapControlOptions', {
						'opened': false
					});
					break;

				case 'none':
					map.set('overviewMapControl', false);
					break;
			}
		};

		that.setScrollWheelZoom = function (state) {
			map.set('scrollwheel', state === 'standard');
		};

		that.setDoubleClickZoom = function (state) {
			map.set('disableDoubleClickZoom', state === 'none');
		};

		that.setZoomControl = function (style) {
			switch (style) {
				case 'small':
					map.set('zoomControl', true);
					map.set('zoomControlOptions', {
						'style': google.maps.ZoomControlStyle.SMALL
					});
					break;

				case 'large':
					map.set('zoomControl', true);
					map.set('zoomControlOptions', {
						'style': google.maps.ZoomControlStyle.LARGE
					});
					break;

				case 'none':
					map.set('zoomControl', false);
					break;
			}
		};

		that.setPanControl = function (state) {
			map.set('panControl', state === 'standard');
		};


		that.setStreetViewControl = function (state) {
			map.set('streetViewControl', state === 'standard');
		};

		that.setDraggable = function (state) {
			map.set('draggable', state === 'standard');
		};

		that.notifyOnScrollChange = notifyOnScrollChange;

		return init();
	};

	function hsl_to_rgb( h, s, l ) {
		h = h / 360;
		s = (parseInt(s, 10) + 100) / 200;
		l = (parseInt(	l, 10) + 100) / 200;

		var r = 0;
		var g = 0;
		var b = 0;

		if( s == 0 ) {
			// achromatic
			r = l;
			g = l;
			b = l;
		} else {
			var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
			var p = 2 * l - q;

			r = hue_to_rgb(p, q, h + 1/3);
			g = hue_to_rgb(p, q, h);
			b = hue_to_rgb(p, q, h - 1/3);
		}

		r = parseInt(r * 255, 10).toString(16);
		g = parseInt(g * 255, 10).toString(16);
		b = parseInt(b * 255, 10).toString(16);

		if (r.length === 1) r = '0' + r;
		if (g.length === 1) g = '0' + g;
		if (b.length === 1) b = '0' + b;

		return '#' + r + g+ b;
	}

	function hue_to_rgb( p, q, t ) {
		if ( t < 0) t += 1;

		if ( t > 1) t -= 1;

		if ( t < 1/6 ) return p + (q - p) * 6 * t;
		if ( t < 1/2 ) return q;
		if ( t < 2/3 ) return p + (q - p) * (2/3 - t) * 6;

		return p;

	}

	context.g1_gmaps_hsl_to_rgb = hsl_to_rgb;

	// jQuery plugin definition
	$.fn.g1Map = function (options) {
		return this.each(function () {
			var $this = $(this);

			var instance = $this.data('g1Map');

			// call API method (only if object exists)
			if (typeof options === 'string' && instance) {
				if (typeof instance[options] === 'function') {
					var args = Array.prototype.slice.call(arguments, 1);

					instance[options].apply(instance, args);
				}
			} else {
				// create object if not exist
				if (!instance) {
					instance = g1Map($this, options);
					$this.data('g1Map', instance);
				}
			}
		});
	};

	// load
	$(document).ready(function () {
		$('.g1gmap').g1Map();
	});
})(jQuery, window);