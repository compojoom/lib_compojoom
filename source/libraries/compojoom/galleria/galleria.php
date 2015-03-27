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
	 * @param   int|array  $itemId     - the item id
	 * @param   string     $typeAlias  - the type of entry we are trying to load
	 * @param   bool       $json       - determines if we should return the result as json array
	 * @param   string     $small      - the small size of the image
	 * @param   string     $normal     - the normal size of the image
	 *
	 * @return mixed|string|bool - json string when we have data, false when there is no data for this item
	 */
	public static function getData($itemId, $typeAlias, $json = true,  $small = 'small', $normal='large')
	{
		$multimedia = JModelLegacy::getInstance('Multimedia', 'CompojoomModel', array('type_alias' => $typeAlias));
		$data = array();
		$itemId = (array) $itemId;

		if (!count($itemId))
		{
			return false;
		}

		$rawData = $multimedia->getFilesFromDb($itemId);

		foreach ($rawData as $row)
		{
			/**
			 * @var Joomla\Registry\Registry
			 */
			$params = new JRegistry($row->params);

			$image = array(
				'big' => $multimedia->getWebFilePath($row->mangled_filename, $row->item_id),
				'title' => $row->title,
				'description' => $row->description
			);

			if ($small)
			{
				$thumbSmall = $params->get('thumbs.' . $small, '');

				if ($thumbSmall)
				{
					$image['thumb'] = $multimedia->getWebFilePath($thumbSmall->name, $row->item_id, true);
				}
			}

			if ($normal)
			{
				$thumbNormal = $params->get('thumbs.' . $normal, '');

				if ($thumbNormal)
				{
					$image['image'] = $multimedia->getWebFilePath($thumbNormal->name, $row->item_id, true);
				}
			}

			$data[$row->item_id][] = $image;
		}

		// Now, what should we return?
		if (count($data))
		{
			// If ItemId is an array, return the whole array
			if (is_array($itemId))
			{
				return $json ? json_encode($data) : $data;
			}
			else
			{
				// Return just the itemId array
				return $json ? json_encode($data[$itemId]) : $data[$itemId];
			}
		}

		return false;
	}
}
