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
abstract class CompojoomModelCustomfields extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'cf.id',
				'title', 'cf.title',
				'type', 'cf.type',
				'default', 'cf.default',
				'enabled', 'cf.enabled',
				'ordering', 'a.ordering'
			);

		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

		// List state information.
		parent::populateState('cf.title', 'asc');
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$query = $this->_getListQuery();

		try
		{
			$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));

			$ids = array();

			foreach ($items as $key => $value)
			{
				$ids[] = $value->id;
			}

			if (count($ids))
			{
				$catsQuery = $this->getCatRelationsQuery($ids);

				if ($catsQuery)
				{
					$categories = $this->_getList($catsQuery);

					foreach ($categories as $cat)
					{
						foreach ($items as $key => $value)
						{
							if ($value->id == $cat->compojoom_customfields_id)
							{
								$items[$key]->cats[] = $cat;
							}
						}
					}
				}
			}
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('cf.*')
			->from('#__compojoom_customfields AS cf');

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('cf.enabled = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(cf.enabled IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('cf.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('(cf.title LIKE ' . $search . ')');
			}
		}

		if ($this->getState('filter.component'))
		{
			$query->where($db->qn('cf.component') . ' LIKE ' . $db->q($this->getState('filter.component')));
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'cf.title');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Gets the category relations for the customfields
	 *
	 * @param   array  $ids  - array with ids
	 *
	 * @return JDatabaseQuery
	 */
	abstract protected function getCatRelationsQuery($ids);

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @param   int  $catid  - the category id
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 */
	public function getConfiguredFields($catid = null)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$cats = array();

		$query->select('*')->from('#__compojoom_customfields as f')
			->where($db->qn('f.show') . '=' . $db->q('all'))
			->where($db->qn('f.enabled') . ' = ' . $db->q(1));
		$all = $db->setQuery($query)->loadObjectList();

		if ($catid)
		{
			$query->clear();
			$query->select('f.*')->from('#__compojoom_customfields AS f')->where($db->qn('f.show') . '=' . $db->q('category'))
				->innerJoin('#__compojoom_customfields_cats AS c ON f.id = c.compojoom_customfields_id')
				->where($db->qn('c.catid') . ' = ' . $db->q($catid))
				->where($db->qn('f.enabled') . ' = ' . $db->q(1));
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

		return $allFields;
	}

	/**
	 * Publish / Unpublish an custom field
	 *
	 * @return  mixed
	 *
	 * @since   5.1.2
	 */
	public function publish()
	{
		$input = JFactory::getApplication()->input;
		$db    = JFactory::getDbo();

		$task = $input->getCmd('task');
		$cid  = $input->get('cid', array(), 'Array');

		$query = $db->getQuery(true);

		$enabled = ($task == 'unpublish') ? 0 : 1;

		$query
			->update('#__compojoom_customfields')
			->set('enabled = ' . $db->quote($enabled))
			->where(CompojoomQueryHelper::in('id', $cid, $db));


		$db->setQuery($query);

		return $db->execute();
	}
}
