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
 * Class CompojoomProfilesKunena
 *
 * @since  4.0.22
 */
class CompojoomProfilesKunena
{
	/**
	 * Creates a link to Kunena profile
	 *
	 * @param   int  $id  - user id
	 *
	 * @return string - link to profile
	 */
	public static function getLink($id)
	{
		$link = KunenaUserHelper::get($id);

		return $link->getURL();
	}
}
