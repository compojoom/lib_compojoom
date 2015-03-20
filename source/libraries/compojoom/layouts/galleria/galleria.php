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

JHTML::_('stylesheet', 'media/lib_compojoom/third/font-awesome/css/font-awesome.min.css');
JHtml::script('media/lib_compojoom/third/galleria/galleria.js');
JHtml::script('media/lib_compojoom/third/galleria/themes/compojoom/galleria.compojoom.js');

?>
<style type="text/css">
	.galleria{ max-width: 700px; height: 400px; background: #000 }
</style>

<div class="galleria"></div>

<script type="text/javascript">
	Galleria.run('.galleria', {
		dataSource: <?php echo $displayData['data']; ?>
	})
</script>
