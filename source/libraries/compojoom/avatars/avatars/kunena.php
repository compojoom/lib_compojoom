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

JLoader::register('KunenaUserHelper', JPATH_LIBRARIES . '/kunena/user/helper.php');

/**
 * Class CompojoomAvatarsKunena
 *
 * @since  4.0.22
 */
class CompojoomAvatarsKunena
{
	/**
	 * Gets the users avatars from Kunena
	 *
	 * @param   array  $userIds  - array with user ids
	 *
	 * @return array
	 */
	public static function getAvatars($userIds)
	{
		$avatars = array();
		$users = KunenaUserHelper::loadUsers($userIds);

		foreach ($users as $user)
		{
			$avatars[$user->userid] = $user->getAvatarURL();
		}

		return $avatars;
	}
}
