<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       2016-09-29
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

/**
 * FreeGeoIp location helper
 *
 * @since  6.0.1
 */
class CompojoomGeolocation
{
	/**
	 * Get the location
	 *
	 * @return  array ('lat', 'lng', 'text')
	 *
	 * @since   5.1.0
	 */
	public static function getLocation()
	{
		$cookieLocation = self::getCookieLocation();

		// Use saved location
		if ($cookieLocation)
		{
			return json_decode($cookieLocation);
		}

		$geoLocation = self::getGeolocation();

		if ($geoLocation)
		{
			self::setCookieLocation($geoLocation);

			return $geoLocation;
		}

		// Warning returning default
		return array('lat' => '', 'lng' => '', 'text' => 'london');
	}

	/**
	 * Get the location based on the cookie
	 *
	 * @return  mixed
	 *
	 * @since   5.1.0
	 */
	protected static function getCookieLocation()
	{
		$jcookie = JFactory::getApplication()->input->cookie;

		return $jcookie->get('clocation', null, 'raw');
	}

	/**
	 * Set the cookie location
	 *
	 * @param   array  $location  Location array with lat, lng, text
	 *
	 * @since   5.1.0
	 */
	public static function setCookieLocation($location, $duration = null)
	{
		if (!$duration)
		{
			// Valid for 90 days
			$duration = time()+60*60*24*90;
		}

		$jCookie = JFactory::getApplication()->input->cookie;

		$jCookie->set('clocation', json_encode($location), $duration);
	}

	/**
	 * Get the location based on freegeoip
	 *
	 * @return  bool|string
	 *
	 * @since   5.1.0
	 */
	protected static function getGeolocation()
	{
		$ip = self::getUserIp();

		$url       = 'http://freegeoip.net/json/' . $ip;
		$options   = new JRegistry;
		$transport = new JHttpTransportCurl($options);

		// Create a 'curl' transport.
		$http = new JHttp($options, $transport);

		$http->setOption('timeout', 5);

		try
		{
			$get = $http->get($url);
		}
		catch (Exception $e)
		{
			return false;
		}

		if ($get->code === 200)
		{
			$body = json_decode($get->body);

			$text = 'Unknown';

			// Small to large areas
			if (!empty($body->city))
			{
				$text = $body->city;
			}
			elseif (!empty($body->zip_code))
			{
				$text = $body->zip_code;
			}
			elseif (!empty($body->region_name))
			{
				$text = $body->region_name;
			}
			elseif (!empty($body->country_name))
			{
				$text = $body->country_name;
			}

			return array('lat' => $body->latitude, 'lng' => $body->longitude, 'text' => $text);
		}

		return false;
	}

	/**
	 * Makes a best quess what the user's IP is
	 *
	 * @return  mixed
	 *
	 * @since   5.1.0
	 */
	private static function getUserIp()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		}

		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		return $_SERVER['REMOTE_ADDR'];
	}
}