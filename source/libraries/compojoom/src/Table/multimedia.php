<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       18.03.15
 *
 * @copyright  Copyright (C) 2008 - 2015 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomTableMultimedia
 *
 * @since  4.0.32
 */
class CompojoomTableMultimedia extends JTable
{
	/**
	 * The constructor
	 *
	 * @param   JDatabaseDriver  &$db  - the db object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__compojoom_multimedia', 'compojoom_multimedia_id', $db);
	}
}
