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
	 * @param   bool  $js         - Load JS
	 * @param   bool  $ctemplate  - Load boostrap backend template
	 * @param   bool  $debug      - Debug mode? e.g. load non minimized versions?
	 *
	 * @return void
	 */
	public static function bootstrap31($js = true, $ctemplate = true, $debug = false)
	{
		// Always load the strapper css
		JHTML::_('stylesheet', 'media/lib_compojoom/css/bootstrap-3.1.1.css');

		if ($js)
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

			// Load jQuery compat
			JHTML::_('script', 'media/lib_compojoom/js/jquery.noconflict.js');

			// Load radio buttons JS
			JHTML::_('script', 'media/lib_compojoom/js/radiobtns.js');

			// Load bootstrap
			if ($debug)
			{
				JHTML::_('script', 'media/lib_compojoom/js/bootstrap-3.1.1.js');
			}
			else
			{
				JHTML::_('script', 'media/lib_compojoom/js/bootstrap-3.1.1.min.js');
			}

			// Load compojoom js
			JHTML::_('script', 'media/lib_compojoom/js/jquery.cjoom.js');

			// Load backend template
			if ($ctemplate)
			{
				JHTML::_('script', 'media/lib_compojoom/js/laceng.js');

				JHTML::_('stylesheet', 'media/lib_compojoom/css/style.css');
				JHTML::_('stylesheet', 'media/lib_compojoom/css/style-responsive.css');
				JHTML::_('stylesheet', 'media/lib_compojoom/css/animate.css');
			}
		}
	}
}
