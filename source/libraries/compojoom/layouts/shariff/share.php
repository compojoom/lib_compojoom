<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       10.06.2015
 *
 * @copyright  Copyright (C) 2008 - 2015 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

// We use Shariff plugin here, so first we need to check if it is existing
JPluginHelper::importPlugin('content');
$results = JFactory::getApplication()->triggerEvent('onContentAfterDisplay', array('shariff.general', &$displayData, &$displayData, 0));
?>
<div class="c_social_media">
	<?php echo trim(implode("\n", $results)); ?>
</div>
