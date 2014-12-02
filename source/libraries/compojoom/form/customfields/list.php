<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       03.02.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomFormCustomfieldsList
 *
 * @since  4.0
 */
class CompojoomFormCustomfieldsList
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
		$string = '<field name="' . htmlspecialchars($data->slug) . '" default="' . htmlspecialchars($data->default) . '" label="' .
			$data->title . '" required="' . ($data->allow_empty ? 'false' : 'true') . '"
			type="list"
			>';
		$string .= $this->options($data->options);
		$string .= '</field>';

		return $string;
	}

	/**
	 * Create the options string
	 *
	 * @param   string  $options  - the options
	 *
	 * @return string
	 */
	private function options($options)
	{
		$options = $this->getOptionsArray($options);
		$xml = array();

		foreach ($options as $key => $value)
		{
			$xml[] = '<option value="' . htmlspecialchars($key) . '">' . htmlspecialchars($value) . '</option>';
		}

		return implode('', $xml);
	}

	/**
	 * Creates an options array
	 *
	 * @param   string  $options  - string with options
	 *
	 * @return array
	 */
	private function getOptionsArray($options)
	{
		$options = explode("\n", $options);
		$array = array();

		foreach ($options as $value)
		{
			$option = explode('=', $value);

			$array[trim($option[0])] = trim($option[1]);
		}

		return $array;
	}

	/**
	 * Let's get the translated label for the value!
	 *
	 * @param   object  $data              - the object with the field value
	 * @param   string  $valueToTranslate  - the value for the field
	 *
	 * @return string
	 */
	public function render($data, $valueToTranslate)
	{
		$options = $this->getOptionsArray($data->options);

		if (is_array($valueToTranslate))
		{
			$translated = array();

			foreach ($valueToTranslate as $kkey => $vvalue)
			{
				foreach ($options as $key => $value)
				{
					if ($key == $vvalue)
					{
						$translated[] = JText::_($value);
					}
				}
			}

			return implode(', ', $translated);
		}
		else
		{
			foreach ($options as $key => $value)
			{
				if ($key == $valueToTranslate)
				{
					return JText::_($value);
				}
			}
		}

		return $valueToTranslate;
	}
}
