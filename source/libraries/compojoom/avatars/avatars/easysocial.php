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
 * Class CompojoomAvatarsEasysocial
 *
 * @since  4.0.23
 */
class CompojoomAvatarsEasysocial
{
	/**
	 * Build an array with all avatars from EasySocial
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
			$query = $db->getQuery(true);
			$query->select('uid AS userid, medium AS thumb')
				->from('#__social_avatars')
				->where('uid = ' . implode(',', $userIds))
				->where('type = ' . $db->q('user'));
			$db->setQuery($query);
			$userList = $db->loadAssocList();
			$avatars = array();

			foreach ($userList as $item)
			{
				if ($item['thumb'])
				{
					$avatars[$item['userid']] = JURI::base() . 'media/com_easysocial/avatars/users/' . $item['userid'] . '/' . $item['thumb'];
				}
			}
		}

		return $avatars;
	}
}
