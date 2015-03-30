<?php
/**
 * @package    lib_compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       30.03.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

if (JVERSION < 3)
{
	JLoader::register('JLayout', JPATH_LIBRARIES . '/compojoom/layout/joomla/layout.php');
	JLoader::register('JLayoutFile', JPATH_LIBRARIES . '/compojoom/layout/joomla/file.php');
	JLoader::register('JLayoutHelper', JPATH_LIBRARIES . '/compojoom/layout/joomla/helper.php');
	JLoader::register('JLayoutBase', JPATH_LIBRARIES . '/compojoom/layout/joomla/base.php');
}

/**
 * Class CompojoomLayoutFile
 *
 * @since  4.0.33
 */
class CompojoomLayoutFile extends JLayoutFile
{
	/**
	 * Refresh the list of include paths
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function refreshIncludePaths()
	{
		// Reset includePaths
		$this->includePaths = array();

		// (0 - lower priority) Frontend base layouts
		$this->addIncludePaths(JPATH_ROOT . '/layouts');

		// (1) Library path
		$this->addIncludePath(JPATH_LIBRARIES . '/compojoom/layouts');

		// (2) Standard Joomla! layouts overriden
		$this->addIncludePaths(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/layouts');

		// Component layouts & overrides if exist
		$component = $this->options->get('component', null);

		if (!empty($component))
		{
			// (3) Component path
			if ($this->options->get('client') == 0)
			{
				$this->addIncludePaths(JPATH_SITE . '/components/' . $component . '/layouts');
			}
			else
			{
				$this->addIncludePaths(JPATH_ADMINISTRATOR . '/components/' . $component . '/layouts');
			}

			// (4) Component template overrides path
			$this->addIncludePath(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/layouts/' . $component);
		}

		// (5 - highest priority) Received a custom high priority path ?
		if (!is_null($this->basePath))
		{
			$this->addIncludePath(rtrim($this->basePath, DIRECTORY_SEPARATOR));
		}
	}
}
