<?php
/**
 * @package    Lib_Compojoom
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       09.07.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CompojooomHtmlFeed
 *
 * Renders the feed from the provided url
 *
 * @since  1.1
 */
class CompojoomHtmlFeed
{
	/**
	 * Renders a feed
	 *
	 * @param   string  $url  - the feed url
	 *
	 * @return void
	 */
	public static function renderFeed($url)
	{
		$rssitems			= 5;
		$rssitemdesc		= 1;


		$feed   = new JFeedFactory;
		$rssDoc = $feed->getFeed($url);

		$feed = $rssDoc;

		if ($rssDoc != false)
		{
			?>
			<div class="feed">
				<?php if (!is_null($feed->title)): ?>
					<h2>
						<a href="<?php echo str_replace('&', '&amp;', $url); ?>" target="_blank">
							<?php echo $feed->title; ?></a>
					</h2>
				<?php endif; ?>

				<?php echo $feed->description; ?>


				<ul class="newsfeed">
					<?php for ($i = 0; $i < $rssitems; $i++)
					{
						if (!$feed->offsetExists($i))
						{
							break;
						}
						?>
						<?php
						$uri = (!empty($feed[$i]->uri) || !is_null($feed[$i]->uri)) ? $feed[$i]->uri : $feed[$i]->guid;
						$text = !empty($feed[$i]->content) ||  !is_null($feed[$i]->content) ? $feed[$i]->content : $feed[$i]->description;
						?>
						<li>
							<?php if (!empty($uri)) : ?>
								<h5 class="feed-link">
									<a href="<?php echo $uri; ?>" target="_blank">
										<?php  echo $feed[$i]->title; ?></a></h5>
							<?php else : ?>
								<h5 class="feed-link"><?php  echo $feed[$i]->title; ?></h5>
							<?php  endif; ?>

							<?php if ($rssitemdesc && !empty($text)) : ?>
								<div class="feed-item-description">
									<?php
									// Strip the images.
									$text = JFilterOutput::stripImages($text);

									$text = JHtml::_('string.truncate', $text, 250);
									echo str_replace('&apos;', "'", $text);
									?>
								</div>
							<?php endif; ?>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php
		}
	}
}
