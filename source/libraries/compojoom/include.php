<?php
/**
 * @package    Lib_Compojoom
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       04.11.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

if (!defined('COMPOJOOM_INCLUDED'))
{
	define('COMPOJOOM_INCLUDED', '1.0.0');

	// Register the autoloader
	require_once __DIR__ . '/autoloader/autoloader.php';
	CompojoomAutoloader::init();
}
