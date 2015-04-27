<?php
/**
 * @package    lib_compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       13.04.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
?>

<div class="box-info alert animated fadeIn">
	<h2>
		<span class="fa fa-joomla"></span>
		<a href="<?php echo $displayData['jed_url']; ?>#reviews" target="_blank">
			<?php echo JText::sprintf('LIB_COMPOJOOM_EXTENSION_AT_JED', $displayData['title']); ?>
		</a>
	</h2>

	<div class="additional-btn">
		<a href="index.php?option=<?php echo $displayData['component']; ?>&task=jed.update&jed=1" class="fa fa-close">
			<?php echo JText::sprintf('LIB_COMPOJOOM_JED_HIDE_THIS_AND_DONT_REMIND', $displayData['title']); ?>
		</a>
	</div>
	<p>
		<?php echo JText::sprintf('LIB_COMPOJOOM_JED_PLEASED', $displayData['title'], 'https://compojoom.com'); ?>

	</p>
	<p>
		<?php echo JText::_('LIB_COMPOJOOM_JED_MEANWHILE'); ?>
	</p>
	<p>
		<a href="<?php echo $displayData['jed_url']; ?>" target="_blank" class="btn btn-primary">
			<span class="fa fa-pencil"></span>
			<?php echo JText::_('LIB_COMPOJOOM_JED_LEAVE_A_REVIEW_NOW'); ?>
		</a>
		<a href="index.php?option=<?php echo $displayData['component']; ?>&task=jed.update&jed=2" class="btn btn-small btn-default">
			<span class="fa fa-clock-o"></span>
			<?php echo JText::_('LIB_COMPOJOOM_JED_REMIND_NEXT_WEEK'); ?>
		</a>
	</p>
</div>
