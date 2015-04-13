<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       13.04.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomControllerJed
 *
 * @since  4.0.30
 */
abstract class CompojoomControllerJed extends CompojoomController
{
	/**
	 * Checks if the user has reviewed the component & if he hasn't, it asks him to review
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function reviewed()
	{
		$config = JComponentHelper::getParams($this->component);
		$jed = $config->get('jed', 0);
		$result = '';

		if ($jed != 1)
		{
			$layout = new CompojoomLayoutFile('jed.jed');

			if ($jed == 0)
			{
				$result = $layout->render($this->data);
			}
			else
			{
				$now = JFactory::getDate();
				$reminderSet = JFactory::getDate($jed);
				$diff = $now->diff($reminderSet);

				if ($diff->days > 7)
				{
					$result = $layout->render($this->data);
				}
			}
		}

		echo '###' . $result . '###';

		// Cut the execution short
		JFactory::getApplication()->close();
	}

	/**
	 * Update the value for jed
	 * 1 = don't remind me again
	 * 2 = remind me in a week
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function update()
	{
		$config = JComponentHelper::getParams($this->component);
		$jed = JFactory::getApplication()->input->getInt('jed', 1);

		// The user wants a reminder
		if ($jed === 2)
		{
			$jed = JFactory::getDate()->toSql();
		}

		$config->set('jed', $jed);

		// Store the updated config
		CompojoomComponentHelper::updateConfiguration($this->component, $config);

		$this->setRedirect('index.php?option=' . $this->component);
	}
}
