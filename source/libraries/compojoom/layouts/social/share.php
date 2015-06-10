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

$fb      = new CompojoomLayoutFile('social.share.facebook');
$twitter = new CompojoomLayoutFile('social.share.twitter');
$gplus   = new CompojoomLayoutFile('social.share.gplus');

echo $fb->render($displayData);
echo $twitter->render($displayData);
echo $gplus->render($displayData);
