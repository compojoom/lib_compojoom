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

	/**
	 * Generates a Universally Unique Identifier, version 4. (truly random UUID)
	 *
	 * @param   bool  $hex  - If TRUE return the uuid in hex format, otherwise as a string
	 *
	 * @see http://tools.ietf.org/html/rfc4122#section-4.4
	 * @see http://en.wikipedia.org/wiki/UUID
	 *
	 * @return string - A UUID, made up of 36 characters or 16 hex digits.
	 *
	 * @since  5.1.13
	 */
	public static function getUuid($hex = false)
	{
		$pr_bits = false;

		if (!$pr_bits)
		{
			$fp = @fopen('/dev/urandom', 'rb');

			if ($fp !== false)
			{
				$pr_bits .= @fread($fp, 16);
				@fclose($fp);
			}
			else
			{
				// If /dev/urandom isn't available (eg: in non-unix systems), use mt_rand().
				$pr_bits = "";

				for ($cnt = 0; $cnt < 16; $cnt++)
				{
					$pr_bits .= chr(mt_rand(0, 255));
				}
			}
		}

		$time_low = bin2hex(substr($pr_bits, 0, 4));
		$time_mid = bin2hex(substr($pr_bits, 4, 2));
		$time_hi_and_version = bin2hex(substr($pr_bits, 6, 2));
		$clock_seq_hi_and_reserved = bin2hex(substr($pr_bits, 8, 2));
		$node = bin2hex(substr($pr_bits, 10, 6));

		/**
		 * Set the four most significant bits (bits 12 through 15) of the
		 * time_hi_and_version field to the 4-bit version number from
		 * Section 4.1.3.
		 * @see http://tools.ietf.org/html/rfc4122#section-4.1.3
		 */
		$time_hi_and_version = hexdec($time_hi_and_version);
		$time_hi_and_version = $time_hi_and_version >> 4;
		$time_hi_and_version = $time_hi_and_version | 0x4000;

		/**
		 * Set the two most significant bits (bits 6 and 7) of the
		 * clock_seq_hi_and_reserved to zero and one, respectively.
		 */
		$clock_seq_hi_and_reserved = hexdec($clock_seq_hi_and_reserved);
		$clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved >> 2;
		$clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved | 0x8000;

		// Either return as hex or as string
		$format = $hex ? '%08s%04s%04x%04x%012s' : '%08s-%04s-%04x-%04x-%012s';

		return sprintf($format, $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $node);
	}
}
