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
 * Class CompojoomFormCustomfieldsAdvancedlist
 *
 * @since  4.0
 */
class CompojoomFormCustomfieldsAdvancedlist
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
		// clone the object and unset the options array as we don't need it for the field params
		$params = clone CompojoomFormCustom::getOptionsJson($data->options);
		unset($params->options);

		// transform the object to array
		$params = get_object_vars($params);

		$string = '<field name="' . htmlspecialchars($data->slug) . '" default="' . htmlspecialchars($data->default) . '" label="' .
			$data->title . '" required="' . ($data->allow_empty ? 'false' : 'true') . '"
			' .
			implode (' ', array_map(
				function($v, $k) {
					return $k.'="'.$v.'"';
				},
				$params,
				array_keys($params)
				)
			) .
			' type="list" class="form-control"
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
		$json = CompojoomFormCustom::getOptionsJson($options);
		$xml = array();

		foreach ($json->options as $option)
		{
			$xml[] = '<option value="' . htmlspecialchars($option->value) . '">' . htmlspecialchars($option->label) . '</option>';
		}

		return implode('', $xml);
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
		$options = CompojoomFormCustom::getOptionsJson($data->options);
		$options = $options->options;

		if (is_array($valueToTranslate))
		{
			$translated = array();

			foreach ($valueToTranslate as $vvalue)
			{
				foreach ($options as $option)
				{
					if ($option->value == $vvalue)
					{
						$translated[] = JText::_($option->value);
					}
				}
			}

			return implode(', ', $translated);
		}

		return $valueToTranslate;
	}
}
