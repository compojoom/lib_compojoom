<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       10.06.2015
 *
 * @copyright  Copyright (C) 2008 - 2015 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomOgp
 * 
 * Renders open graph data to the head of the page
 * 
 * @since  4.0.32
 */
class CompojoomOgp
{
	/**
	 * Adds the OpenGraph information on the page
	 *
	 * @param   array  $data  - the array containing the open graph data
	 *
	 * @return void
	 */
	public static function add($data)
	{
		$document = JFactory::getDocument();
		$document->addCustomTag('<meta property="og:url" content="' . JURI::current() . '" />');

		if (isset($data['type']))
		{
			if ($data['type'] == 'place')
			{
				if (isset($data['lat']) && isset($data['lng']))
				{
					$document->addCustomTag('<meta property="og:type" content="' . $data['type'] . '" />');
					$document->addCustomTag('<meta property="place:location:latitude" content="' . $data['lat'] . '" />');
					$document->addCustomTag('<meta property="place:location:longitude" content="' . $data['lng'] . '" />');
				}
			}
			else
			{
				$document->addCustomTag('<meta property="og:type" content="' . $data['type'] . '" />');
			}
		}

		if (isset($data['title']))
		{
			$document->addCustomTag('<meta property="og:title" content="' . self::escape(JHtmlString::truncate(strip_tags($data['title']), 150)) . '" />');
		}

		if (isset($data['description']))
		{
			$document->addCustomTag('<meta property="og:description" content="' . self::escape(JHtmlString::truncate(strip_tags(($data['description']), 200))) . '" />');
		}

		if (isset($data['image']) && strlen($data['image']))
		{
			$document->addCustomTag('<meta property="og:image" content="' . $data['image'] . '" />');
		}
	}

	/**
	 * Method to escape output.
	 *
	 * @param   string  $output  The output to escape.
	 *
	 * @return  string  The escaped output.
	 */
	private static function escape($output)
	{
		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
	}
}
