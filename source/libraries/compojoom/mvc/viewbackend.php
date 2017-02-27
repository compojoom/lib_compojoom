<?php
/**
 * @package    Lib_Compojoom
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       16.05.16
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomMvcViewBackend - load the lanceng template in the backend
 *
 * @since  5.0.0
 */
class CompojoomMvcViewbackend extends CompojoomMvcView
{
	/**
	 * The title
	 *
	 * @var    string
	 * @since  5.0.0
	 */
	protected $cTitle;

	/**
	 * Slogan
	 *
	 * @var    string
	 * @since  5.0.0
	 */
	protected $cSlogan;

	/**
	 * Menu entry
	 *
	 * @var     string
	 * @since   5.0.0
	 */
	protected $cMenuEntry;

	/**
	 * The menu items
	 *
	 * @var     array
	 * @since   5.0.0
	 */
	protected $cMenu = array();

	/**
	 * Extension
	 *
	 * @var     string
	 * @since   5.0.0
	 */
	protected $extension;

	/**
	 * Extension
	 *
	 * @var     string
	 * @since   5.0.0
	 */
	protected $copyright;

	/**
	 * Minify CSS / JS
	 *
	 * @var     boolean
	 * @since   5.0.0
	 */
	protected $minify = true;

	/**
	 * Constructor
	 *
	 * @param   array  $config  The configuration array
	 * 
	 * @since   5.0.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}


	/**
	 * Set title
	 *
	 * @param   String  $title      - The title
	 * @param   String  $slogan     - The slogan
	 * @param   String  $menuEntry  - The menu entry
	 *
	 * @return  void
	 *
	 * @since   5.0.0
	 */
	public function setConfiguration($title, $slogan, $menuEntry, $menu, $extension = '')
	{
		$this->cTitle = $title;
		$this->cSlogan = $slogan;
		$this->cMenuEntry = $menuEntry;
		$this->cMenu = $menu;
		$this->extension = $extension;

		if (empty($this->extension))
		{
			$this->extension = JFactory::getApplication()->input->get('option');
		}

		// Set a default copyright
		if (empty($this->copyright))
		{
			$this->copyright = JText::_($this->extension) . ' is powered by <a href="compojoom.com">compojoom.com</a>';
		}
	}

	/**
	 * Set if minify css and js
	 *
	 * @param   bool  $minify  Minify the css
	 *
	 * @return  void
	 *
	 * @since   5.0.0
	 */
	public function setMinify($minify)
	{
		$this->minify = $minify;
	}

	/**
	 * Execute and display the Template.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void    Outputs directly
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   5.0.0
	 */
	public function display($tpl = null)
	{
		$tmpl = JFactory::getApplication()->input->getCmd('tmpl');

		// @todo improve and move
		if ($tmpl == 'component')
		{
			$result = $this->loadTemplate($tpl);

			if ($result instanceof Exception)
			{
				return $result;
			}

			echo $result;

			echo $this->copyright;
		}
		else
		{
			JHtml::_('formbehavior.chosen', 'select');

			echo CompojoomHtmlCtemplate::getHead(
				$this->cMenu,
				$this->cMenuEntry,
				$this->cTitle,
				$this->cSlogan
			);

			$result = $this->loadTemplate($tpl);

			if ($result instanceof Exception)
			{
				return $result;
			}

			echo '<div id="cextension_holder">';

			// Content from the template
			echo $result;

			// Copyright
			echo CompojoomHtmlCtemplate::getFooter($this->copyright);
			echo '</div>';
		}

		// Minify css & js
		CompojoomHtml::external(
			CompojoomHtml::getScriptQueue($this->extension),
			CompojoomHtml::getCSSQueue($this->extension),
			'media/com_' . $this->extension . '/cache',
			$this->minify,
			$this->minify
		);
	}
}
