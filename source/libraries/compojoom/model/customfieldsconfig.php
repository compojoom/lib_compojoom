<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       23.01.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

/**
 * Class HotspotsModelHotspots
 *
 * @since  3.0
 */
class CompojoomModelCustomfieldsconfig extends JModelList
{
	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @param   string  $component  - the component that we want to get the custom fields for (e.x. com_hotspots.xxx where xxx is the item type)
	 * @param   int     $catid      - the category id
	 * @param   string  $operator   - Operator for component (defaults to =)
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 */
	public function getFields($component, $catid = null, $operator = "=")
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$cats = array();

		$query->select('f.*, c.catid as catid')->from('#__compojoom_customfields as f')
			->leftJoin('#__compojoom_customfields_cats AS c ON f.id = c.compojoom_customfields_id')
			->where($db->qn('f.show') . '=' . $db->q('all'))
			->where($db->qn('f.enabled') . ' = ' . $db->q(1));

		if ($operator == "LIKE")
		{
			$query->where($db->qn('f.component') . ' LIKE ' . $db->q($component));
		}
		else
		{
			$query->where($db->qn('f.component') . ' = ' . $db->q($component));
		}

		$all = $db->setQuery($query)->loadObjectList();

		if ($catid)
		{
			$query->clear();
			$query->select('f.*')->from('#__compojoom_customfields AS f')->where($db->qn('f.show') . '=' . $db->q('category'))
				->innerJoin('#__compojoom_customfields_cats AS c ON f.id = c.compojoom_customfields_id')
				->where(CompojoomQueryHelper::in('c.catid', is_array($catid) ? $catid : array($catid), $db))
				->where($db->qn('f.enabled') . ' = ' . $db->q(1));

			if ($operator == "LIKE")
			{
				$query->where($db->qn('f.component') . ' LIKE ' . $db->q($component));
			}
			else
			{
				$query->where($db->qn('f.component') . ' = ' . $db->q($component));
			}

			$cats = $db->setQuery($query)->loadObjectList();
		}

		if (count($cats))
		{
			$allFields = array_merge($all, $cats);
		}
		else
		{
			$allFields = $all;
		}

		// Sort by ordering
		usort(
			$allFields, function($a, $b){
				return $a->ordering - $b->ordering;
			}
		);

		foreach ($allFields as $i => $field)
		{
			// JSON decode params column
			$allFields[$i]->params = json_decode($field->params);
		}

		return $allFields;
	}
}
