<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       10.03.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomHtmlBehavior
 *
 * @since  1.0
 */
class CompojoomHtmlBehavior
{
	/**
	 * Loads our bootstrap 3.1.1 JS and css
	 *
	 * @param   bool  $js          - Load JS
	 * @param   bool  $ctemplate   - Load boostrap backend template
	 * @param   bool  $thirdparty  - Load third party js for template
	 * @param   bool  $debug       - Debug mode? e.g. load non minimized versions?
	 *
	 * @return void
	 */
	public static function bootstrap31($js = true, $ctemplate = true, $thirdparty = true, $debug = false)
	{
		// Always load the strapper css
		JHTML::_('stylesheet', 'media/lib_compojoom/css/bootstrap-3.1.1.css');

		if (JVERSION < '3')
		{
			JHTML::_('stylesheet', 'media/lib_compojoom/css/bootstrap-j25-fixes.css');
		}

		if ($js)
		{
			if (JVERSION < '3')
			{
				self::jquery();

				// Load bootstrap
				if ($debug)
				{
					JHTML::_('script', 'media/lib_compojoom/js/bootstrap-3.1.1.js');
				}
				else
				{
					JHTML::_('script', 'media/lib_compojoom/js/bootstrap-3.1.1.min.js');
				}

				// Load radio buttons JS
				JHTML::_('script', 'media/lib_compojoom/js/jquery.radiobtns.js');
			}
			else
			{
				// Load native
				JHtml::_('jquery.framework',  true);
				JHtml::_('bootstrap.framework', true);
			}

			// Load compojoom js
			JHTML::_('script', 'media/lib_compojoom/js/jquery.cjoom.js');

			// Load third party scripts and css? (Required for Template)
			if ($thirdparty)
			{
				// Load 3rd Party css

				// Font Awesome
				JHTML::_('stylesheet', 'media/lib_compojoom/third/font-awesome/css/font-awesome.min.css');

				// Dialogs with effects / CSS transitions and animations
				JHTML::_('stylesheet', 'media/lib_compojoom/third/nifty-modal/css/component.css');

				// Popups (more..) but nice ones -> responsive, http://dimsemenov.com/plugins/magnific-popup/
				JHTML::_('stylesheet', 'media/lib_compojoom/third/magnific-popup/magnific-popup.css');

				// Datepicker for Bootstrap by Stefan Petre, http://www.eyecon.ro/bootstrap-datepicker/
				JHTML::_('stylesheet', 'media/lib_compojoom/third/datepicker/css/datepicker.css');

				// Load 3rd Party scripts for Laceng
				JHTML::_('script', 'media/lib_compojoom/third/slimscroll/jquery.slimscroll.min.js');

				JHTML::_('script', 'media/lib_compojoom/third/magnific-popup/jquery.magnific-popup.min.js');
				JHTML::_('script', 'media/lib_compojoom/third/input/bootstrap.file-input.js');
				JHTML::_('script', 'media/lib_compojoom/third/datepicker/js/bootstrap-datepicker.js');

				JHTML::_('script', 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js');
				JHTML::_('script', 'https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js');
			}

			// Load backend template
			if ($ctemplate)
			{
				JHTML::_('stylesheet', 'media/lib_compojoom/css/style.css');
				JHTML::_('stylesheet', 'media/lib_compojoom/css/animate.css');
				JHTML::_('stylesheet', 'media/lib_compojoom/css/style-responsive.css');

				if (JVERSION < 3)
				{
					JHTML::_('stylesheet', 'media/lib_compojoom/css/j25style.css');
					JHTML::_('script', 'media/lib_compojoom/js/jquery.radiobtns.js');
					JHTML::_('script', 'media/lib_compojoom/js/jquery.cjoom25.js');
				}

				JHTML::_('script', 'media/lib_compojoom/js/lanceng.js');
			}
		}
	}

	/**
	 * Load our jquery version on 2.5 and the default jquery on j3
	 *
	 * @return void
	 */
	public static function jquery()
	{
		// Load jQuery first
		if (JVERSION < 3)
		{
			JHTML::_('script', 'media/lib_compojoom/js/jquery.js');
			JHTML::_('script', 'media/lib_compojoom/js/jquery.noconflict.js');
		}
		else
		{
			JHtml::_('jquery.framework');
		}
	}

	/**
	 * Loads qTip2
	 *
	 * @return  void
	 */
	public static function qTip2()
	{
		// Load CSS
		JHTML::_('stylesheet', 'media/lib_compojoom/css/jquery.qtip-2.2.1.min.css');

		// Load JS
		JHTML::_('script', 'media/lib_compojoom/js/jquery.qtip.min.js');
	}
}
