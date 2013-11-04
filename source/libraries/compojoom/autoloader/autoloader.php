<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       04.10.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomAutoloader
 * 
 * @since  3.0
 */
class CompojoomAutoloader
{
	/**
	 * An instance of this autoloader
	 *
	 * @var   CompojoomAutoloader
	 */
	public static $autoloader = null;

	/**
	 * The path to the CMandrill root directory
	 *
	 * @var   string
	 */
	public static $cmandrillPath = null;

	/**
	 * Initialise this autoloader
	 *
	 * @return  CompojoomAutoloader
	 */
	public static function init()
	{
		if (self::$autoloader == null)
		{
			self::$autoloader = new self;
		}

		return self::$autoloader;
	}

	/**
	 * Public constructor. Registers the autoloader with PHP.
	 */
	public function __construct()
	{
		self::$cmandrillPath = realpath(__DIR__ . '/../');

		spl_autoload_register(array($this,'autoload_compojoom_library'));
	}

	/**
	 * The actual autoloader
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_compojoom_library($class_name)
	{
		// Make sure the class has a CMandrill prefix
		if (substr(strtolower($class_name), 0, 9) != 'compojoom')
		{
			return;
		}

		// Remove the prefix
		$class = substr($class_name, 9);

		// Change from camel cased (e.g. ViewHtml) into a lowercase array (e.g. 'view','html')
		$class = preg_replace('/(\s)+/', '_', $class);
		$class = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class));
		$class = explode('_', $class);

		// First try finding in structured directory format (preferred)
		$path = self::$cmandrillPath . '/' . implode('/', $class) . '.php';

		if (@file_exists($path))
		{
			include_once $path;
		}

		// Then try the duplicate last name structured directory format (not recommended)
		if (!class_exists($class_name, false))
		{
			reset($class);
			$lastPart = end($class);
			$path = self::$cmandrillPath . '/' . implode('/', $class) . '/' . $lastPart . '.php';

			if (@file_exists($path))
			{
				include_once $path;
			}
		}
	}
}
