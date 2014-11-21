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
 * Class CompojoomProfilesCommunity
 *
 * @since  4.0.22
 */
class CompojoomProfilesCommunity
{
	/**
	 * Creates a link to JomSocial profile
	 *
	 * @param   int  $id  - user id
	 *
	 * @return string - link to profile
	 */
	public static function getLink($id)
	{
		include_once JPATH_ROOT . '/components/com_community/libraries/core.php';

		$link = CRoute::_('index.php?option=com_community&view=profile&userid=' . $id);

		return $link;
	}
}
