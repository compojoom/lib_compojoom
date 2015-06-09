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
$lang     = JFactory::getLanguage()->getTag();
$document = JFactory::getDocument();
$document->addCustomTag('<meta property="og:url" content="' . JURI::current() . '" />');

if (isset($displayData['meta']['type']))
{
	if ($displayData['meta']['type'] == 'place')
	{
		if (isset($displayData['meta']['lat']) && isset($displayData['meta']['lng']))
		{
			$document->addCustomTag('<meta property="og:type" content="' . $displayData['meta']['type'] . '" />');
			$document->addCustomTag('<meta property="place:location:latitude" content="' . $displayData['meta']['lat'] . '" />');
			$document->addCustomTag('<meta property="place:location:longitude" content="' . $displayData['meta']['lng'] . '" />');
		}
	}
	else
	{
		$document->addCustomTag('<meta property="og:type" content="' . $displayData['meta']['type'] . '" />');
	}
}


if (isset($displayData['meta']['title']))
{
	$document->addCustomTag('<meta property="og:title" content="' . $this->escape(JHtmlString::truncate(strip_tags($this->escape($displayData['meta']['title'])), 150)) . '" />');
}

if (isset($displayData['meta']['description']))
{
	$document->addCustomTag('<meta property="og:description" content="' . $this->escape(JHtmlString::truncate(strip_tags(($displayData['meta']['description']), 200))) . '" />');
}

if (isset($displayData['meta']['image']) && strlen($displayData['meta']['image']))
{
	$document->addCustomTag('<meta property="og:image" content="' . $displayData['meta']['image'] . '" />');
}
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

<style type="text/css">
	.fb_iframe_widget iframe {
		max-width: inherit;
	}
</style>