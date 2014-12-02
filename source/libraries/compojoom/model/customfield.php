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

jimport('joomla.application.component.modeladmin');

/**
 * Class HotspotsModelHotspots
 *
 * @since  3.0
 */
abstract class CompojoomModelCustomfield extends JModelAdmin
{
	/**
	 * Loads a row from the db & adds the catid array
	 *
	 * @param   int  $pk  - the row id
	 *
	 * @return mixed
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		if ($item)
		{
			// Let us load the categories
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('catid')->from('#__compojoom_customfields_cats')
				->where('compojoom_customfields_id' . '=' . $db->q($item->id));
			$db->setQuery($query);

			$item->catid = $db->loadColumn();
		}

		return $item;
	}



	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 */
	public function getTable($type = 'Customfield', $prefix = 'CompojoomTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Saves the manually set order of records.
	 *
	 * @param   array    $pks    An array of primary key ids.
	 * @param   integer  $order  +1 or -1
	 *
	 * @return  mixed
	 */
	public function saveorder($pks = null, $order = null)
	{
		$table = $this->getTable();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
		}

		// Update ordering values
		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);

			// Access checks.
			if (!$this->canEditState($table))
			{
				// Prune items that you can't change.
				unset($pks[$i]);
				JLog::add(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
			}
			elseif ($table->ordering != $order[$i])
			{
				$table->ordering = $order[$i];

				if (!$table->store())
				{
					$this->setError($table->getError());

					return false;
				}
			}
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Executes after the row is saved and saves the category relationship info
	 *
	 * @param   JModelLegacy  $model  - the model
	 * @param   array         $data   - the valid data
	 *
	 * @return bool
	 */
	public function onAfterSave($model, $data)
	{
		$db = JFactory::getDbo();
		$cats = $data['catid'];
		$item = $model->getItem();

		/**
		 * Let's wipe out the customfield-category table
		 * I always have to do that, otherwise I could have some problems when
		 * a field changes from 'all' to 'category'
		 */
		$query = $db->getQuery(true)
			->delete('#__compojoom_customfields_cats')
			->where('compojoom_customfields_id = ' . $item->id);

		if (!$db->setQuery($query)->execute())
		{
			$this->setError(JText::_('COM_HOTSPOTS_CUSTOMFIELDS_ERR_DELETE_CATS'));

			return false;
		}

		if ($cats && $data['show'] == 'category')
		{
			$query = $db->getQuery(true)
				->insert('#__compojoom_customfields_cats')
				->columns('compojoom_customfields_id, catid');

			foreach ($cats as $cat)
			{
				if (!$cat)
				{
					continue;
				}

				$query->values($item->id . ', ' . $cat);
			}

			if (!$db->setQuery($query)->execute())
			{
				$this->setError(JText::_('COM_HOTSPOTS_CUSTOMFIELDS_ERR_INSERT_CATS'));

				return false;
			}
		}

		return true;
	}
}
