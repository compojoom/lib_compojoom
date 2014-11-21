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
 * Class CompojoomComponentHelper
 *
 * @since  4.0.21
 */
class CompojoomComponentHelper
{
	/**
	 * Checks if the component folder is existing and assumes that the component is installed
	 * The JComponentHelper::isEnabled function generates warnings and that's why we use this
	 * simplified approach to determine if the user has specific component
	 *
	 * @param   string  $component  - the component name (including com_)
	 *
	 * @return bool
	 */
	public static function isInstalled($component)
	{
		$folderPath = JPATH_SITE . '/components/' . $component;

		return JFolder::exists($folderPath);
	}

	/**
	 * Gets the item id for the provided component & view
	 *
	 * @param   string  $component  - the component string
	 * @param   string  $view       - the view name
	 *
	 * @return mixed <int>
	 */
	public static function getItemid($component = '', $view = '')
	{
		$appl = JFactory::getApplication();
		$menu = $appl->getMenu();
		$itemId = '';
		$items = $menu->getItems('component', $component);

		if ($view)
		{
			foreach ($items as $value)
			{
				if (strstr($value->link, 'view=' . $view))
				{
					$itemId = $value->id;
					break;
				}
			}
		}
		else
		{
			$itemId = isset($items[0]) ? $items[0]->id : '';
		}

		return $itemId;
	}
}
