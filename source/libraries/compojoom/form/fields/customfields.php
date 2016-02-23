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
 * Class JFormFieldCustomfields
 *
 * Loads all available custom fields for the component
 * Looks in the library, component itself and in the template/html/com_componentName/fields/customfields
 *
 * @since  4.0.33
 */
class JFormFieldCustomfields extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'customfields';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		jimport('joomla.filesystem.folder');
		$component = $this->element['component'];
		$customfields = array();
		$options = array();

		$files = JFolder::files(JPATH_LIBRARIES . '/compojoom/form/customfields');

		foreach ($files as $file)
		{
			$customfields[] = basename($file, '.php');
		}

		$componentPath = JPATH_ADMINISTRATOR . '/components/' . $component . '/models/fields/customfields';
		$overridePath = JPATH_SITE . '/templates/' . CompojoomTemplateHelper::getFrontendTemplate() . '/html/' . $component . '/fields/customfields';

		if (file_exists($componentPath))
		{
			$files = JFolder::files($componentPath);

			foreach ($files as $file)
			{
				$customfields[] = basename($file, '.php');
			}
		}

		if (file_exists($overridePath))
		{
			$files = JFolder::files($overridePath);

			foreach ($files as $file)
			{
				$file = basename($file, '.php');

				if (!in_array($file, $customfields))
				{
					$customfields[] = $file;
				}
			}
		}

		foreach ($customfields as $customfield)
		{
			$options[] = Jhtml::_('select.option', $customfield, $customfield);
		}

		return $options;
	}
}
