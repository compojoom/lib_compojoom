<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       11.02.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomMultimedia
 *
 * @since  4.0.31
 */
class CompojoomModelMultimedia extends JModelLegacy
{
	/**
	 * @var string
	 */
	protected $component;

	/**
	 * Constructor
	 *
	 * @param   array  $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
	 *
	 * @throws Exception
	 */
	public function __construct($config = array())
	{
		if (!isset($config['type_alias']))
		{
			throw new Exception('You need to specify a type_alias. For example: com_hotspots.hotspot');
		}

		$this->typeAlias = $config['type_alias'];

		$typeAlias = explode('.', $this->typeAlias);
		$this->component = $typeAlias[0];
		$this->contentType = $typeAlias[1];

		if (isset($config['deleteUrl']))
		{
			$this->deleteUrl = $config['deleteUrl'];
		}
		else
		{
			$this->deleteUrl = 'index.php?option=' . $this->component . '&task=multimedia.doIt&action=delete&' . JSession::getFormToken() . '=1';
		}

		parent::__construct($config);
	}

	/**
	 * Uploads the images temporary to the cache folder
	 * If the user doesn't save his entry the cron job will delete
	 * the images
	 *
	 * @param   array  $file  - the file array
	 *
	 * @return boolean
	 */
	public function uploadTmp($file)
	{
		$appl = JFactory::getApplication();

		if (isset($file['name']))
		{
			JLoader::import('joomla.filesystem.file');
			$user = JFactory::getUser();
			$canUpload = $user->authorise('core.multimedia.create', $this->component);

			$mediaHelper = new JHelperMedia;

			// Some cameras just add whitespace, let's change this
			$file['name'] = str_replace(' ', '_', $file['name']);

			// The user doesn't seem to have upload privilegies
			if (!$canUpload)
			{
				$appl->enqueueMessage(JText::_('LIB_COMPOJOOM_YOU_DONT_HAVE_UPLOAD_PRIVILEGES'));

				return false;
			}

			// Check if we pass all other checks
			if (!$mediaHelper->canUpload($file, $this->component))
			{
				return false;
			}

			// Get a (very!) randomised name
			$serverkey = JFactory::getConfig()->get('secret', '');

			$sig = microtime() . $serverkey;

			if (function_exists('sha256'))
			{
				$mangledname = sha256($sig);
			}
			elseif (function_exists('sha1'))
			{
				$mangledname = sha1($sig);
			}
			else
			{
				$mangledname = md5($sig);
			}

			$mangledname .= '_' . $file['name'];

			// ...and its full path
			$filepath = JPath::clean($this->getFilePath() . $mangledname);

			// If we have a name clash, abort the upload
			if (JFile::exists($filepath))
			{
				$appl->enqueueMessage(JText::_('LIB_COMPOJOOM_ATTACHMENTS_ERR_NAMECLASH'));

				return false;
			}

			// Do the upload

			if (!JFile::upload($file['tmp_name'], $filepath))
			{
				$appl->enqueueMessage(JText::_('LIB_COMPOJOOM_ATTACHMENTS_ERR_CANTJFILEUPLOAD'));

				return false;
			}

			// Get the MIME type
			if (function_exists('mime_content_type'))
			{
				$mime = mime_content_type($filepath);
			}
			elseif (function_exists('finfo_open'))
			{
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $filepath);
			}
			else
			{
				$mime = 'application/octet-stream';
			}

			// Create a temporary thumb file
			$image = new JImage($filepath);

			$thumbs = $image->createThumbs('60x80');

			$imageData = base64_encode(file_get_contents($thumbs[0]->getPath()));

			// Now remove the thumb
			JFile::delete($thumbs[0]->getPath());

			// Format the image SRC:  data:{mime};base64,{data};
			$src = 'data: ' . $mime . ';base64,' . $imageData;

			// Return the file info
			$fileData = array(
				'name' => $mangledname,
				'thumbnailUrl' => $src,
				'size' => $file['size'],
				'type' => $file['type'],
				'deleteType' => 'delete',
				'url' => '',
				'deleteUrl' => $this->deleteUrl . '&file=' . $mangledname
			);

			return $fileData;
		}
		else
		{
			$appl->enqueueMessage(JText::_('LIB_COMPOJOOM_ATTACHMENTS_ERR_NOFILE'));

			return false;
		}
	}

	/**
	 * Move the images from their temporary location to their final location
	 *
	 * @param   int    $itemId  - the item id
	 * @param   array  $files   - the files to save
	 *
	 * @return boolean
	 */
	public function uploadPermanent($itemId, $files)
	{
		$dbFiles = $this->getFilesFromDb($itemId, 'mangled_filename');

		// If the file is already in the Database we don't have to manipulate it again
		foreach ($files as $key => $file)
		{
			if (isset($dbFiles[$file]))
			{
				unset($files[$key]);
			}
		}

		$moved = $this->permanentlyMoveFiles($itemId, $files);
		$this->saveInDb($itemId, $moved);
	}

	/**
	 * Permanently moves a file from the temp location to the final location.
	 * Creates all necessary thumbs and ads the information to the database
	 *
	 * @param   int    $itemId  - the item id
	 * @param   array  $files   - array with filename to save
	 *
	 * @return array
	 */
	private function permanentlyMoveFiles($itemId, $files)
	{
		$status = array();
		$params = JComponentHelper::getParams($this->component);
		$destFolder = JPath::clean($this->getFilePath('', $itemId));


		// If the folder doesn't exist, let's crete it first
		if (!JFolder::exists($destFolder))
		{
			JFolder::create($destFolder);
		}

		// Now let's move the files
		foreach ($files as $file)
		{
			$tmpLocation = JPath::clean($this->getFilePath($file, ''));
			$newLocation = JPath::clean($destFolder . $file);

			$status[$file]['status'] = JFile::move($tmpLocation, $newLocation);

			// Now let's create some thumbs
			if ($status[$file]['status'])
			{
				$sizes = explode("\n", $params->get('thumb_sizes', '60x80'));

				// Push one more size for the thumbs in the edit screen
				if (!in_array('60x80', $sizes))
				{
					$sizes[] = '60x80';
				}

				$image = new CompojoomImage($newLocation);
				$status[$file]['thumbs'] = $image->createThumbs($sizes);
				$status[$file]['properties'] = $image->getImageFileProperties($newLocation);
			}
		}

		return $status;
	}

	/**
	 * Get the already stored files in the database
	 *
	 * @param   int     $id   - the item id
	 * @param   string  $key  - the sort key
	 *
	 * @return mixed
	 */
	public function getFilesFromDb($id, $key = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')->from('#__compojoom_multimedia')
			->where('item_id = ' . $db->q($id))
			->where('type_alias = ' . $db->q($this->typeAlias));

		$db->setQuery($query);

		return $db->loadObjectList($key);
	}

	/**
	 * Save the files paths and info into the database
	 *
	 * @param   int    $itemId  - the item id
	 * @param   array  $files   - the files array
	 *
	 * @return void
	 */
	private function saveInDb($itemId, $files)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$values = array();

		foreach ($files as $key => $value)
		{
			if ($value['status'])
			{
				$params = array();

				if ($value['thumbs'])
				{
					foreach ($value['thumbs'] as $tkey => $tvalue)
					{
						$params['thumbs'][$tkey] = array(
							'name' => basename($tvalue->getPath())
						);
					}
				}

				$params = new JRegistry($params);

				$values[] = implode(
					',',
					array(
						$db->q($itemId),
						$db->q($this->typeAlias),
						$db->q($key),
						$db->q($value['properties']->mime),
						$db->q('web'),
						$db->q(JFactory::getDate()->toSql()),
						$db->q(JFactory::getUser()->get('id')),
						$db->q(1),
						$db->q($params->toString())
					)
				);
			}
		}

		if (count($values))
		{
			$query->insert('#__compojoom_multimedia')
				->columns('item_id, type_alias, mangled_filename, mime_type, origin, created_on, created_by, enabled, params')
				->values($values);

			$db->setQuery($query);

			$db->execute();
		}
	}

	/**
	 * Deletes a file from the disk and from the database
	 *
	 * @param   string  $file  - the filename
	 * @param   int     $id    - the userid
	 *
	 * @return bool
	 */
	public function delete($file, $id)
	{
		if ($id)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$user = JFactory::getUser();
			$createdByRestriction = false;

			// Can the user edit his own items?
			if ($user->authorise('core.multimedia.delete.own', $this->component))
			{
				$createdByRestriction = true;
			}

			// Does the user have global delete privileges
			if ($user->authorise('core.multimedia.delete', $this->component))
			{
				$createdByRestriction = false;
			}

			// First we need to grab the image since we need to make sure to delete the thumbnails as well
			$query->select('*')->from('#__compojoom_multimedia')->where('mangled_filename = ' . $db->q($file))
				->where('type_alias = ' . $db->q($this->typeAlias));

			$db->setQuery($query);

			$image = $db->loadObject();

			if ($image)
			{
				// The image should be created by the same user who tries to delete it
				if ($createdByRestriction)
				{
					if ($image->created_by != $user->id)
					{
						JFactory::getApplication()->enqueueMessage('LIB_COMPOJOOM_NOT_AUTORISED_TO_DELETE');

						return false;
					}
				}

				$params = new JRegistry($image->params);
				$thumbs = $params->get('thumbs');

				// Delete the thumbs first
				foreach ($thumbs as $thumb)
				{
					JFile::delete($this->getFilePath($thumb->name, $id, true));
				}

				// Now let's delete the db entry and the file
				$query->clear();
				$query->delete('#__compojoom_multimedia')->where('mangled_filename = ' . $db->q($file))
					->where('created_by = ' . $db->q(JFactory::getUser()->id));

				$db->setQuery($query);

				if ($db->execute())
				{
					return JFile::delete($this->getFilePath($file, $id));
				}
			}

			return false;
		}

		// So, we are dealing with temp File?
		return JFile::delete($this->getFilePath($file, $id));
	}

	/**
	 * Gets the filepath to a file
	 *
	 * @param   string  $file     - the file name
	 * @param   int     $id       - the item id
	 * @param   bool    $isThumb  - are we dealing with a thumb?
	 *
	 * @return string
	 */
	public function getFilePath($file = '', $id = 0, $isThumb = false)
	{
		$params = JComponentHelper::getParams($this->component);

		if ($id)
		{
			$path = JPATH_ROOT . '/' . $params->get('image_path', 'images') . '/' . $this->typeAlias;

			if ($id)
			{
				$path .= '/' . $id;
			}

			if ($isThumb)
			{
				$path .= '/thumbs';
			}
		}
		else
		{
			// If we don't have an ID, then we are dealing with the cache
			$path = JPATH_ROOT . '/' . $params->get('tmp_file_path', 'cache') . '/lib_compojoom.multimedia';
		}


		return JPath::clean($path . '/' . $file);
	}

	/**
	 * Get the web path to a file
	 *
	 * @param   string  $file     - the file name
	 * @param   int     $id       - the id
	 * @param   bool    $isThumb  - are we dealing with a thumb
	 *
	 * @return string
	 */
	public function getWebFilePath($file, $id, $isThumb = false)
	{
		$params = JComponentHelper::getParams($this->component);
		$path = Juri::root() . $params->get('image_path', 'images') . '/' . $this->typeAlias . '/' . $id;

		if ($isThumb)
		{
			$path .= '/thumbs';
		}

		$path .= '/' . $file;

		return $path;
	}

	/**
	 * Get all files for an item. If we don't pass an item id, then we are trying to find
	 * if we have information about the files in the user state
	 *
	 * @param   int  $id  - the item id
	 *
	 * @return array
	 */
	public function getFiles($id)
	{
		$app = JFactory::getApplication();
		$files = array();

		if ($id)
		{
			$dbFiles = $this->getFilesFromDb($id);

			foreach ($dbFiles as $file)
			{
				$params = new JRegistry($file->params);
				$thumbs = $params->get('thumbs');
				$size = '60x80';
				$web = $this->getWebFilePath($thumbs->$size->name, $id, true);
				$url = $this->getWebFilePath($file->mangled_filename, $id);
				$fileSize = filesize($this->getFilePath($file->mangled_filename, $id));

				$deleteUrl = $this->deleteUrl . '&file=' . $file->mangled_filename . '&id=' . $id;
				$files[] = $this->fileArray($file->mangled_filename, $web, $fileSize, $file->mime_type, $url, $deleteUrl);
			}
		}
		else
		{
			$state = $app->getUserState($this->context);

			if (isset($state[$this->fieldName]))
			{
				foreach ($state[$this->fieldName] as $file)
				{
					$path = $this->getFilePath($file, '');

					// Create a temporary thumb file
					$image = new JImage($this->getFilePath($file, ''));
					$mime = $image->getImageFileProperties($path)->mime;
					$thumbs = $image->createThumbs('60x80');
					$imageData = base64_encode(file_get_contents($thumbs[0]->getPath()));

					// Format the image SRC:  data:{mime};base64,{data};
					$src = 'data: ' . $mime . ';base64,' . $imageData;

					// Return the file info
					$files[] = $this->fileArray($file, $src, filesize($path), $mime, '', '');

					// Now remove the thumb
					JFile::delete($thumbs[0]->getPath());
					$image->destroy();
				}
			}
		}

		return $files;
	}

	/**
	 * Create an array with file information for json output
	 *
	 * @param   string  $fileName      - the file name
	 * @param   string  $thumbnailUrl  - the thumbnail url
	 * @param   string  $size          - the size of the file
	 * @param   string  $type          - the type of the file
	 * @param   string  $url           - the url
	 * @param   string  $deleteUrl     - the delete url for this item
	 * @param   string  $deleteType    - the delete type
	 *
	 * @return array
	 */
	private function fileArray($fileName, $thumbnailUrl, $size, $type, $url, $deleteUrl, $deleteType = 'delete')
	{
		return array(
			'name' => $fileName,
			'thumbnailUrl' => $thumbnailUrl,
			'size' => $size,
			'type' => $type,
			'deleteType' => $deleteType,
			'url' => $url,
			'deleteUrl' => $deleteUrl
		);
	}
}
