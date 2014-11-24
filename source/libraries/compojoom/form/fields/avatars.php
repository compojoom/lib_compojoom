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
 * Class JFormFieldAvatars
 *
 * @since  4.0.22
 */
class JFormFieldAvatars extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'avatars';

	/**
	 * Create the input field
	 *
	 * @return mixed
	 */
	protected function getInput()
	{
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		$options = $this->getOptions();

		return JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		jimport('joomla.filesystem.folder');
		$options = array('0' => JText::_('LIB_COMPOJOOM_NONE'));
		$components = JFolder::files(JPATH_LIBRARIES . '/compojoom/avatars/avatars');
		$isPro = (int) $this->element['isPro'];

		foreach ($components as $component)
		{
			$disabled = true;
			$component = str_replace('.php', '', $component);

			// Disable this option if the component is not installed, or if we are not in a PRO extension
			if (CompojoomComponentHelper::isInstalled('com_' . $component) && $isPro)
			{
				$disabled = false;
			}

			$options[] = Jhtml::_('select.option', $component, JText::_('LIB_COMPOJOOM_COM_' . strtoupper($component)), 'value', 'text', $disabled);
		}

		return $options;
	}
}
