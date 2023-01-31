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
	 * Loads the backend template JS and css
	 *
	 * @param   bool  $js          - Load JS
	 * @param   bool  $ctemplate   - Load boostrap backend template
	 * @param   bool  $thirdparty  - Load third party js for template
	 * @param   bool  $minifyJs    - Minify js
	 * @param   bool  $minifyCss   - Minify css
	 *
	 * @return void
	 */
	public static function lanceng($js = true, $ctemplate = true, $thirdparty = true, $key = 'lanceng')
	{
		self::bootstrap(true);

		if ($js)
		{
			// Load compojoom js
			CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/js/jquery.cjoom.js');

			// Load third party scripts and css? (Required for Template)
			if ($thirdparty)
			{
				// Font Awesome
				CompojoomHtml::addCSSToQueue($key, 'media/lib_compojoom/third/font-awesome/css/font-awesome.min.css');

				// Popups (more..) but nice ones -> responsive, http://dimsemenov.com/plugins/magnific-popup/
				CompojoomHtml::addCSSToQueue($key, 'media/lib_compojoom/third/magnific-popup/magnific-popup.css');

				// Datepicker for Bootstrap by Stefan Petre, http://www.eyecon.ro/bootstrap-datepicker/
				CompojoomHtml::addCSSToQueue($key, 'media/lib_compojoom/third/datepicker/css/datepicker.css');

				// Load 3rd Party scripts for Laceng
				CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/third/slimscroll/jquery.slimscroll.min.js');
				CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/third/magnific-popup/jquery.magnific-popup.min.js');
				CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/third/input/bootstrap.file-input.js');
				CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/third/datepicker/js/bootstrap-datepicker.js');
			}

			// Load backend template
			if ($ctemplate)
			{
				// CompojoomHtml::addCSSToQueue($key, 'media/lib_compojoom/css/animate.css');
				CompojoomHtml::addCSSToQueue($key, 'media/lib_compojoom/css/compojoom-backend-style.css');
				CompojoomHtml::addCSSToQueue($key, 'media/lib_compojoom/css/compojoom-backend-style-responsive.css');
				CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/js/lanceng.js');

				if(!version_compare(JVERSION, '4', 'lt')) {
					CompojoomHtml::addCSSToQueue($key, 'media/lib_compojoom/css/compojoom-backend-style-j4.css');
				}
			}
		}

		if ($key == "lanceng")
		{
			// Minify css & js (All items should be in que right now)
			CompojoomHtml::external(
				CompojoomHtml::getScriptQueue('lanceng'),
				CompojoomHtml::getCSSQueue('lanceng'),
				'media/lib_compojoom/cache', true,
				false
			);
		}
	}

	/**
	 * Load bootstrap and overrides
	 *
	 * @param   bool  $bootstrap  - Load the bootstrap library (not only overrides)
	 *
	 * @return  void
	 */
	public static function bootstrap($bootstrap = true, $loadBootstrapCss = false)
	{
		if ($bootstrap)
		{
			// Load native (for js)
			JHtml::_('bootstrap.framework', true);
			self::jquery();
		}

		if($loadBootstrapCss) {
			// Don't use queue here, as wee already have that file minimized
			JHtml::stylesheet('media/lib_compojoom/css/compojoom-bootstrap-3.3.6.min.css');
		}

		JHtml::stylesheet('media/lib_compojoom/css/compojoom.min.css');
	}

	/**
	 * Load jQuery ui (minified)
	 *
	 * @since 6.0.10
	 */
	public static function jqueryui()
	{
		JHtml::_('script', 'media/lib_compojoom/js/jquery-ui.min.js');

		JHtml::stylesheet('media/lib_compojoom/css/jquery-ui.min.css');
		JHtml::stylesheet('media/lib_compojoom/css/jquery-ui.structure.min.css');
		JHtml::stylesheet('media/lib_compojoom/css/jquery-ui.theme.min.css');
	}

	/**
	 * Load qjuery
	 *
	 * @return  void
	 */
	public static function jquery()
	{
		JHtml::_('jquery.framework', false);
	}

	/**
	 * Loads qTip2
	 *
	 * @param   string  $namespace  The namespace vor CompojoomHTML script add
	 *
	 * @return  void
	 */
	public static function qTip2($namespace)
	{
		// Load CSS
		CompojoomHtml::addCSSToQueue($namespace, 'media/lib_compojoom/css/jquery.qtip-2.2.1.min.css');

		// Load JS
		JHTML::_('script', 'media/lib_compojoom/js/jquery.qtip.min.js');
	}
}
