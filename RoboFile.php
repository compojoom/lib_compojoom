<?php
/**
 * @package    JBuild
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       20.09.15
 *
 * @copyright  Copyright (C) 2008 - 2015 Yves Hoppe - compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', __DIR__);
}

// PSR-4 Autoload by composer
require_once JPATH_BASE . '/vendor/autoload.php';

/**
 * RoboFile for lib_compojoom
 *
 * @since  5.3
 */
class RoboFile extends \Robo\Tasks
{
	use \Joomla\Jorobo\Tasks\loadTasks;

	/**
	 * Initialize Robo
	 */
	public function __construct()
	{
		$this->stopOnFail(true);
	}

	/**
	 * Map into Joomla installation.
	 *
	 * @param   String   $target    The target joomla instance
	 * @param   boolean  $override  Override existing mappings?
	 *
	 * @return  void
	 */
	public function map($target, $override = true)
	{
		$this->taskMap($target)->run();
	}

	/**
	 * Build the library package
	 *
	 * @param   array  $params  Additional params
	 *
	 * @return  void
	 */
	public function build($params = ['dev' => false])
	{
		// Library cache
		$this->_deleteDir(__DIR__ . '/source/media/lib_compojoom/cache');
		$this->_mkdir(__DIR__ . '/source/media/lib_compojoom/cache');

		$this->taskBuild($params)->run();
	}
}
