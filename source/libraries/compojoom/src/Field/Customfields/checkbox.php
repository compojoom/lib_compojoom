<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       2.01.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomFormCustomfieldsText
 *
 * @since  4.0
 */
class CompojoomFormCustomfieldsCheckbox
{
	/**
	 * Generates a xml string out of the field data
	 *
	 * @param   object  $data  - the field row
	 *
	 * @return string
	 */
	public function xml($data)
	{
		$string = '<field type="checkbox" name="' . $data->slug . '" default="' . $data->default . '" label="' .
			$data->title . '" required="' . ($data->allow_empty ? 'false' : 'true') . '" '
			. ' class="' . ($data->allow_empty ? '' : 'required') . ' form-control"/>';

		return $string;
	}

	/**
	 * There is no need to translate the value for text fields
	 *
	 * @param   object  $data              - the object with the field value
	 * @param   string  $valueToTranslate  - the value for the field
	 *
	 * @return string
	 */
	public function render($data, $valueToTranslate)
	{
		$options = CompojoomFormCustom::getOptionsArray($data->options);

		$value = $valueToTranslate;

		// If we have a checked value, then let's output it
		if (isset($options['value_checked']))
		{
			$value = $options['value_checked'];

			if (isset($options['translate']))
			{
				$value = JText::_($value);
			}

			return $value;
		}

		return $value;
	}
}
