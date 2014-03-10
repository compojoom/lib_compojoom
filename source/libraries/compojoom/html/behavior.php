<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       10.03.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomHtmlBehavior
 *
 * @since  1.0
 */
class CompojoomHtmlBehavior
{
	/**
	 * Function to decide if we need to include our own boostrap.css file or not
	 *
	 * @return void
	 */
	public static function bootstrap()
	{
		if (JVERSION < 3.0)
		{
			JHTML::_('stylesheet', 'media/com_cmc/css/bootstrap.css');
		}
	}
}
