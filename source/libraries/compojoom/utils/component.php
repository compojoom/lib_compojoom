<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       17.09.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JLoader::import('joomla.application.component.helper');

/**
 * Class CompojoomUtilsComponent
 *
 * @since  4.0
 */
class CompojoomUtilsComponent
{
	private static $instance = null;

	/**
	 * The constructor
	 *
	 * @param   string  $component  - the component name
	 */
	protected function __construct($component)
	{
		$this->config = JComponentHelper::getParams($component);
	}

	/**
	 * Gets a single instance of the class
	 *
	 * @param   string  $component  - the component name
	 *
	 * @return CompojoomUtilsComponent|null
	 */
	public static function getInstance($component)
	{
		if (!self::$instance)
		{
			self::$instance = new CompojoomUtilsComponent($component);
		}

		return self::$instance;
	}

	/**
	 * Gets a component value taking into account the joomla version we run on
	 *
	 * @param   string  $value    - string
	 * @param   mixed   $default  - default value in case no value is set
	 *
	 * @return mixed
	 */
	public function get($value, $default)
	{
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$returnValue = $this->config->get($value, $default);
		}
		else
		{
			$returnValue = $this->config->getValue($value, $default);
		}

		return $returnValue;
	}
}
