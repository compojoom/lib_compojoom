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

	/**
	 * Gets allowed actions
	 *
	 * @param   int     $messageId  - message id
	 * @param   string  $unit       - the unit
	 * @param   string  $assetName  - asset name
	 *
	 * @return JObject
	 */
	public static function getActions($messageId = 0, $unit = 'component', $assetName = '')
	{
		jimport('joomla.access.access');
		$user = JFactory::getUser();
		$result = new JObject;

		if (empty($messageId))
		{
			$asset = $assetName;
		}
		else
		{
			$asset = $assetName . '.' . $unit . '.' . (int) $messageId;
		}

		$actions = JAccess::getActions($assetName, $unit);

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $asset));
		}

		return $result;
	}

	/**
	 * Update the configuration of a component
	 *
	 * @param   string  $component  - the component
	 * @param   object  $config     - the config object
	 *
	 * @return void
	 */
	public static function updateConfiguration($component, $config)
	{
		// Now let's update the Database
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set($db->qn('params') . '=' . $db->q($config))
			->where($db->qn('element') . '=' . $db->q($component))
			->where($db->qn('type') . '=' . $db->q('component'));
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Get the custom_data field from the #__extensions table and return it as JRegistry
	 *
	 * @param   string  $component  - the component name
	 *
	 * @return JRegistry
	 */
	public static function getComponentCustomData($component)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$customData = new JRegistry;

		$query->select('custom_data')->from('#__extensions')
			->where($db->qn('element') . '=' . $db->q($component))
			->where($db->qn('type') . '=' . $db->q('component'));
		$db->setQuery($query);
		$data = $db->loadObject();

		if ($data)
		{
			$customData->loadString($data->custom_data);
		}

		return $customData;
	}

	/**
	 * Update the custom_data field in the #__extensions table with our object
	 *
	 * @param   string     $component  - the component name
	 * @param   JReqistry  $data       - the custom data to store
	 *
	 * @return void
	 */
	public static function updateComponentCustomData($component, $data)
	{
		// Now let's update the custom data
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set($db->qn('custom_data') . '=' . $db->q($data))
			->where($db->qn('element') . '=' . $db->q($component))
			->where($db->qn('type') . '=' . $db->q('component'));
		$db->setQuery($query);

		$db->execute();
	}
}
