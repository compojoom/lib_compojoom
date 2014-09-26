<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       04.11.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomInstaller
 *
 * @since  1.0
 */
class CompojoomInstaller
{
	/**
	 * The minimum PHP version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumPHPVersion = '5.3.3';

	/**
	 * Obsolete files and folders to remove from both paid and free releases. This is used when you refactor code and
	 * some files inevitably become obsolete and need to be removed.
	 *
	 * @var   array
	 */
	protected $removeFilesAllVersions = array(
		'files'   => array(
			// Use pathnames relative to your site's root, e.g.
			// 'administrator/components/com_foobar/helpers/whatever.php'
		),
		'folders' => array(
			// Use pathnames relative to your site's root, e.g.
			// 'administrator/components/com_foobar/baz'
		)
	);

	/**
	 * Constructor
	 *
	 * @param   string                      $type     - the installation type
	 * @param   JInstallerAdapterComponent  $parent   - the parent object of the JInstaller
	 * @param   string                      $extName  - the extension name
	 */
	public function __construct($type, $parent, $extName)
	{
		$this->type = $type;
		$this->parent = $parent;

		// Load the library lang files
		if ($type == 'uninstall')
		{
			CompojoomLanguage::load('lib_compojoom', JPATH_ROOT);
			CompojoomLanguage::load('lib_compojoom.sys', JPATH_ROOT);

			// Now les us load the extension files
			CompojoomLanguage::load($extName, JPATH_ADMINISTRATOR);
			CompojoomLanguage::load($extName . '.sys', JPATH_ADMINISTRATOR);
		}
		else
		{
			CompojoomLanguage::load('lib_compojoom', $parent->getParent()->getPath('source') . '/libraries/compojoom');
			CompojoomLanguage::load('lib_compojoom.sys', $parent->getParent()->getPath('source') . '/libraries/compojoom');

			// Now les us load the extension files
			CompojoomLanguage::load($extName, $parent->getParent()->getPath('source') . '/administrator');
			CompojoomLanguage::load($extName . '.sys', $parent->getParent()->getPath('source') . '/administrator');
		}

		// Since Joomla translates the message before it has loaded the correct lang files
		// let us translate themessage again
		$manifest = $parent->getParent()->getManifest();
		$parent->getParent()->set('message', JText::_((string) $manifest->description));

		$this->addCss();
	}

	/**
	 * This function ads the necessary CSS for the installation
	 *
	 * @return void
	 */
	private function addCss()
	{
		$document = JFactory::getDocument();
		$document->addStyleDeclaration(".compojoom-info {
						background-color: #D9EDF7;
					    border-color: #BCE8F1;
					    color: #3A87AD;
					    border-radius: 4px 4px 4px 4px;
					    padding: 8px 35px 8px 14px;
					    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
					    margin-bottom: 18px;
					}");
	}

	/**
	 * Install libraries
	 *
	 * @param   array  $libraries  - libraries to install
	 *
	 * @return array
	 */
	public function installLibraries($libraries)
	{
		$src = $this->parent->getParent()->getPath('source');

		$db = JFactory::getDbo();
		$status = array();

		foreach ($libraries as $library => $published)
		{
			$path = $src . "/libraries/$library";

			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__extensions')
				->where($db->qn('element') . '=' . $db->q($library))
				->where($db->qn('type') . '=' . $db->q('library'));

			$db->setQuery($query);
			$object = $db->loadObject();

			$installer = new JInstaller;

			// If we don't have an object, let us install the library
			if (!$object)
			{
				$result = $installer->install($path);
				$status[] = array('name' => $library, 'result' => $result);
			}
			else
			{
				$manifest = simplexml_load_file($path . '/' . $library . '.xml');
				$manifestCache = json_decode($object->manifest_cache);

				if (version_compare($manifest->version, $manifestCache->version, '>='))
				{
					// Okay, the library with the extension is newer, we need to install it
					$result = $installer->install($path);
					$status[] = array('name' => $library, 'result' => $result);
				}
				else
				{
					$status[] = array('name' => $library, 'result' => false,
						'message' => 'No need to install the library. You are already running a newer version of the library: ' . $manifestCache->version);
				}
			}
		}

		return $status;
	}


