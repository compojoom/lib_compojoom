<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       05.11.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');


/**
 * Class CompojoomHtmlTemplates
 *
 * @since  1.0
 */
class CompojoomHtmlTemplates
{
	/**
	 * Function to render a social media info
	 *
	 * @return string
	 */
	public static function renderSocialMediaInfo()
	{
		$html[] = '<p><strong>' . JText::_('LIB_COMPOJOOM_LATEST_NEWS_PROMOTIONS') . '</strong>:</p>';
		$html[] = '<table><tr><td>' . JText::_('LIB_COMPOJOOM_LIKE_FB') . ': </td><td><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Fcompojoom&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=119257468194823" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe></td></tr>
							<tr><td>' . JText::_('LIB_COMPOJOOM_FOLLOW_TWITTER') . ': </td><td><a href="https://twitter.com/compojoom" class="twitter-follow-button" data-show-count="false">Follow @compojoom</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td></tr></table>';

		return implode('', $html);
	}
}
