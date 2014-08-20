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
	 * Ads the specified js files to the head of the page (merges &
	 * minifies them if necessary)
	 *
	 * @param   string|array  $files      - single file or array with file paths
	 * @param   string        $cachePath  - the path to the cache folder
	 * @param   bool          $minify     - should we minify it
	 *
	 * @return void
	 */
	public static function script($files, $cachePath, $minify = true)
	{
		// If we have a string, then we are dealing with a single file
		if (is_string($files))
		{
			$files = array($files);
		}

		// Let's merge and minify if we need to
		if ($minify)
		{
			$fileName =	CompojoomShrink::shrink($files, $cachePath);
			JHtml::script($fileName);
		}
		else
		{
			// Output each file from the array as is
			foreach ($files as $file)
			{
				JHtml::script($file);
			}
		}
	}
}
