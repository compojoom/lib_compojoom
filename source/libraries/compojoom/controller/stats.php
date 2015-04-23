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
class CompojoomControllerStats extends CompojoomController
{
	/**
	 * Looks for an update to the extension
	 *
	 * @return string
	 */
	public function send()
	{
		JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		$statsModel = $this->getModel();

		$data = $statsModel->getData();

		// Set up our JRegistry object for the BDHttp connector
		$options = new JRegistry;

		// Use a 30 second timeout
		$options->set('timeout', 30);

		try
		{
			$transport = JHttpFactory::getHttp($options);
		}
		catch (Exception $e)
		{
			echo '###Something went wrong! We could not even get a transporter!###';
			JFactory::getApplication()->close();
		}

		// We have to provide the user-agent here, because Joomla! 2.5 won't understand it
		// If we add it in the $options...
		$request = $transport->post(
			'https://stats.compojoom.com',
			$data,
			array('user-agent' => 'LibCompojoom/4.0' )
		);

		// There is a bug in curl, that we don't have an work-around in j2.5 That's why we
		// will asume here that 100 == 200...
		if ($request->code == 200 || (JVERSION < 3 && $request->code == 100))
		{
			// Let's update the date
			$statsModel->dataGathered();

			echo '###All Good!###';
		}
		else
		{
			echo '###Something went wrong!###';
		}

		// Cut the execution short
		JFactory::getApplication()->close();
	}
}
