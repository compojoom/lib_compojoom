/*
 * https://compojoom.com
 * Copyright (c) 2013 - 2014 Yves Hoppe; License: GPL v2 or later
 */

(function ($) {
	$(document).ready(function(){
		$('a.toolbar').button();
		$('a.toolbar').addClass('btn btn-default');
		$('#toolbar-new a.toolbar').addClass('btn-success');
	});
})(jQuery);