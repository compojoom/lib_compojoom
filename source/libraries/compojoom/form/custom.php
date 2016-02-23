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

	private static $optionsStore;

	/**
	 * Returns a xml string to load in a JForm object
	 *
	 * @param   array   $fields     - the custom fields
	 * @param   string  $component  - the component name (e.g. com_hotspots) that we generate the form for
	 *
	 * @return string
	 */
	public static function generateFormXML($fields, $component = null)
	{
		$xmlFields = array();

		if ($component)
		{
			JLoader::discover(
				'CompojoomFormCustomfields',
				JPATH_ADMINISTRATOR . '/components/' . $component . '/models/fields/customfields'
			);
			JLoader::discover(
				'CompojoomFormCustomfields',
				JPATH_SITE . '/templates/' . CompojoomTemplateHelper::getFrontendTemplate() . '/html/' . $component . '/fields/customfields'
			);
		}

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
	 * @param   object  $field      - the field config object
	 * @param   string  $value      - string
	 * @param   string  $component  - if we want to load overriden customfields provide the component where we should check
	 *
	 * @return mixed
	 */
	public static function render($field, $value, $component = null)
	{
		$class = 'CompojoomFormCustomfields' . ucfirst($field->type);

		if ($component)
		{
			JLoader::discover(
				'CompojoomFormCustomfields',
				JPATH_ADMINISTRATOR . '/components/' . $component . '/models/fields/customfields'
			);
			JLoader::discover(
				'CompojoomFormCustomfields',
				JPATH_SITE . '/templates/' . CompojoomTemplateHelper::getFrontendTemplate() . '/html/' . $component . '/fields/customfields'
			);
		}

		if (!isset(self::$customFields[$class]))
		{
			self::$customFields[$class] = new $class;
		}

		return self::$customFields[$class]->render($field, $value);
	}

	/**
	 * Creates an options array
	 *
	 * @param   string  $options  - string with options
	 *
	 * @return array
	 */
	public static function getOptionsArray($options)
	{
		$hash = md5(serialize($options));

		if (!isset(self::$optionsStore[$hash]))
		{
			$options = explode("\n", $options);
			$array = array();

			foreach ($options as $value)
			{
				$option = explode('=', $value, 2);

				$array[trim($option[0])] = trim($option[1]);
			}

			self::$optionsStore[$hash] = $array;
		}

		return self::$optionsStore[$hash];
	}
}
