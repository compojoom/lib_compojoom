<?php
/**
 * @package    Hotspots
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       01.08.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

jimport('joomla.application.component.controller');

defined('_JEXEC') or die ('Restricted access');

/**
 * Class hotspotsController
 *
 * @since  3.0
 */
class CompojoomController extends JControllerLegacy
{
	protected $default_view = 'dashboard';

	/**
	 * The constructor
	 *
	 * Ah, we normally don't have to override the constructor,
	 * but we run into problems on j2.5 because JRequest there can't properly
	 * handle DELETE verb in HTTP request. We end up with the wrong task
	 * That's why we make sure that task is changed on the $input object
	 * where it is handled properly and not on the JRequest object
	 *
	 * @param   array  $config  - array with config options
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		if (JVERSION < 3)
		{
			$input = JFactory::getApplication()->input;

			// Get the environment configuration.
			$command  = $input->get('task', 'display');

			// Check for array format.
			$filter = JFilterInput::getInstance();

			if (is_array($command))
			{
				$command = $filter->clean(array_pop(array_keys($command)), 'cmd');
			}
			else
			{
				$command = $filter->clean($command, 'cmd');
			}

			// Check for a controller.task command.
			if (strpos($command, '.') !== false)
			{
				// Explode the controller.task command.
				list ($type, $task) = explode('.', $command);

				// Let's override the input!
				$input->set('task', $task);
			}
		}
	}
}
