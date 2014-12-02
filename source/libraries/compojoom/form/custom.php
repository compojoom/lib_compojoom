<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       01.12.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomFormCustom
 *
 * @since  4.0
 */
class CompojoomFormCustom
{
	private static $customFields;

	/**
	 * Returns a xml string to load in a JForm object
	 *
	 * @param   array  $fields  - the custom fields
	 *
	 * @return string
	 */
	public static function generateFormXML($fields)
	{
		$xmlFields = array();

		foreach ($fields as $field)
		{
			$class = 'CompojoomFormCustomfields' . ucfirst($field->type);

			if (!isset(self::$customFields[$class]))
			{
				self::$customFields[$class] = new $class;
			}

			$xmlFields[] = self::$customFields[$class]->xml($field);
		}

		return '<form><fields name="customfields">' . implode('', $xmlFields) . '</fields></form>';
	}

	/**
	 * Returns the translated label for a value
	 *
	 * @param   object  $field  - the field config object
	 * @param   string  $value  - string
	 *
	 * @return mixed
	 */
	public static function render($field, $value)
	{
		$class = 'CompojoomFormCustomfields' . ucfirst($field->type);

		if (!isset(self::$customFields[$class]))
		{
			self::$customFields[$class] = new $class;
		}

		return self::$customFields[$class]->render($field, $value);
	}
}
