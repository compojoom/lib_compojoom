<?php
/**
 * @package    lib_compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       25.03.2015
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
$lang = JFactory::getLanguage()->getTag();
?>
<!-- Facebook -->
<script>(function (d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src = "//connect.facebook.net/<?php echo str_replace('-', '_', $lang); ?>/all.js#xfbml=1";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>

<div class="fb-like" data-href="<?php echo JURI::current(); ?>" data-send="false"
     data-layout="button_count"
     data-width="100" data-show-faces="false" data-action="recommend" style="margin-right: 25px;"></div>
<!-- Facebook end-->
