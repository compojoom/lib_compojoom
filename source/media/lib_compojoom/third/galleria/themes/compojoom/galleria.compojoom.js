/**
 * Galleria Compojoom Theme 04.03.2015
 * https://compojoom.com
 *
 * Licensed under the GPL license
 * https://raw.github.com/aino/galleria/master/LICENSE
 *
 */

(function($) {

/*global window, jQuery, Galleria */

Galleria.addTheme({
    name: 'compojoom',
    author: 'Daniel Dimitrov daniel@compojoom.com',
    defaults: {
        transition: 'pulse',
        imageCrop: 'landscape',
        carousel: true,
        transitionSpeed: 500,
        _showFullscreen: true,
        _showPopout: true,
        _showProgress: true,
        _showTooltip: true,

	    _toggleInfo: true
    },
    init: function(options) {


	    Galleria.requires(1.4, 'This version of Classic theme requires Galleria 1.4 or later');


	    // add some elements
	    this.addElement('info-link','info-close', 'bar','fullscreen fa fa-arrows-alt','progress');
	    this.append({
		    'stage' : 'progress',
		    'container': 'bar',
		    'info' : ['info-link','info-close'],
		    'bar'   : ["thumbnails-container", 'fullscreen fa fa-arrows-alt']
	    });

	    var gallery = this,
	    thumbs = this.$('thumbnails-container'),
		    fs_link = this.$('fullscreen fa fa-arrows-alt'),
		    transition = options.transition,
		    bar = this.$('bar'),
		    OPEN = false,
		    FULLSCREEN = false;

	    fs_link.click(function() {
		    if (FULLSCREEN) {
			    gallery.exitFullscreen();
		    } else {
			    gallery.enterFullscreen();
		    }
	    });

	    toggleThumbs = function(e) {
		    if (OPEN && CONTINUE) {
			    gallery.play();
		    } else {
			    CONTINUE = PLAYING;
			    gallery.pause();
		    }
		    Galleria.utils.animate( thumbs, { top: OPEN ? gallery.getStageHeight()+30 : 0 } , {
			    easing:'galleria',
			    duration:400,
			    complete: function() {
				    gallery.defineTooltip('thumblink', OPEN ? lang.show_thumbnails : lang.hide_thumbnails);
				    thumb_link[OPEN ? 'removeClass' : 'addClass']('open');
				    OPEN = !OPEN;
			    }
		    });
	    };

	    // cache some stuff
	    var info = this.$('info-link,info-close,info-text'),
		    touch = Galleria.TOUCH;

	    // show loader & counter with opacity
	    this.$('loader,counter').show().css('opacity', 0.4);

	    // some stuff for non-touch browsers
	    if (! touch ) {
		    this.addIdleState( this.get('image-nav-left'), { left:-50 });
		    this.addIdleState( this.get('image-nav-right'), { right:-50 });
		    this.addIdleState( this.get('counter'), { opacity:0 });
	    }

	    // toggle info
	    if ( options._toggleInfo === true ) {
		    info.bind( 'click:fast', function() {
			    info.toggle();
		    });
	    } else {
		    info.show();
		    this.$('info-link, info-close').hide();
	    }

	    // bind some stuff
	    this.bind('thumbnail', function(e) {

		    if (! touch ) {
			    // fade thumbnails
			    $(e.thumbTarget).css('opacity', 0.6).parent().hover(function() {
				    $(this).not('.active').children().stop().fadeTo(100, 1);
			    }, function() {
				    $(this).not('.active').children().stop().fadeTo(400, 0.6);
			    });

			    if ( e.index === this.getIndex() ) {
				    $(e.thumbTarget).css('opacity',1);
			    }
		    } else {
			    $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.6).bind('click:fast', function() {
				    $(this).css( 'opacity', 1 ).parent().siblings().children().css('opacity', 0.6);
			    });
		    }
	    });

	    var activate = function(e) {
		    $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);
	    };

	    this.bind('loadstart', function(e) {
		    if (!e.cached) {
			    this.$('loader').show().fadeTo(200, 0.4);
		    }
		    window.setTimeout(function() {
			    activate(e);
		    }, touch ? 300 : 0);
		    this.$('info').toggle( this.hasInfo() );
	    });

	    this.bind('loadfinish', function(e) {
		    this.$('loader').fadeOut(200);
	    });


	    this.bind( 'fullscreen_enter', function(e) {
		    FULLSCREEN = true;
		    gallery.setOptions('transition', false);
		    fs_link.addClass('open');
		    bar.css('bottom',0);
		    this.defineTooltip('fullscreen', 'afullscreen enter');
	    });

	    this.bind( 'fullscreen_exit', function(e) {
		    FULLSCREEN = false;
		    Galleria.utils.clearTimer('bar');
		    gallery.setOptions('transition',transition);

		    fs_link.removeClass('open');
		    bar.css('bottom',0);

		    this.defineTooltip('fullscreen', 'fullscreen leave');
	    });
    }
});

}(jQuery));
