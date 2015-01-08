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
	 *
	 * @return string
	 */
	public static function in($column, $array, $db)
	{
		return $db->qn($column) . ' IN (' .
					implode(',', array_map(
							function($v) use ($db){
								return $db->q($v);
							}, $array
						)
					) .
		')';
	}
}
