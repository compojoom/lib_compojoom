<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       23.01.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Compojoom\Library\Controller;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

\defined('_JEXEC') or die('Restricted access');

/**
 * Class HotspotsControllerCustomfield
 *
 * @since  4.0
 */
class CustomfieldController extends FormController
{
	/**
	 * We need to save the category references
	 *
	 * @param   BaseDatabaseModel $model      - On 2.5 JModel, on > 3.0 JModelLegacy
	 * @param   array                $validData  - the valid data
	 *
	 * @return boolean
	 */
	protected function postSaveHook(BaseDatabaseModel $model, $validData = [])
	{
		return $model->onAfterSave($model, $validData);
	}
}
