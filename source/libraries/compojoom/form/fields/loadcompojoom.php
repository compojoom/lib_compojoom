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
 * Class JFormFieldLoadcompojoom
 *
 * @since  4.0.22
 */
class JFormFieldLoadcompojoom extends JFormField
{
	/**
	 * Overrides the input
	 * Loads the lib_compojoom classes and language files in a form
	 *
	 * @return void
	 */
	protected function getInput()
	{
		// Load the compojoom framework
		require_once JPATH_LIBRARIES . '/compojoom/include.php';

		$extension = (string) $this->element['component'];

		// Load language
		CompojoomLanguage::load($extension, JPATH_SITE);
		CompojoomLanguage::load($extension, JPATH_ADMINISTRATOR);
		CompojoomLanguage::load($extension . '.sys', JPATH_ADMINISTRATOR);
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
