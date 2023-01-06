<?php
/**
 * @package    lib_compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       19.03.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('media/lib_compojoom/third/font-awesome/css/font-awesome.min.css');
JHtml::stylesheet('media/lib_compojoom/third/galleria/themes/compojoom/galleria.compojoom.css');
JHtml::script('media/lib_compojoom/third/galleria/galleria.js');
JHtml::script('media/lib_compojoom/third/galleria/themes/compojoom/galleria.compojoom.js');

JFactory::getDocument()->addScriptDeclaration("
	Galleria.run('.galleria', {
	  dataSource: " . $displayData['data'] . ",
      imageCrop: true
	});
");
?>
<div class="galleria"></div>