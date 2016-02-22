<?php
/**
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       2016-02-21
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomTestingJasmine
 *
 * @since  4.0.53
 */
class CompojoomTestingJasmine
{
	/**
	 * This function will load the jasmine library files
	 *
	 * @param   string  $key  - Namespace for the queue
	 *
	 * @return  void
	 */
	public static function loadJasmine($key = 'libcompojoom')
	{
		CompojoomHtml::addCSSToQueue($key, 'media/lib_compojoom/third/jasmine/jasmine.css');
		CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/third/jasmine/jasmine.js');
		CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/third/jasmine/jasmine-html.js');
		CompojoomHtml::addScriptsToQueue($key, 'media/lib_compojoom/third/jasmine/boot.js');
	}
}