	/**
	 * Installs modules that come with the package
	 *
	 * @param   array  $modulesToInstall  - modues that need to be installed
	 *
	 * @return array
	 */
	public function installModules($modulesToInstall)
	{
		$src = $this->parent->getParent()->getPath('source');
		$status = array();

		// Modules installation
		if (count($modulesToInstall))
		{
			foreach ($modulesToInstall as $folder => $modules)
			{
				if (count($modules))
				{
					foreach ($modules as $module => $modulePreferences)
					{
						// Install the module
						if (empty($folder))
						{
							$folder = 'site';
						}

						$path = "$src/modules/$module";

						if ($folder == 'admin')
						{
							$path = "$src/administrator/modules/$module";
						}

						if (!is_dir($path))
						{
							continue;
						}

						$db = JFactory::getDbo();

						// Was the module alrady installed?
						$query = $db->getQuery('true');
						$query->select('COUNT(*)')->from($db->qn('#__modules'))
							->where($db->qn('module') . '=' . $db->q($module));
						$db->setQuery($query);

						$count = $db->loadResult();

						$installer = new JInstaller;
						$result = $installer->install($path);
						$status[] = array('name' => $module, 'client' => $folder, 'result' => $result);

						// Modify where it's published and its published state
						if (!$count)
						{
							list($modulePosition, $modulePublished) = $modulePreferences;
							$query->clear();
							$query->update($db->qn('#__modules'))->set($db->qn('position') . '=' . $db->q($modulePosition));

							if ($modulePublished)
							{
								$query->set($db->qn('published') . '=' . $db->q(1));
							}

							$query->set($db->qn('params') . '=' . $db->q($installer->getParams()));
							$query->where($db->qn('module') . '=' . $db->q($module));
							$db->setQuery($query);
							$db->execute();
						}

						// Get module id
						$query->clear();
						$query->select('id')->from($db->qn('#__modules'))
							->where($db->qn('module') . '=' . $db->q($module));
						$db->setQuery($query);

						$moduleId = $db->loadObject()->id;

						$query->clear();
						$query->select('COUNT(*) as count')->from($db->qn('#__modules_menu'))
							->where($db->qn('moduleid') . '=' . $db->q($moduleId));

						$db->setQuery($query);

						if (!$db->loadObject()->count)
						{
							// Insert the module on all pages, otherwise we can't use it
							$query->clear();
							$query->insert($db->qn('#__modules_menu'))
								->columns($db->qn('moduleid') . ',' . $db->qn('menuid'))
								->values($db->q($moduleId) . ' , ' . $db->q('0'));
							$db->setQuery($query);
							$db->execute();
						}
					}
				}
			}
		}

		return $status;
	}

	/**
	 * Uninstalls the given modules
	 *
	 * @param   array  $modulesToUninstall  - modues to uninstall
	 *
	 * @return array
	 */
	public function uninstallModules($modulesToUninstall = array())
	{
		$status = array();

		if (count($modulesToUninstall))
		{
			$db = JFactory::getDbo();

			foreach ($modulesToUninstall as $folder => $modules)
			{
				if (count($modules))
				{
					foreach ($modules as $module => $modulePreferences)
					{
						// Find the module ID
						$query = $db->getQuery(true);
						$query->select('extension_id')->from('#__extensions')->where($db->qn('element') . '=' . $db->q($module))
							->where($db->qn('type') . '=' . $db->q('module'));
						$db->setQuery($query);

						$id = $db->loadResult();

						// Uninstall the module
						if ($id)
						{
							$installer = new JInstaller;
							$result = $installer->uninstall('module', $id, 1);
							$status[] = array('name' => $module, 'client' => $folder, 'result' => $result);
						}
					}
				}
			}
		}

		return $status;
	}

	/**
	 * Install plugins
	 *
	 * @param   array  $plugins  - plugins to install
	 *
	 * @return array
	 */
	public function installPlugins($plugins)
	{
		$src = $this->parent->getParent()->getPath('source');

		$db = JFactory::getDbo();
		$status = array();

		foreach ($plugins as $plugin => $published)
		{
			$parts = explode('_', $plugin);
			$pluginType = $parts[1];
			$pluginName = $parts[2];

			$path = $src . "/plugins/$pluginType/$pluginName";

			$query = $db->getQuery(true);
			$query->select('COUNT(*)')
				->from('#__extensions')
				->where($db->qn('element') . '=' . $db->q($pluginName))
				->where($db->qn('folder') . '=' . $db->q($pluginType));

			$db->setQuery($query);
			$count = $db->loadResult();

			$installer = new JInstaller;
			$result = $installer->install($path);
			$status[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);

			// If the plugin was not unpublished by the user, enable it
			if ($published && !$count)
			{
				$query->clear();
				$query->update('#__extensions')
					->set($db->qn('enabled') . '=' . $db->q(1))
					->where($db->qn('element') . '=' . $db->q($pluginName))
					->where($db->qn('folder') . '=' . $db->q($pluginType));
				$db->setQuery($query);
				$db->execute();
			}
		}

		return $status;
	}

	/**
	 * Uninstall modules
	 *
	 * @param   array  $plugins  - plugins to uninstall
	 *
	 * @return array
	 */
	public function uninstallPlugins($plugins)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$status = array();

