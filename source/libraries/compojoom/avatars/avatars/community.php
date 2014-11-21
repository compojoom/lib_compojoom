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
 * Class CompojoomAvatarsCommunity
 *
 * @since  4.0.22
 */
class CompojoomAvatarsCommunity
{
	/**
	 * Build an array with all avatars from JomSocial
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
			$query = 'SELECT userid, thumb FROM #__community_users WHERE userid IN (' . implode(',', $userIds) . ')';
			$db->setQuery($query);
			$userList = $db->loadAssocList();
			$avatars = array();

			foreach ($userList as $item)
			{
				if ($item['thumb'])
				{
					$avatars[$item['userid']] = JURI::base() . $item['thumb'];
				}
			}
		}

		return $avatars;
	}
}
