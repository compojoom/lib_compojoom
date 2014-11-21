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
 * Class CompojoomProfilesComprofiler
 *
 * @since  4.0.22
 */
class CompojoomProfilesComprofiler
{
	/**
	 * Creates a link to CB profile
	 *
	 * @param   int  $id  - user id
	 *
	 * @return string - link to profile
	 */
	public static function getLink($id)
	{
		$itemId = '';

		if (CompojoomComponentHelper::getItemid('com_comprofiler'))
		{
			$itemId = '&Itemid=' . CompojoomComponentHelper::getItemid('com_comprofiler');
		}

		$link = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user=' . $id . $itemId);

		return $link;
	}
}
