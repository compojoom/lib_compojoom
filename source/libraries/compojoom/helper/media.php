<?php
/**
 * @package    lib_compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       30.03.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

if (JVERSION < 3)
{
	JLoader::register('JHelperMedia', JPATH_LIBRARIES . '/compojoom/helper/joomla/media.php');
}

/**
 * Class CompojoomHelperMedia
 *
 * @since  4.0.33
 */
class CompojoomHelperMedia extends JHelperMedia
{
}
