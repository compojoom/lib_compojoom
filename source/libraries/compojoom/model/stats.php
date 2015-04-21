<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       17.09.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomModelUpdate
 *
 * This class is based onF0FUtilsUpdate so all creadits go to F0F
 *
 * @since  4.0
 */
class CompojoomModelStats extends JModelLegacy
{
	protected $extension = '';

	protected $exclude = array(
		'downloadid'
	);

	/**
	 * Constructor
	 *
	 * @param   array  $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function __construct($config)
	{
		if ($this->extension == '')
		{
			throw new Exception('You need to provide an extension');
		}

		parent::__construct($config);
	}

	/**
	 * Get the necessary data for our stats report
	 *
	 * @return array
	 */
	public function getData()
	{
		$data = array();
		$db = JFactory::getDbo();
		$data['php'] = phpversion();
		$data['mysql'] = $db->getVersion();
		$data['joomla'] = JVERSION;
		$data['os'] = php_uname();
		$data['server'] = apache_get_version() ? apache_get_version() : 'other';
		$data['extension'] = $this->extension;
		$data['config'] = $this->getConfig();

		return $data;
	}

	/**
	 * We've gathered the data and all we need to do is to update the time for the last report
	 *
	 * @return void
	 */
	public function dataGathered()
	{
		$data = CompojoomComponentHelper::getComponentCustomData($this->extension);
		$data->set('update_report_sent', JFactory::getDate()->toSql());

//		CompojoomComponentHelper::updateComponentCustomData($this->extension, $data);
	}

	/**
	 * Get the component config by respecting the exclude options
	 *
	 * @return string
	 */
	private function getConfig()
	{
		$config = JComponentHelper::getParams($this->extension);

		// Remove the values for any excluded option
		foreach ($this->exclude as $value)
		{
			$config->set($value, null);
		}

		return $config->toString();
	}

	/**
	 * Do we need to update the statistics on this site?
	 *
	 * @return bool
	 */
	public function needsUpdate()
	{
		$config = JComponentHelper::getParams($this->extension);

		// Has the user enabled stat reports?
		if ($config->get('update_stats', 1))
		{
			// When was the last time we've send a report?
			$customData = CompojoomComponentHelper::getComponentCustomData($this->extension);

			$reportSent = $customData->get('update_report_sent', '');

			// We haven't set anything, then we need to update
			if ($reportSent == '')
			{
				return true;
			}

			// We have a date?
			$now = JFactory::getDate();
			$reportSentDate = JFactory::getDate($reportSent);
			$diff = $now->diff($reportSentDate);

			if ($diff->days > 7)
			{
				return true;
			}
		}

		return false;
	}
}
