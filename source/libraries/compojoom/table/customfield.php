<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       02.12.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomTableCustomfield
 *
 * @since  4
 */
class CompojoomTableCustomfield extends JTable
{
	/**
	 * The constructor
	 *
	 * @param   JDatabaseDriver  &$db  - the db object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__compojoom_customfields', 'id', $db);
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table. The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		// Sanitize input.
		\Joomla\Utilities\ArrayHelper::toInteger($pks);
		$state = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Update the publishing state for rows with the given primary keys.
		$query = $this->_db->getQuery(true)
			->update($this->_db->quoteName($this->_tbl))
			->set($this->_db->quoteName('enabled') . ' = ' . (int) $state)
			->where('(' . $where . ')');
		$this->_db->setQuery($query);

		try
		{
			$this->_db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');

		return true;
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see     JTable::check()
	 * @since   11.1
	 */
	public function check()
	{
		if (trim($this->slug) == '')
		{
			$this->slug = $this->title;
		}

		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$this->slug = JApplicationHelper::stringURLSafe($this->slug);
		}
		else
		{
			$this->slug = JFactory::getApplication()->stringURLSafe($this->slug);
		}

		if (trim(str_replace('-', '', $this->slug)) == '')
		{
			$this->slug = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		return true;
	}

	/**
	 * Overrides JTable::store to set modified data and user id.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ($this->id)
		{
			// Existing item
			$this->modified_on = $date->toSql();
			$this->modified_by = $user->get('id');
		}
		else
		{
			// New article. An article created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created_on)
			{
				$this->created_on = $date->toSql();
			}

			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}

		// Verify that the slug is unique
		$table = JTable::getInstance('Customfield', 'CompojoomTable');

		if ($table->load(array('slug' => $this->slug)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_ARTICLE_UNIQUE_slug'));

			return false;
		}

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded bind function
	 *
	 * @param   array   $array   - named array
	 * @param   string  $ignore  - Ignore
	 *
	 * @return  null|string     null is operation was satisfactory, otherwise returns an error
	 *
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded load function
	 *
	 * @param    int      $pk     - primary key
	 * @param    boolean  $reset  - reset data
	 *
	 * @return  boolean
	 * @see JTable:load
	 */
	public function load($pk = null, $reset = true)
	{
		if (parent::load($pk, $reset))
		{
			// Convert the params field to a registry.
			$params = new JRegistry;
			$params->loadString($this->params, 'JSON');
			$this->params = $params;

			return true;
		}
		else
		{
			return false;
		}
	}
}
