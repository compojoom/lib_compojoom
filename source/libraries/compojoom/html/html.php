<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       20.08.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomHtml
 *
 * @since  4.0.1
 */
class CompojoomHtml
{
	/**
	 * @var array
	 */
	private static $queue = array();

	/**
	 * @var array
	 */
	private static $cssqueue = array();

	/**
	 * Holds an internal queue with javascript files
	 *
	 * @param   string  $key      - the key for the storage
	 * @param   mixed   $scripts  - file name or array with filenames
	 *
	 * @return void
	 */
	public static function addScriptsToQueue($key, $scripts)
	{
		if (is_array($scripts))
		{
			foreach ($scripts as $value)
			{
				self::addScriptToQueue($key, $value);
			}
		}

		if (is_string($scripts))
		{
			self::addScriptToQueue($key, $scripts);
		}
	}

	/**
	 * Add a single script to que (if it's not already added)
	 *
	 * @param   string  $key     - the key for the storage
	 * @param   mixed   $script  - file name
	 *
	 * @return  void
	 */
	private static function addScriptToQueue($key, $script)
	{
		if (isset(self::$queue[$key]) && in_array($script, self::$queue[$key]))
		{
			return;
		}

		self::$queue[$key][] = $script;
	}


	/**
	 * Holds an internal queue with css files
	 *
	 * @param   string  $key  - the key for the storage
	 * @param   mixed   $css  - file name or array with filenames
	 *
	 * @return void
	 */
	public static function addCSSToQueue($key, $css)
	{
		if (is_array($css))
		{
			foreach ($css as $value)
			{
				self::addSingleCSSToQueue($key, $value);
			}
		}

		if (is_string($css))
		{
			self::addSingleCSSToQueue($key, $css);
		}
	}


	/**
	 * Add a single script to que (if it's not already added)
	 *
	 * @param   string  $key     - the key for the storage
	 * @param   mixed   $script  - file name
	 *
	 * @return  void
	 */
	private static function addSingleCSSToQueue($key, $script)
	{
		if (isset(self::$cssqueue[$key]) && in_array($script, self::$cssqueue[$key]))
		{
			return;
		}

		self::$cssqueue[$key][] = $script;
	}

	/**
	 * Get the scripts from the queue based on the key
	 *
	 * @param   string  $key  - the key name
	 *
	 * @return  mixed
	 */
	public static function getScriptQueue($key)
	{
		return isset(self::$queue[$key]) ? self::$queue[$key] : array();
	}

	/**
	 * Get the css from the queue based on the key
	 *
	 * @param   string  $key  - the key name
	 *
	 * @return  mixed
	 */
	public static function getCSSQueue($key)
	{
		return isset(self::$cssqueue[$key]) ? self::$cssqueue[$key] : array();
	}

	/**
	 * Ads the specified js files to the head of the page (merges &
	 * minifies them if necessary)
	 *
	 * @param   string|array  $files      - single file or array with file paths
	 * @param   string        $cachePath  - the path to the cache folder
	 * @param   bool          $minify     - should we minify it
	 * @param   bool          $scriptTag  - if set to true outputs the javascript tag directly in the body of the site
	 *
	 * @return void
	 */
	public static function script($files, $cachePath, $minify = true, $scriptTag = false)
	{
		// If we have a string, then we are dealing with a single file
		if (is_string($files))
		{
			$files = array($files);
		}

		// Check if there are any files
		if (!count($files))
		{
			return;
		}

		// Let's merge and minify if we need to
		if ($minify)
		{
			$fileName = CompojoomShrink::shrink($files, $cachePath);

			if ($scriptTag)
			{
				echo '<script data-inline type="text/javascript" src="' . $fileName . '"></script>' . "\n";
			}
			else
			{
				JHtml::script($fileName);
			}
		}
		else
		{
			// Output each file from the array as is
			foreach ($files as $file)
			{
				if ($scriptTag)
				{
					echo '<script data-inline type="text/javascript" src="' . $file . '"></script>' . "\n";
				}
				else
				{
					JHtml::script($file);
				}
			}
		}
	}

	/**
	 * Ads the specified css files to the head of the page (minifies & joins them if necessary)
	 *
	 * @param   string|array  $files      - single file or array with file paths
	 * @param   string        $cachePath  - the path to the cache folder
	 * @param   bool          $minify     - should we minify it
	 *
	 * @return void
	 */
	public static function css($files, $cachePath, $minify = true)
	{
		// If we have a string, then we are dealing with a single file
		if (is_string($files))
		{
			$files = array($files);
		}

		// Check if there are any files
		if (!count($files))
		{
			return;
		}

		// Let's merge and minify if we need to
		if ($minify)
		{
			$css = array();
			$times = array();

			foreach ($files as $file)
			{
				if (file_exists(JPATH_ROOT . '/' . $file))
				{
					$times[] = filemtime(JPATH_ROOT . '/' . $file);
				}
			}

			$md5 = md5(json_encode($files));
			$url = $cachePath . '/' . $md5 . '.min.css';
			$minFile = JPATH_ROOT . '/' . $url;


			// If the minFile doesn't exist or the minFile time is older than any of the times, let's do our job!
			if (!file_exists($minFile) || max($times) > filemtime($minFile))
			{
				// TODO Move to autoload
				require_once JPATH_LIBRARIES . "/compojoom/cssmin/cssmin.php";

				// Minify & Join CSS filfes
				foreach ($files as $file)
				{
					$css[] = "/* File: " . $file . " */";
					$css[] = CSSMin::minify(file_get_contents(JPATH_ROOT . "/" . $file));
				}

				$mincss = implode("\n", $css);
				JFile::write($minFile, $mincss);
			}

			// Load css file
			JHtml::stylesheet($url);
		}
		else
		{
			// Output each file from the array as is
			foreach ($files as $file)
			{
				JHtml::stylesheet($file);
			}
		}
	}

	/**
	 * Combines minify of css & js (merges &
	 * minifies them if necessary)
	 *
	 * @param   string|array  $jsfiles    - single file or array with file paths
	 * @param   string|array  $cssfiles   - single file or array with file paths
	 * @param   string        $cachePath  - the path to the cache folder
	 * @param   bool          $minifyJs   - should we minify it
	 * @param   bool          $minifyCss  - should we minify it
	 * @param   bool          $scriptTag  - if set to true outputs the javascript tag directly in the body of the site
	 *
	 * @return void
	 */
	public static function external($jsfiles, $cssfiles, $cachePath, $minifyJs = true, $minifyCss = true, $scriptTag = false)
	{
		self::script($jsfiles, $cachePath, $minifyJs, $scriptTag);
		self::css($cssfiles, $cachePath, $minifyCss);
	}

	/**
	 * Loads the recaptcha v2 url with language in the head
	 *
	 * @param   array  $params  array with additional parameters to hang on the url
	 *
	 * @return void
	 */
	public static function recaptcha(array $params = array())
	{
		JHtml::script('https://www.google.com/recaptcha/api.js?hl=' . JFactory::getLanguage()->getTag() . implode('&', $params));
	}
}
