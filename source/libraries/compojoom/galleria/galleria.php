<?php
/**
 * @package    lib_compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       19.03.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomModelGalleria
 *
 * @since  4.0.31
 */
class CompojoomGalleria
{
	/**
	 * This function will return a json object with image information
	 * for a galleria.io
	 *
	 * @param   int     $itemId     - the item id
	 * @param   string  $typeAlias  - the type of entry we are trying to load
	 *
	 * @return mixed|string
	 */
	public static function getData($itemId, $typeAlias, $small = 'small', $normal='large')
	{
		$multimedia = JModelLegacy::getInstance('Multimedia', 'CompojoomModel', array('type_alias' => $typeAlias));
		$data = array();
		$rawData = $multimedia->getFilesFromDb($itemId);

		foreach ($rawData as $row)
		{
			/**
			 * @var Joomla\Registry\Registry
			 */
			$params = new JRegistry($row->params);

			$image = array(
				'big' => $multimedia->getWebFilePath($row->mangled_filename, $itemId),
				'title' => $row->title,
				'description' => $row->description
			);

			if ($small)
			{
				$thumbSmall = $params->get('thumbs.' . $small, '');

				if ($thumbSmall)
				{
					$image['thumb'] = $multimedia->getWebFilePath($thumbSmall->name, $itemId, true);
				}
			}

			if ($normal)
			{
				$thumbNormal = $params->get('thumbs.' . $normal, '');

				if ($thumbNormal)
				{
					$image['image'] = $multimedia->getWebFilePath($thumbNormal->name, $itemId, true);
				}
			}

			$data[] = $image;
		}

		return json_encode($data);
	}
}