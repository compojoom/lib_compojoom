<?php
/**
 * @package    lib_compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       28.04.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
?>

<div class="alert alert-warning">
	<h3>
		<span class="fa fa-warning"></span>
		<?php echo $displayData['header']; ?>
	</h3>
	<p>
		<a href="index.php?option=com_installer&view=update" class="btn btn-primary">
			<?php echo $displayData['button']; ?>
		</a>
		<a href="<?php echo $displayData['infourl']; ?>" target="_blank" class="btn btn-small btn-info">
			<?php echo $displayData['infolbl']; ?>
		</a>
	</p>
</div>
