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
	 * Function to decide if we need to include our own boostrap.css file or not
	 *
	 * @return void
	 */
	public static function bootstrap()
	{
		if (JVERSION < 3.0)
		{
			JHTML::_('stylesheet', 'media/com_cmc/css/bootstrap.css');
		}
	}

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
		if ($js)
		{
			if (JVERSION < '3')
			{
				// Load jQuery first
				if ($debug)
				{
					JHTML::_('script', 'media/lib_compojoom/js/jquery.js');
				}
				else
				{
					JHTML::_('script', 'media/lib_compojoom/js/jquery-1.11.0.min.js');
				}

				// Load jQuery in no conflict mode
				JHTML::_('script', 'media/lib_compojoom/js/jquery.noconflict.js');

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
				JHTML::_('script', 'media/lib_compojoom/js/radiobtns.js');
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

				// Chart API
				// JHTML::_('stylesheet', 'media/lib_compojoom/third/morris/morris.css');

				// Dialogs with effects / CSS transitions and animations
				JHTML::_('stylesheet', 'media/lib_compojoom/third/nifty-modal/css/component.css');

				// Sortable / SASS by HubSpot
				// JHTML::_('stylesheet', 'media/lib_compojoom/third/sortable/sortable-theme-bootstrap.css');

				// Checkboxes / Radiobuttons project by fronteed - see https://github.com/fronteed/iCheck
				// JHTML::_('stylesheet', 'media/lib_compojoom/third/icheck/skins/minimal/grey.css');

				// Bootstrap select - is already implemented in joomla 3.2 (but with bootstrap 2.3.2)
				// see http://silviomoreto.github.io/bootstrap-select/
				// JHTML::_('stylesheet', 'media/lib_compojoom/third/select/bootstrap-select.min.css');

				// Bootstrap Editor
				// JHTML::_('stylesheet', 'media/lib_compojoom/third/summernote/summernote.css');

				// Popups (more..) but nice ones -> responsive, http://dimsemenov.com/plugins/magnific-popup/
				JHTML::_('stylesheet', 'media/lib_compojoom/third/magnific-popup/magnific-popup.css');

				// Datepicker for Bootstrap by Stefan Petre, http://www.eyecon.ro/bootstrap-datepicker/
				JHTML::_('stylesheet', 'media/lib_compojoom/third/datepicker/css/datepicker.css');

				// Load 3rd Party scripts for Laceng
				JHTML::_('script', 'media/lib_compojoom/third/slimscroll/jquery.slimscroll.min.js');
				// JHTML::_('script', 'http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js');
				// JHTML::_('script', 'media/lib_compojoom/third/morris/morris.js');
				// JHTML::_('script', 'media/lib_compojoom/third/nifty-modal/js/classie.js');
				// JHTML::_('script', 'media/lib_compojoom/third/nifty-modal/js/modalEffects.js');
				// JHTML::_('script', 'media/lib_compojoom/third/sortable/sortable.min.js');
				// JHTML::_('script', 'media/lib_compojoom/third/select/bootstrap-select.min.js');

				// JHTML::_('script', 'media/lib_compojoom/third/summernote/summernote.js');

				JHTML::_('script', 'media/lib_compojoom/third/magnific-popup/jquery.magnific-popup.min.js');
				JHTML::_('script', 'media/lib_compojoom/third/input/bootstrap.file-input.js');
				JHTML::_('script', 'media/lib_compojoom/third/datepicker/js/bootstrap-datepicker.js');
				// JHTML::_('script', 'media/lib_compojoom/third/icheck/icheck.min.js');
				// JHTML::_('script', 'media/lib_compojoom/third/wizard/jquery.snippet.min.js');
				// JHML::_('script', 'media/lib_compojoom/third/wizard/jquery.easyWizard.js');

				JHTML::_('script', 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js');
				JHTML::_('script', 'https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js');
			}

			// Always load the strapper css
			JHTML::_('stylesheet', 'media/lib_compojoom/css/bootstrap-3.1.1.css');

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
}
