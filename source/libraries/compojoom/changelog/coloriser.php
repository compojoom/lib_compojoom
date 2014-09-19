<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       17.09.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Based on the TxChangelogColoriser class in twentronix's cookie confirm
 *
 * @link https://www.twentronix.com
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomChangelogColoriser
 *
 * @since  4.0
 */
class CompojoomChangelogColoriser
{
	/**
	 * Colorises a changelog file
	 *
	 * @param   string  $file      - path to the file
	 * @param   bool    $onlyLast  - show only the info about the latest release
	 *
	 * @return string
	 */
	public static function colorise($file, $onlyLast = false)
	{
		$html = '';

		$lines = @file($file);

		if (empty($lines))
		{
			return $html;
		}

		array_shift($lines);

		foreach ($lines as $line)
		{
			$line = trim($line);

			if (empty($line))
			{
				continue;
			}

			$type = substr($line, 0, 1);

			switch ($type)
			{
				case '=':
					continue;
					break;

				case '*':
					$html .= "\t" . '<li class="securityfixed"><span class="label label-critical">Fix</span>' . htmlentities(trim(substr($line, 2))) . "</li>\n";
					break;

				case '#':
					$html .= "\t" . '<li class="fixed"><span class="label label-info">Fix</span>' . htmlentities(trim(substr($line, 2))) . "</li>\n";
					break;

				case '$':
					$html .= "\t" . '<li class="language"><span class="label label-purple">Lang</span>' . htmlentities(trim(substr($line, 2))) . "</li>\n";
					break;

				case '+':
					$html .= "\t" . '<li class="added"><span class="label label-success">New</span>' . htmlentities(trim(substr($line, 2))) . "</li>\n";
					break;

				case '^':
					$html .= "\t" . '<li class="changed"><span class="label label-inverse">Diff</span>' . htmlentities(trim(substr($line, 2))) . "</li>\n";
					break;

				case '~':
					$html .= "\t" . '<li class="changedmisc"><span class="label">Diff</span>' . htmlentities(trim(substr($line, 2))) . "</li>\n";
					break;

				case '-':
					$html .= "\t" . '<li class="removed"><span class="label label-important">Del</span>' . htmlentities(trim(substr($line, 2))) . "</li>\n";
					break;

				case '!':
					$html .= "\t" . '<li class="note"><span class="label label-warning">Note</span>' . htmlentities(trim(substr($line, 2))) . "</li>\n";
					break;

				default:
					if (!empty($html))
					{
						$html .= "</ul>";

						if ($onlyLast)
						{
							return $html;
						}
					}

					if (!$onlyLast)
					{
						$html .= "<h3>" . preg_replace('#- Released.*#', '<span>$0</span>', $line) . "</h3>\n";
					}

					$html .= "<ul>\n";
					break;
			}
		}

		$html .= "</ul>";

		return $html;
	}
}
