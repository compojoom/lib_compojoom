<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       08.01.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomQueryHelper
 *
 * @since  4.0.31
 */
class CompojoomQueryHelper
{
	/**
	 * Generates column IN ('a', 'b', '3')
	 *
	 * @param   string  $column  - the column name
	 * @param   array   $array   - the array to implode and escape
	 * @param   object  $db      - the db object
	 * @param   bool    $not     - Use NOT IN instead of IN
	 *
	 * @return string
	 */
	public static function in($column, $array, $db, $not = false)
	{
		$n = ($not) ? " NOT " : "";

		return $db->qn($column) . $n . ' IN (' . self::implode($array, $db) . ')';
	}

	/**
	 * Implode with value escaping
	 *
	 * @param   array   $values  - array with values to implode
	 * @param   object  $db      - the db object
	 * @param   string  $type    - the type of escaping we should do quote or quoteName (q or qn)
	 *
	 * @return string
	 */
	public static function implode($values, $db, $type = 'q')
	{
		return implode(',', array_map(
				function ($v) use ($db, $type)
				{
					return $db->$type($v);
				}, $values
			)
		);
	}

	/**
	 * Create proper array for Update query. The initial array has the form of array ('key' => 'value')
	 * the new array has the form of array( 'key = value', ...) both key and value are properly escaped
	 *
	 * @param   array   $array  - the array to format
	 * @param   object  $db     - the db object
	 *
	 * @return array
	 */
	public static function createProperArrayForUpdateQuery($array, $db)
	{
		$set = array();

		foreach ($array as $key => $value)
		{
			$set[] = $db->qn($key) . '=' . $db->q($value);
		}

		return $set;
	}
}
