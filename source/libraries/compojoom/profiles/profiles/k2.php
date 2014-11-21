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
 * Class CompojoomProfilesK2
 *
 * @since  4.0.22
 */
class CompojoomProfilesK2
{
	/**
	 * Creates a link to K2 profile
	 *
	 * @param   int  $id  - user id
	 *
	 * @return string - link to profile
	 */
	public static function getLink($id)
	{
		require_once JPATH_ROOT . '/components/com_k2/helpers/route.php';

		$link = JRoute::_(K2HelperRoute::getUserRoute($id));

		return $link;
	}
}
