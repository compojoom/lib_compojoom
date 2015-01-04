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

		include_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';

		if ($userIds)
		{
			// Preload the users in ES
			FD::user($userIds);

			foreach ($userIds as $id)
			{
				// Get the avatars
				$avatars[$id] = FD::user($id)->getAvatar();
			}
		}

		return $avatars;
	}
}
