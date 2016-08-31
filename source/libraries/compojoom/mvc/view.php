<?php
/**
 * @package    Lib_Compojoom
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       16.05.16
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomMvcView
 *
 * @since  5.0
 */
class CompojoomMvcView extends JViewLegacy
{
	/**
	 * The JDocument
	 * 
	 * @var     JDocument
	 *
	 * @since   5.0.0
	 */
	public $document = null;

	public function __construct($config = array())
	{
		$this->document = JFactory::getDocument();

		parent::__construct($config);
	}
}
