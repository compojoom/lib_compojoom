<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       21.11.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class JFormFieldExplanation
 *
 * @since  4.0.50
 */
class JFormFieldExplanation extends JFormField
{
	/**
	 * Display the text we want
	 *
	 * @return void
	 */
	protected function getInput()
	{
		$alert  = (string) $this->element['alert'];
		$value = $this->translateDescription ? JText::_((string) $this->element['value']) : (string) $this->element['value'];

		if ($alert)
		{
			$html[] = '<div class="alert alert-' . $alert . '">';
			$html[] = $value;
			$html[] = '</div>';
		}
		else
		{
			$html[] = $value;
		}

		return implode('', $html);
	}

	/**
	 * Overrides the label
	 *
	 * @return void
	 */
	protected function getLabel()
	{
	}
}
