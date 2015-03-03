<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       02.03.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
/**
 * Class CompojoomControllerMultimedia
 *
 * @since  3.0
 */
abstract class CompojoomControllerMultimedia extends JControllerLegacy
{
	/**
	 * Act with an appropriate action
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function doIt()
	{
		// Check for request forgeries
		if (!JSession::checkToken('request'))
		{
			$this->sendResponse(
				array(
					array(
						"error" => JText::_('JINVALID_TOKEN'),
						"name" => '',
						"size" => ''
					)
				)
			);

			JFactory::getApplication()->close();
		}

		$requestType = $_SERVER['REQUEST_METHOD'];
		$input = JFactory::getApplication()->input;
		$multimediaModel = $this->getModel();

		// We  have to show the available files
		if ($requestType == 'GET')
		{
			$id = $input->getInt('id');
			$files = $multimediaModel->getFiles($id);
			$this->sendResponse($files);

			JFactory::getApplication()->close();
		}

		// Handle deletes of the files
		if ($requestType == 'DELETE')
		{
			$file = JFactory::getApplication()->input->getString('file');
			$id = $input->getInt('id');

			if ($multimediaModel->delete($file, $id))
			{
				$response = array(
					$file => $multimediaModel->delete($file, $id)
				);
				echo json_encode($response);
			}
			else
			{
				$response = array(
					$file => false
				);
				echo json_encode($response);
//				// If we are here, then we are dealing with errors
//				$errors = JFactory::getApplication()->getMessageQueue();
//				$error = array_pop($errors);
//				$this->sendResponse(
//					array(
//						array(
//							"error" => $error['message'],
//							"name" => $file,
//							"size" => ''
//						)
//					)
//				);
			}



			JFactory::getApplication()->close();
		}

		// If we have a post, then we are dealing with creating files
		if ($requestType == 'POST')
		{
			$this->upload();
			JFactory::getApplication()->close();
		}
	}

	/**
	 * Handle the upload of a KML file
	 *
	 * @return void
	 */
	protected function upload()
	{
		$model = $this->getModel();
		$input = JFactory::getApplication()->input;
		$file = $input->files->get('files', '', 'array');
		$file = $file[0];
		$appl = JFactory::getApplication();
		$uploadedFile = $model->uploadTmp($file);

		if ($uploadedFile)
		{
			$this->sendResponse(
				array($uploadedFile)
			);

			return;
		}

		// If we are here, then we are dealing with errors
		$errors = $appl->getMessageQueue();
		$error = array_pop($errors);
		$this->sendResponse(
			array(
				array(
					"error" => $error['message'],
					"name" => $file['name'],
					"size" => $file['size']
				)
			)
		);

		return;
	}

	/**
	 * Echoes the response as json
	 *
	 * @param   array  $response  - array with files
	 *
	 * @return void
	 */
	private function sendResponse($response)
	{
		$response = array(
			'files' => $response
		);

		echo json_encode($response);

		return;
	}
}