		foreach ($plugins as $plugin => $published)
		{
			$parts = explode('_', $plugin);
			$pluginType = $parts[1];
			$pluginName = $parts[2];
			$query->clear();
			$query->select('extension_id')->from($db->qn('#__extensions'))
				->where($db->qn('type') . '=' . $db->q('plugin'))
				->where($db->qn('element') . '=' . $db->q($pluginName))
				->where($db->qn('folder') . '=' . $db->q($pluginType));
			$db->setQuery($query);

			$id = $db->loadResult();

			if ($id)
			{
				$installer = new JInstaller;
				$result = $installer->uninstall('plugin', $id, 1);
				$status[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);
			}
		}

		return $status;
	}

	/**
	 * Gets a param value out of the manifest cache for this extension
	 *
	 * @param   string  $name     - the name of the param we are looking for
	 * @param   string  $element  - the extension name
	 * @param   string  $type     - the type of the extension
	 * @param   string  $folder   - the folder (if plugin)
	 *
	 * @return mixed - the parameter value when found. False when the parameter doesn't exist
	 */
	public function getParam($name, $element, $type = 'component', $folder = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery('true');
		$query->select($db->qn('manifest_cache'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type') . '=' . $db->q($type))
			->where($db->qn('element') . '=' . $db->q($element));

		if ($folder)
		{
			$query->where($db->qn('folder') . '=' . $db->q($folder));
		}

		$manifest = json_decode($db->loadResult(), true);

		return isset($manifest[$name]) ? $manifest['name'] : false;
	}

	/**
	 * Render the module information
	 *
	 * @param   array  $modules  - modules to render information for
	 *
	 * @return string
	 */
	public function renderModuleInfoInstall($modules)
	{
		$rows = 0;

		$html = array();

		if (count($modules))
		{
			$html[] = '<table class="table">';
			$html[] = '<tr>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_MODULE') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_MODULE_CLIENT') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_STATUS') . '</th>';
			$html[] = '</tr>';

			foreach ($modules as $module)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $module['name'] . '</td>';
				$html[] = '<td class="key">' . ucfirst($module['client']) . '</td>';
				$html[] = '<td>';
				$html[] = '<span style="color:' . (($module['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($module['result']) ? JText::_('LIB_COMPOJOOM_MODULE_INSTALLED') : JText::_('LIB_COMPOJOOM_MODULE_NOT_INSTALLED');
				$html[] = '</span>';
				$html[] = '</td>';
				$html[] = '</tr>';
			}

			$html[] = '</table>';
		}


		return implode('', $html);
	}

	/**
	 * Renders uninstall info for modules
	 *
	 * @param   array  $modules  - the modules to render uninstall info for
	 *
	 * @return string
	 */
	public function renderModuleInfoUninstall($modules)
	{
		$rows = 0;
		$html = array();

		if (count($modules))
		{
			$html[] = '<table class="table">';
			$html[] = '<tr>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_MODULE') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_MODULE_CLIENT') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_STATUS') . '</th>';
			$html[] = '</tr>';

			foreach ($modules as $module)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $module['name'] . '</td>';
				$html[] = '<td class="key">' . ucfirst($module['client']) . '</td>';
				$html[] = '<td>';
				$html[] = '<span style="color:' . (($module['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($module['result']) ? JText::_('LIB_COMPOJOOM_MODULE_UNINSTALLED') : JText::_('LIB_COMPOJOOM_MODULE_COULD_NOT_UNINSTALL');
				$html[] = '</span>';
				$html[] = '</td>';
				$html[] = '</tr>';
			}

			$html[] = '</table>';
		}

		return implode('', $html);
	}

	/**
	 * Renders information for the installed libraries
	 *
	 * @param   array  $libraries  - array with libraries
	 *
	 * @return string
	 */
	public function renderLibraryInfoInstall($libraries)
	{
		$rows = 0;
		$html[] = '<table class="table">';

		if (count($libraries))
		{
			$html[] = '<tr>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_LIBRARY') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_STATUS') . '</th>';
			$html[] = '</tr>';

			foreach ($libraries as $library)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $library['name'] . '</td>';
				$html[] = '<td>';
				$html[] = '<span style="color: ' . (($library['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($library['result']) ? JText::_('LIB_COMPOJOOM_LIBRARY_INSTALLED') : JText::_('LIB_COMPOJOOM_LIBRARY_NOT_INSTALLED');
				$html[] = '</span>';

				if (isset($library['message']))
				{
					$html[] = ' (' . $library['message'] . ')';
				}

				$html[] = '</td>';
				$html[] = '</tr>';
			}
		}

		$html[] = '</table>';

		return implode('', $html);
	}

	/**
	 * Renders information for the installed plugin
	 *
	 * @param   array  $plugins  - array with plugins
	 *
	 * @return string
	 */
	public function renderPluginInfoInstall($plugins)
	{
		$rows = 0;
		$html[] = '<table class="table">';

		if (count($plugins))
		{
			$html[] = '<tr>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_PLUGIN') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_PLUGIN_GROUP') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_STATUS') . '</th>';
			$html[] = '</tr>';

			foreach ($plugins as $plugin)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $plugin['name'] . '</td>';
				$html[] = '<td class="key">' . ucfirst($plugin['group']) . '</td>';
				$html[] = '<td>';
				$html[] = '<span style="color: ' . (($plugin['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($plugin['result']) ? JText::_('LIB_COMPOJOOM_PLUGIN_INSTALLED') : JText::_('LIB_COMPOJOOM_PLUGIN_NOT_INSTALLED');
				$html[] = '</span>';
				$html[] = '</td>';
				$html[] = '</tr>';
			}
		}

		$html[] = '</table>';

		return implode('', $html);
	}

	/**
	 * Render uninstall info for plugins
	 *
	 * @param   array  $plugins  - the plugins that we should render information for
	 *
	 * @return string
	 */
	public function renderPluginInfoUninstall($plugins)
	{
		$rows = 0;
		$html = array();

		if (count($plugins))
		{
			$html[] = '<table class="table">';
			$html[] = '<tbody>';
			$html[] = '<tr>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_PLUGIN') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_PLUGIN_GROUP') . '</th>';
			$html[] = '<th>' . JText::_('LIB_COMPOJOOM_STATUS') . '</th>';
			$html[] = '</tr>';

			foreach ($plugins as $plugin)
			{
				$html[] = '<tr class="row' . (++$rows % 2) . '">';
				$html[] = '<td class="key">' . $plugin['name'] . '</td>';
				$html[] = '<td class="key">' . ucfirst($plugin['group']) . '</td>';
				$html[] = '<td>';
				$html[] = '	<span style="color:' . (($plugin['result']) ? 'green' : 'red') . '; font-weight: bold;">';
				$html[] = ($plugin['result']) ? JText::_('LIB_COMPOJOOM_PLUGIN_UNINSTALLED') : JText::_('LIB_COMPOJOOM_PLUGIN_NOT_UNINSTALLED');
				$html[] = '</span>';
				$html[] = '</td>';
				$html[] = ' </tr> ';
			}

			$html[] = '</tbody > ';
			$html[] = '</table > ';
		}

		return implode('', $html);
	}

	/**
	 * Check if the installation is allowed
	 *
	 * @return boolean
	 */
	public function allowedInstall()
	{
		$jversion = new JVersion;
		$appl = JFactory::getApplication();
		$manifest = $this->parent->get("manifest")->attributes();

		// Check the minimum PHP version
		if (!empty($this->minimumPHPVersion))
		{
			if (defined('PHP_VERSION'))
			{
				$version = PHP_VERSION;
			}
			elseif (function_exists('phpversion'))
			{
				$version = phpversion();
			}
			else
			{
				// All bets are off!
				$version = '5.0.0';
			}

			if (!version_compare($version, $this->minimumPHPVersion, 'ge'))
			{
				$msg = "<p>You need PHP $this->minimumPHPVersion or later to install this component</p>";

				$appl->enqueueMessage($msg);

				return false;
			}
		}

		// Find mimimum required joomla version from the manifest file
		$minJVersion = $manifest->version;

		if (version_compare($jversion->getShortVersion(), $minJVersion, 'lt'))
		{
			$appl->enqueueMessage(
				JText::sprintf('LIB_COMPOJOOM_CANNOT_INSTALL_PRIOR_TO_JOOMLA', $manifest->name, $minJVersion), 'warning'
			);

			return false;
		}

		return true;
	}

	/**
	 * Removes obsolete files and folders
	 *
	 * @param   array  $removeList  The files and directories to remove
	 *
	 * @return void
	 */
	public function removeFilesAndFolders($removeList)
	{
		// Remove files
		if (isset($removeList['files']) && !empty($removeList['files']))
		{
			foreach ($removeList['files'] as $file)
			{
				$f = JPATH_ROOT . '/' . $file;

				if (!JFile::exists($f))
				{
					continue;
				}

				JFile::delete($f);
			}
		}

		// Remove folders
		if (isset($removeList['folders']) && !empty($removeList['folders']))
		{
			foreach ($removeList['folders'] as $folder)
			{
				$f = JPATH_ROOT . '/' . $folder;

				if (!JFolder::exists($f))
				{
					continue;
				}

				JFolder::delete($f);
			}
		}
	}
}
