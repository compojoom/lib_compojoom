/**
 * onImpression : A  jQuery plugin to trigger a callback function when an element is displayed on screen
 * Created by Jason Ramsey - check out http://www.jaseowns.com/solutions/onimpression for examples and use options
 *
 * Licensed under the MIT license.
 *
 * Inspired by Luís Almeida's Unveil https://github.com/luis-almeida
 **/
; (function ($) {
	$.fn.onImpression = function (options) {

		var settings = $.extend({
			offset: 0,
			callback: null,
			attribute: "",
			alwayscallback: false,
			scrollable: ""
		}, options);

		var $window = $(window),
			$scrollable = $(settings.scrollable),
			onImpressionElements = this,
			loaded;

		this.one("onImpression", function () {
			if (typeof settings.callback === "function") settings.callback.call(this, this.getAttribute(settings.attribute));
		});

		this.on("alwaysOnImpression", function () {
			if (typeof settings.callback === "function") settings.callback.call(this, this.getAttribute(settings.attribute));
		});

		function onImpression() {
			var inview = onImpressionElements.filter(function () {
				var $e = $(this);
				if ($e.is(":hidden")) return;
				var wt = $window.scrollTop(),
					wb = wt + $window.height(),
					et = $e.offset().top,
					eb = et + $e.height();
				var inScrollable = false;
				if ($scrollable.length) {
					var scrollTop = $scrollable.scrollTop(),
						scrollBottom = scrollTop + $scrollable.height();
					inScrollable = (eb >= scrollTop - settings.offset && et <= scrollBottom + settings.offset);
				}
				return (eb >= wt - settings.offset && et <= wb + settings.offset) || inScrollable;
			});

			if (settings.alwayscallback) {
				loaded = inview.trigger("alwaysOnImpression");
			}
			else {
				loaded = inview.trigger("onImpression");
				onImpressionElements = onImpressionElements.not(loaded);
			}
		}

		// Only run  code if the callback is available, else there is no point
		if (typeof settings.callback === "function") {
			if ($scrollable.length) {
				$scrollable.on("scroll.onImpression resize.onImpression lookup.onImpression", onImpression);
			} else {
				$window.on("scroll.onImpression resize.onImpression lookup.onImpression", onImpression);
			}
			onImpression();
		}

		return this;
	};

})(window.jQuery);
