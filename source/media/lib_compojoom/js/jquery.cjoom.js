/*!
 * Compojoom 1.0.0 (https://compojoom.com)
 * Copyright 2014 Compojoom.com
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
(function ($) {
	$(document).ready(function () {
		$('*[rel=tooltip]').tooltip()
		$('.hasTooltip').tooltip({"html": true,"container": "body"});
	})
})(jQuery);
