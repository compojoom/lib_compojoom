<?php
/**
 * @package    Lib_Compojoom
 * @author     Yves Hoppe <yves@comnpojoom.com>
 * @date       20.09.2014
 *
 * @copyright  Copyright (C) 2008 - 2014 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojoomUtilsAdvertising
 *
 * @since 4.0.8
 */
class CompojoomUtilsAdvertising
{
	/**
	 * Generates the slideshow advertising HTML for our extensions
	 *
	 * @param   string  $extension  - The current extension
	 *
	 * @return  string  - Just echo
	 */
	public static function getSlideshow($extension = "")
	{
		if (empty($extension))
		{
			$extension = JFactory::getApplication()->input->get("option", "");
		}

		$extensions[] = array("img" => "media/lib_compojoom/img/extensions/hotspots-bg.jpg",
					"name" => "Hotspots", "desc" => "LIB_COMPOJOOM_ADV_HOTSPOTS_DESC",
					"link" => "https://compojoom.com/joomla-extensions/hotspots"
		);

		$extensions[] = array("img" => "media/lib_compojoom/img/extensions/matukio-bg.jpg",
		                      "name" => "Matukio", "desc" => "LIB_COMPOJOOM_ADV_MATUKIO_DESC",
		                      "link" => "https://compojoom.com/joomla-extensions/matukio-events-management-made-easy"
		);

		$extensions[] = array("img" => "media/lib_compojoom/img/extensions/ccomment-bg.jpg",
		                      "name" => "CComment", "desc" => "LIB_COMPOJOOM_ADV_CCOMMENT_DESC",
		                      "link" => "https://compojoom.com/joomla-extensions/ccomment"
		);

		$extensions[] = array("img" => "media/lib_compojoom/img/extensions/cmigrator-bg.jpg",
		                      "name" => "CMigrator", "desc" => "LIB_COMPOJOOM_ADV_CMIGRATOR_DESC",
		                      "link" => "https://compojoom.com/joomla-extensions/cmigrator"
		);

		$extensions[] = array("img" => "media/lib_compojoom/img/extensions/tiles-bg.jpg",
		                      "name" => "Tiles", "desc" => "LIB_COMPOJOOM_ADV_TILES_DESC",
		                      "link" => "https://compojoom.com/joomla-extensions/tiles"
		);

		$html[] = '<!-- Carousel -->';
		$html[] = '<div id="carousel-advertising" class="carousel slide text-center" data-ride="carousel">';
		$html[] = '  <!-- Indicators -->';
		$html[] = '  <ol class="carousel-indicators">';

		foreach ($extensions as $i => $e)
		{
			$active = (($i == 0) ? "active" : "");

			$html[] = '    <li data-target="#carousel-advertising" data-slide-to="' . $i . '" class="' . $active . '"></li>';
		}

		$html[] = '  </ol>';

		$html[] = '<!-- Wrapper for slides -->';
		$html[] = '  <div class="carousel-inner">';

		foreach ($extensions as $i => $e)
		{
			$active = (($i == 0) ? " active" : "");

			$html[] = '    <div class="item' . $active . '">';

			$html[] = '       <div class="text-center carousel-text">';
			$html[] = '         <h3><i class="fa fa-quote-left fa-2x"></i>' . JText::_($e["desc"]) . "</h3>";
			$html[] = '       </div>';
			$html[] = '       <div class="carousel-img">';
			$html[] = '         <img src="' . JURI::root() . $e["img"] . '" class="img-absolute"
									alt="' . JText::_($e["name"]) . '" /> ';
			$html[] = '       </div>';
			$html[] = '       <div class="carousel-caption">';
			$html[] = '         <a class="btn btn-primary" href="' . $e["link"] . '" target="_blank">Get ' . JText::_($e["name"]) . ' now!</a>';
			$html[] = '       </div>';
			$html[] = '    </div>';
		}

		$html[] = '  <!-- Controls -->';
		$html[] = '  <a class="carousel-control carousel-control-left" href="#carousel-advertising" role="button" data-slide="prev">';
		$html[] = '     <span class="fa fa-chevron-left fa-2x"></span>';
		$html[] = '  </a>';
		$html[] = '  <a class="carousel-control carousel-control-right" href="#carousel-advertising" role="button" data-slide="next">';
		$html[] = '    <span class="fa fa-chevron-right fa-2x"></span>';
		$html[] = '  </a>';

		$html[] = '  </div>';
		$html[] = '</div>';

		return implode("", $html);
	}
}