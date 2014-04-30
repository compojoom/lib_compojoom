<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.11.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomLanguage
 *
 * @since  1.0
 */
class CompojoomLanguage
{
	/**
	 * Loads a language during the installation
	 *
	 * @param   string   $extension  - extension name
	 * @param   string   $path       - the path to the lang files
	 * @param   boolean  $liblang   - should the library language also be loaded?
	 *
	 * @return void
	 */
	public static function load($extension, $path, $liblang = true)
	{
		$jlang = JFactory::getLanguage();
		$jlang->load($extension, $path, 'en-GB', true);
		$jlang->load($extension, $path, $jlang->getDefault(), true);
		$jlang->load($extension, $path, null, true);

		// Load the library language files (default true)
		if ($liblang)
		{
			$jlang->load('lib_compojoom', JPATH_ADMINISTRATOR);
			$jlang->load('lib_compojoom', JPATH_SITE);
		}
	}
}
