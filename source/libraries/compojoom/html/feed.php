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


		// Aaach, Joomla 2.5 please die faster...
		if (JVERSION < '3') {
			$rssDoc = JFactory::getFeedParser($url, 900);

			if(!$rssDoc)
			{
				return JText::_('LIB_COMPOJOOM_FEED_COULDNT_BE_FETCHED');
			}
		}
		else {
			// Get RSS parsed object
			try
			{
				jimport('joomla.feed.factory');
				$feed   = new JFeedFactory;
				$rssDoc = $feed->getFeed($url);
			}
			catch (Exception $e)
			{
				return JText::_('LIB_COMPOJOOM_FEED_COULDNT_BE_FETCHED');
			}
		}

		$feed = $rssDoc;

		if(JVERSION < 3)
		{
			if ($rssDoc != false)
			{
				$filter = JFilterInput::getInstance();

				// Channel header and link
				$channel['title'] = $filter->clean($rssDoc->get_title());
				$channel['link'] = $filter->clean($rssDoc->get_link());
				$channel['description'] = $filter->clean($rssDoc->get_description());

				// Items
				$items = $rssDoc->get_items();

				// Feed elements
				$items = array_slice($items, 0, $rssitems);
				?>
				<div class="newsfeed">
					<?php if (!is_null($channel['title'])): ?>
						<h2>
							<a href="<?php echo htmlspecialchars(str_replace('&', '&amp;', $channel['link'])); ?>" target="_blank">
								<?php echo htmlspecialchars($channel['title']); ?></a>
						</h2>
					<?php endif; ?>

					<?php echo $channel['description']; ?>

					<?php

						$actualItems = count($items);
						$setItems = $rssitems;

						if ($setItems > $actualItems)
						{
							$totalItems = $actualItems;
						}
						else
						{
							$totalItems = $setItems;
						}
						?>

						<ul class="newsfeed">
							<?php
							for ($j = 0; $j < $totalItems; $j ++)
							{
								$currItem = $items[$j];
								?>
								<li>
									<?php if (!is_null($currItem->get_link())): ?>
										<a href="<?php echo htmlspecialchars($currItem->get_link()); ?>" target="_child">
											<?php echo htmlspecialchars($currItem->get_title()); ?></a>
									<?php endif; ?>

									<?php
									// Item description
									if ($rssitemdesc)
									{
										// Item description
										$text = $filter->clean(html_entity_decode($currItem->get_description(), ENT_COMPAT, 'UTF-8'));
										$text = str_replace('&apos;', "'", $text);

										$texts = explode(' ', $text);
										$count = count($texts);
										if ($count > 50)
										{
											$text = '';

											for ($i = 0; $i < 50; $i ++)
											{
												$text .= ' '.$texts[$i];
											}

											$text .= '...';
										}
										?>
										<div>
											<?php echo $text; ?>
										</div>
									<?php
									}
									?>
								</li>
							<?php
							}
							?>
						</ul>
					</div>
			<?php
			}
		}
		else
		{
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
}
