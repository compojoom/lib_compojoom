<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       20.11.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomAvatarsK2
 *
 * @since  4.0.22
 */
class CompojoomAvatarsK2
{
	/**
	 * Gets user avatars from K2
	 *
	 * @param   array  $userIds  - array with user ids
	 *
	 * @return array
	 */
	public static function getAvatars($userIds)
	{
		$avatars = array();

		if ($userIds)
		{
			$db = JFactory::getDBO();
			$query = 'SELECT userID as userid, image FROM #__k2_users WHERE userid IN (' . implode(',', $userIds) . ')';
			$db->setQuery($query);
			$userList = $db->loadAssocList();

			foreach ($userList as $item)
			{
				if ($item['image'])
				{
					$avatars[$item['userid']] = JURI::root() . 'media/k2/users/' . $item['image'];
				}
			}
		}

		return $avatars;
	}
}
