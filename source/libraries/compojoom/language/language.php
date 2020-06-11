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

use \Joomla\CMS\Factory;
use \Joomla\CMS\Plugin\PluginHelper;
use \Joomla\CMS\Language\Text;

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
	 * @param   boolean  $liblang    - should the library language also be loaded?
	 *
	 * @return void
	 * @throws Exception
	 */
	public static function load($extension, $path, $liblang = true)
	{
		$jlang = Factory::getLanguage();
		$jlang->load($extension, $path, 'en-GB', true);
		$jlang->load($extension, $path, $jlang->getDefault(), true);
		$jlang->load($extension, $path, null, true);

		// Load the library language files (default true)
		if ($liblang)
		{
			$jlang->load('lib_compojoom', JPATH_ADMINISTRATOR);
			$jlang->load('lib_compojoom', JPATH_SITE);
			$jlang->load('lib_cforms', JPATH_ADMINISTRATOR);
			$jlang->load('lib_cforms', JPATH_SITE);
		}

		// Make it possible to override the loaded language with a plugin
		PluginHelper::importPlugin('system');

		Factory::getApplication()->triggerEvent('onAfterCompojoomLoadLanguage', array($extension, $path));
	}

	/**
	 * Loads a language file to Joomla.JText_()
	 *
	 * @param   string  $extension  The extension language file
	 * @param   string  $path       The path to the file (JPATH_COMPONENT)
	 *
	 * @return  void
	 * @throws Exception
	 */
	public static function loadJavaScriptLanguage($extension, $path)
	{
		require_once __DIR__ . '/CompojoomJLanguage.php';

		$clang = CompojoomJLanguage::getInstance(JFactory::getApplication()->get('config.language'));

		$strings = $clang->getStrings();

		$lang = Factory::getLanguage();

		$clang->load($extension, $path, null, false, false);
		$clang->load($extension, $path, 'en-GB', false, false);
		$clang->load($extension, $path, $lang->getDefault(), false, false);

		$jsLang = array_diff_assoc($clang->getStrings(), $strings);

		$lang->load($extension, $path, null, false, false);
		$lang->load($extension, $path, 'en-GB', false, false);
		$lang->load($extension, $path, $lang->getDefault(), false, false);

		// Add them to the header
		foreach (array_keys($jsLang) as $key)
		{
			Text::script($key);
		}
	}
}
