<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       19.11.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomAvatars
 *
 * @since  4.0.21
 */
class CompojoomAvatars
{
	/**
	 * @var    array  CompojoomAvatars instances container.
	 * @since  11.1
	 */
	protected static $instances = array();

	/**
	 * Get's an instance of the correct class that should handle the avatars
	 *
	 * @param   string  $type  - the avatar system to use
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public static function getInstance($type)
	{
		// If we already have a database connector instance for these options then just use that.
		if (empty(self::$instances[$type]))
		{
			// Derive the class name from the type.
			$class = 'CompojoomAvatars' . ucfirst($type);

			// If the class doesn't exist, let's look for it and register it.
			if (!class_exists($class))
			{
				// Derive the file path for the type class.
				$path = dirname(__FILE__) . '/avatars/' . strtolower($type) . '.php';

				// If the file exists register the class with our class loader.
				if (file_exists($path))
				{
					JLoader::register($class, $path);
				}
				// If it doesn't exist we are at an impasse so throw an exception.
				else
				{
					throw new Exception('Specified avatar is not supported: ' . $type);
				}
			}

			// If the class still doesn't exist we have nothing left to do but throw an exception.  We did our best.
			if (!class_exists($class))
			{
				throw new Exception('Specified avatar is not supported: ' . $type);
			}

			// Set the new connector to the global instances based on signature.
			self::$instances[$type] = new $class;
		}

		return self::$instances[$type];
	}
}
