<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       10.02.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Class JFormFieldCMultimedia
 *
 * @since  4.0.31
 */
class JFormFieldCMultimedia extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'cmultimedia';

	/**
	 * Get's the input
	 *
	 * @return mixed
	 */
	protected function getInput()
	{
		$params = JComponentHelper::getParams((string) $this->element['component']);
		$layout = new CompojoomLayoutFile('fileupload.fileupload');
		$maxNumberOfFiles = $params->get('max_number_of_files', 10);
		$html = $layout->render(
			array(
				'url' => (string)$this->element['url'],
				'formControl' => $this->formControl,
				'fieldName' => $this->fieldname,
				'maxNumberOfFiles' => $maxNumberOfFiles,
				'fileTypes' => $params->get('image_extensions'),
				'maxSize' => $params->get('upload_maxsize'),
				'component' => (string)$this->element['component']
			)
		);

		return $html;
	}

	/**
	 * Make sure that the current user has the sufficient privilegies
	 *
	 * @return string
	 */
	protected function getLabel()
	{
		$user = JFactory::getUser();

		if (!$user->authorise('core.multimedia.create', (string) $this->element['component']))
		{
			return '';
		}

		return parent::getLabel();
	}
}
