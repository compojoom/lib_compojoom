<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       01.12.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Compojoom\Library\Form;
use Compojoom\Library\Template\Helper as CompojoomTemplateHelper;

defined('_JEXEC') or die('Restricted access');

/**
 * Class Custom
 *
 * @since  4.0
 */
class Custom
{
	private static $customFields;

	private static $optionsStore;

	private static $optionsJsonStore;

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

//        die('generateFormXML');
//		if ($component)
//		{
//			JLoader::discover(
//				'CompojoomFormCustomfields',
//				JPATH_ADMINISTRATOR . '/components/' . $component . '/models/fields/customfields'
//			);
//			JLoader::discover(
//				'CompojoomFormCustomfields',
//				JPATH_SITE . '/templates/' . CompojoomTemplateHelper::getFrontendTemplate() . '/html/' . $component . '/fields/customfields'
//			);
//		}

		foreach ($fields as $field)
		{
            $className = 'Compojoom\\Library\\Field\\Customfields\\' . ucfirst($field->type);
            if ($component)
            {
                $className = 'Compojoom\\Component\\' . $component . '\\Administrator\\Field\\Customfields\\' . ucfirst($field->type);

                // Check if an override exists in the template
                $overridePath = JPATH_SITE . '/templates/' . CompojoomTemplateHelper::getFrontendTemplate() . '/html/' . $component . '/fields/customfields/' . ucfirst($field->type) . '.php';
                if (file_exists($overridePath)) {
                    require_once $overridePath;
                    $className = 'Compojoom\\Component\\' . $component . '\\Site\\Field\\Customfields\\' . ucfirst($field->type);
                }

            }

//            $class = 'CompojoomFormCustomfields' . ucfirst($field->type);

			if (!isset(self::$customFields[$className]))
			{
				self::$customFields[$className] = new $className;
			}

			$xmlFields[] = self::$customFields[$className]->xml($field);
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

				if (count($option) > 1)
				{
					$array[trim($option[0])] = trim($option[1]);
				}
			}

			self::$optionsStore[$hash] = $array;
		}

		return self::$optionsStore[$hash];
	}

	/**
	 * Get the whole json object that some fields need
	 *
	 * @param   string  $options  - string with options
	 *
	 * @return object
	 */
	public static function getOptionsJson($options)
	{
		$hash = md5(serialize($options));

		if (!isset(self::$optionsJsonStore[$hash]))
		{
			$json = json_decode($options);

			// If the json string is malformed the field won't have a correct configuration and we need to let the user know this
			if (!($json && $options != $json))
			{
				throw new Exception('Your customfield configuration is not correct. The json string that you\'ve entered in the field options is malformed.', 500);
			}

			self::$optionsJsonStore[$hash] = $json;
		}

		return self::$optionsJsonStore[$hash];
	}
}
