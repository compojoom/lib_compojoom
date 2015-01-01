<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       01.01.2015
 *
 * @copyright  Copyright (C) 2008 - 2015 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');


/**
 * Class CompojoomProfilesEasySocial
 *
 * @since  4.0.23
 */
class CompojoomProfilesEasySocial
{
	/**
	 * Creates a link to EasySocial
	 *
	 * @param   int  $id  - user id
	 *
	 * @return string - link to profile
	 */
	public static function getLink($id)
	{
		include_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';

		$link = FD::user($id)->getPermalink(false);

		return $link;
	}
}
