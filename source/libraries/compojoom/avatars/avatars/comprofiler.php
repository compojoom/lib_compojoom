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
 * Class CompojoomAvatarsComprofiler
 *
 * @since  4.0.22
 */
class CompojoomAvatarsComprofiler
{
	/**
	 * Gets the avatars from CB
	 *
	 * @param   array  $userIds  - user ids
	 *
	 * @return array
	 */
	public function getAvatars($userIds)
	{
		$avatars = array();

		if ($userIds)
		{
			$db = JFactory::getDBO();
			$query = 'SELECT ' . $db->qn('u.username')
				. ',' . $db->qn('c.user_id')
				. ',' . $db->qn('c.avatar')
				. ' FROM ' . $db->qn('#__users') . 'AS u,'
				. ' ' . $db->qn('#__comprofiler') . 'AS c'
				. ' WHERE ' . $db->qn('u.id') . '=' . $db->qn('c.user_id')
				. ' AND ' . $db->qn('u.id') . ' IN (' . implode(',', $userIds) . ')';

			$db->setQuery($query);
			$userList = $db->loadAssocList();


			foreach ($userList as $item)
			{
				if ($item['avatar'])
				{
					if (Joomla\String\StringHelper::strpos($item['avatar'], "gallery/") === false)
					{
						$path = JURI::base() . 'images/comprofiler/tn' . $item['avatar'];
					}
					else
					{
						$path = JURI::base() . 'images/comprofiler/' . $item['avatar'];
					}

					$avatars[$item['user_id']] = $path;
				}
			}
		}

		return $avatars;
	}
}
