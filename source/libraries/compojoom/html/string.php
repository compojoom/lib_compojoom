<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       30.09.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * HTML helper class for rendering manipulated strings.
 * Since the truncateComplex is not available on j3.1
 * we had to create this class
 *
 * @package     Lib_Compojoom
 * @subpackage  HTML
 * @since       1.6
 */
abstract class CompojoomHtmlString
{
	/**
	 * This method is available first in joomla 3.1
	 * We have this function here to polyfill for joomla 2.5
	 *
	 * Method to extend the truncate method to more complex situations
	 *
	 * The goal is to get the proper length plain text string with as much of
	 * the html intact as possible with all tags properly closed.
	 *
	 * @param   string   $html       The content of the introtext to be truncated
	 * @param   integer  $maxLength  The maximum number of characters to render
	 * @param   boolean  $noSplit    Don't split a word if that is where the cutoff occurs (default: true).
	 *
	 * @return  string  The truncated string. If the string is truncated an ellipsis
	 *                  (...) will be appended.
	 *
	 * @note    If a maximum length of 3 or less is selected and the text has more than
	 *          that number of characters an ellipsis will be displayed.
	 *          This method will not create valid HTML from malformed HTML.
	 *
	 * @since   3.1
	 */
	public static function truncateComplex($html, $maxLength = 0, $noSplit = true)
	{
		if (JVERSION > 3.1)
		{
			return JHtmlString::truncateComplex($html, $maxLength, $noSplit);
		}

		// Start with some basic rules.
		$baseLength = strlen($html);

		// If the original HTML string is shorter than the $maxLength do nothing and return that.
		if ($baseLength <= $maxLength || $maxLength == 0)
		{
			return $html;
		}

		// Take care of short simple cases.
		if ($maxLength <= 3 && substr($html, 0, 1) != '<' && strpos(substr($html, 0, $maxLength - 1), '<') === false && $baseLength > $maxLength)
		{
			return '...';
		}

		// Deal with maximum length of 1 where the string starts with a tag.
		if ($maxLength == 1 && substr($html, 0, 1) == '<')
		{
			$endTagPos = strlen(strstr($html, '>', true));
			$tag = substr($html, 1, $endTagPos);

			$l = $endTagPos + 1;

			if ($noSplit)
			{
				return substr($html, 0, $l) . '</' . $tag . '...';
			}

			// TODO: $character doesn't seem to be used...
			$character = substr(strip_tags($html), 0, 1);

			return substr($html, 0, $l) . '</' . $tag . '...';
		}

		// First get the truncated plain text string. This is the rendered text we want to end up with.
		$ptString = JHtml::_('string.truncate', $html, $maxLength, $noSplit, $allowHtml = false);

		// It's all HTML, just return it.
		if (strlen($ptString) == 0)
		{
			return $html;
		}

		// If the plain text is shorter than the max length the variable will not end in ...
		// In that case we use the whole string.
		if (substr($ptString, -3) != '...')
		{
			return $html;
		}

		// Regular truncate gives us the ellipsis but we want to go back for text and tags.
		if ($ptString == '...')
		{
			$stripped = substr(strip_tags($html), 0, $maxLength);
			$ptString = JHtml::_('string.truncate', $stripped, $maxLength, $noSplit, $allowHtml = false);
		}

		// We need to trim the ellipsis that truncate adds.
		$ptString = rtrim($ptString, '.');

		// Now deal with more complex truncation.
		while ($maxLength <= $baseLength)
		{
			// Get the truncated string assuming HTML is allowed.
			$htmlString = JHtml::_('string.truncate', $html, $maxLength, $noSplit, $allowHtml = true);

			if ($htmlString == '...' && strlen($ptString) + 3 > $maxLength)
			{
				return $htmlString;
			}

			$htmlString = rtrim($htmlString, '.');

			// Now get the plain text from the HTML string and trim it.
			$htmlStringToPtString = JHtml::_('string.truncate', $htmlString, $maxLength, $noSplit, $allowHtml = false);
			$htmlStringToPtString = rtrim($htmlStringToPtString, '.');

			// If the new plain text string matches the original plain text string we are done.
			if ($ptString == $htmlStringToPtString)
			{
				return $htmlString . '...';
			}

			// Get the number of HTML tag characters in the first $maxLength characters
			$diffLength = strlen($ptString) - strlen($htmlStringToPtString);

			if ($diffLength <= 0)
			{
				return $htmlString . '...';
			}

			// Set new $maxlength that adjusts for the HTML tags
			$maxLength += $diffLength;
		}
	}
}
