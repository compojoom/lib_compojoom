<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       27.01.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomFormCustomfieldsUrl
 *
 * @since  4.0
 */
class CompojoomFormCustomfieldsUrl
{
	/**
	 * Generates a xml string out of the field data
	 *
	 * @param   object $data - the field row
	 *
	 * @return string
	 */
	public function xml($data)
	{
		$string = '<field type="url" name="' . $data->slug . '" default="' . $data->default . '" label="' .
			$data->title . '" required="' . ($data->allow_empty ? 'false' : 'true') . '" class="form-control"/>';

		return $string;
	}

	/**
	 * There is no need to translate the value for text fields
	 *
	 * @param   object $data             - the object with the field value
	 * @param   string $valueToTranslate - the value for the field
	 *
	 * @return string
	 */
	public function render($data, $valueToTranslate)
	{
		$uri = Juri::getInstance($valueToTranslate);

		// most probably external link if it starts with www. Add a scheme in order to properly redirect
		if (substr($uri->toString(), 0, 4) === 'www.')
		{
			$uri->setScheme('http');
		}

		// wild guess, but if the host has a .com in it, then it's not a relative url

		$displayUri = $uri->toString();
		$href =  $uri->toString();

		// If we are dealing with mailto, then output just path
		if ($uri->getScheme() === 'mailto')
		{
			$displayUri = $uri->toString(array('path'));
			$href = 'mailto:' . $uri->toString(array('path'));
		}
		// if we have a host, then output just the host
		else if ($uri->getHost())
		{
			$displayUri = $uri->toString(array('host'));
		}

		return '<a href="' . $href . '" target="_blank" rel="nofollow">' . $displayUri . '</a>';
	}
}
