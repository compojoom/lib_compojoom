<?php
/**
 * @package    Lib_Compojoom
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       13.05.15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Extends JLanguage in order to access Strings
 *
 * @since   5.2.0
 */
class CompojoomJLanguage extends JLanguage
{
	/**
	 * The instance holder
	 *
	 * @var     object
	 */
	private static $instance = null;

	/**
	 * Get the instance of CompojoomJLanguage
	 *
	 * @param   string  $lang   The language
	 * @param   bool    $debug  Debug
	 *
	 * @return  CompojoomJLanguage
	 */
	public static function getInstance($lang, $debug = false)
	{
		if (!self::$instance)
		{
			self::$instance = new CompojoomJLanguage($lang, $debug);
		}

		return self::$instance;
	}

	/**
	 * Get the strings of translations in JLanguage
	 *
	 * @return  array
	 */
	public function getStrings()
	{
		return $this->strings;
	}
}
