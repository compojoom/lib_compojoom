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

		$this->compileLess();
		$this->minifyCss();

		$this->taskBuild($params)->run();
	}

	/**
	 * Compile less to css
	 */
	public function compileLess()
	{
		$this->taskLess([
			'source/media/lib_compojoom/less/compojoom-bootstrap.less' => 'source/media/lib_compojoom/css/compojoom-bootstrap-3.3.6.css',
			'source/media/lib_compojoom/less/compojoom.less' => 'source/media/lib_compojoom/css/compojoom.css'
		])
			->importDir('source/media/lib_compojoom/less')
			->compiler('lessphp')
			->run();
	}

	/**
	 * Minify css files
	 */
	public function minifyCss()
	{
		// Bootstrap
		$this->taskMinify( 'source/media/lib_compojoom/css/compojoom-bootstrap-3.3.6.css' )
			->to('source/media/lib_compojoom/css/compojoom-bootstrap-3.3.6.min.css')
			->run();

		// Compojoom fixes
		$this->taskMinify( 'source/media/lib_compojoom/css/compojoom.css' )
			->to('source/media/lib_compojoom/css/compojoom.min.css')
			->run();
	}
}
