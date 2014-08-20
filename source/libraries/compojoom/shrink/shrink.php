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
 * Class CompojoomShrink
 *
 * @since  4.0
 */
class CompojoomShrink
{
	/**
	 * Function that will merge & minify the provided files
	 *
	 * @param   array   $files      - array with relative paths to js files
	 * @param   string  $cachePath  - where to save the new merged & minified file
	 *
	 * @return string
	 */
	public static function shrink(array $files, $cachePath)
	{
		$times = array();
		$md5 = md5(json_encode($files));
		$url = $cachePath . '/' . $md5 . '.min.js';
		$minFile = JPATH_ROOT . '/' . $url;

		// Lets read the times of the files we need to merge
		foreach ($files as $file)
		{
			$times[] = filemtime(JPATH_ROOT . '/' . $file);
		}

		// If the minFile doesn't exist or the minFile time is older than any of the times, let's do our job!
		if (!file_exists($minFile) || max($times) > filemtime($minFile))
		{
			$js = '';

			foreach ($files as $file)
			{
				$js[] = file_get_contents(JPATH_ROOT . '/' . $file);
			}

			// Do the actual minifying
			$minJs = CompojoomMinifier::minify(implode($js));
			JFile::write($minFile, $minJs);
		}

		return $url;
	}
}
