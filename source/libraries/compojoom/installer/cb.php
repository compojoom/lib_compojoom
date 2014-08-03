<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       01.08.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomInstallerCB
 *
 * @since  1.0
 */
class CompojoomInstallerCb
{
	/**
	 * Function to install an AUP rule
	 *
	 * @param   object  $parent      - the parent installer object
	 * @param   string  $pluginName  - the plugin name
	 *
	 * @return boolean
	 */
	public static function install($parent, $pluginName)
	{
		$status = false;

		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/library/cb/cb.installer.php'))
		{
			global $_CB_framework;
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.class.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/comprofiler.class.php';

			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/library/cb/cb.installer.php';

			$cbInstaller = new cbInstallerPlugin;

			if ($cbInstaller->install($parent->getParent()->getPath('source') . '/components/com_comprofiler/plugin/user/' . $pluginName . '/'))
			{
				$path = $parent->getParent()->getPath('source') . '/components/com_comprofiler/plugin/user/' . $pluginName . '/administrator/language';
				$languages = JFolder::folders($path);

				foreach ($languages as $language)
				{
					if (JFolder::exists(JPATH_ROOT . '/administrator/language/' . $language))
					{
						JFile::copy(
							$path . '/' . $language . '/' . $language . '.plg_' . $pluginName . '.ini',
							JPATH_ROOT . '/administrator/language/' . $language . '/' . $language . '.plg_' . $pluginName . '.ini'
						);
					}
				}

				$status = true;
			}
		}

		return $status;
	}
}
