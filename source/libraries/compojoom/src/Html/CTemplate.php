<?php
/**
 * @package    Lib_Compojoom
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       26.04.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Compojoom\Library\Html;

defined('_JEXEC') or die('Restricted access');


use Compojoom\Library\Html\Behavior as CompojoomHtmlBehavior;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Language\Text;

/**
 * Class CTemplate
 *
 * @since  1.1
 */
class CTemplate
{
    /**
     * Function to render a social media info
     *
     * Todo: Cleanup and refactor..
     *     *
     * @param array $menu - The menu
     * @param CString $active - The active entry
     * @param CString $title - The title
     * @param CString $slogan - The slogan
     * @param CString $extension - The extension (opt - if not set taken from input->get('option'))
     *
     * @return CString
     */
    public static function getHead($menu, $active = 'dashboard', $title = '', $slogan = '', $extension = '')
    {
        // Load bootstrap
        CompojoomHtmlBehavior::lanceng(true, true, true);
        $isJoomla3 = version_compare(JVERSION, '4', 'lt');

        $input = Factory::getApplication()->input;

        if (empty($extension)) {
            $extension = $input->get('option');
        }

        if (empty($active)) {
            $active = $input->getCmd('view', "");
        }

        $user = Factory::getUser();
        $gravatar = self::get_gravatar($user->email);

        $html[] = '<div class="compojoom-bootstrap" style="clear: both">';

        // Loading animation
        $html[] = '<div id="loading" style="display: none;">
						<div class="loading-inner">
							<div class="spinner">
								<div class="cube1"></div>
								<div class="cube2"></div>
							</div>
						</div>
					</div>';

        $toolbar = Toolbar::getInstance('toolbar');
        $toolbarItems = $toolbar->getItems();

        // Container
        $html[] = '<div class="c-container">';

        if ($isJoomla3) {
            $html[] = '<div class="logo-brand header sidebar rows">
							<div class="c-extension-title logo pull-left">
								<h1><a href="\' . JRoute::_("index.php?option=" . Factory::getApplication()->input->get(\'option\')) . \'">' . Text::_($extension) . '</a></h1>
							</div>
							<div class="c-toolbar-holder">
								<div class="c-toolbar pull-left">';

            foreach ($toolbarItems as $item) {
                $html[] = $toolbar->renderButton($item);
            }


            $html[] = '</div>
							</div>
							<div class="c-logo-icon pull-left hidden-sm hidden-xs hidden-md">
								<a href="https://compojoom.com" title="Compojoom"><img src="../media/lib_compojoom/img/logo-green.png" alt="Compojoom" /></a>
							</div>
						</div>
					';
        }
        if ($isJoomla3) {
            // Begin sidebar
            $html[] = '<div class="left side-menu">
						<div class="body rows scroll-y">
							<div class="sidebar-inner" style="min-height: 100%">
								<div class="media c-media-sidebar">
								<a class="pull-left" href="index.php?option=com_users">
									<img class="media-object" src="' . $gravatar . '" alt="Avatar" />
								</a>
							<div class="media-body c-media-introtext">
								' . Text::_('LIB_COMPOJOOM_WELCOME_BACK') . ',
								<h4 class="media-heading"><strong>' . $user->name . '</strong></h4>
							</div>
					</div>
				';

            // Search
            $html[] = '<div id="search">
						<form role="form">
							<input type="text" id="csearch" class="form-control search" placeholder="' . Text::_('LIB_COMPOJOOM_SEARCH_HERE') . '" />
							<i class="fa fa-search"></i>
						</form>
					</div>';

//		 Sidebar menu
            $html[] = '<div id="sidebar-menu" style="clear: both;">
						<ul>';

            foreach ($menu as $k => $m) {
                $act = "";

                if ($k == $active || array_key_exists($active, $m['children'])) {
                    $act = ' class="active"';
                }

                $keyw = "";

                if (!empty($m['keywords'])) {
                    $keyw = ' keywords="' . $m['keywords'] . '"';
                }

                $html[] = '<li' . $act . $keyw . '>';

                // If we have an empty link we generate it on the key! like jtoolbarhelper does
                if (empty($m['link'])) {
                    $m['link'] = 'index.php?option=' . $extension . '&view=' . $k;
                }

                // Link
                $html[] = '<a href="' . JRoute::_($m['link']) . $m['anchor'] . '" title="' . Text::_($m['title']) . '">';

                // Icon
                if (!empty($m['icon'])) {
                    $child = '';

                    if (count($m['children'])) {
                        $child = ' i-has-children ';
                    }

                    $html[] = '<i class="fa ' . $m['icon'] . $child . ' hasTooltip" data-toggle="tooltip" data-placement="right" title="' . Text::_($m['title']) . '"></i> ';
                }

                if (count($m['children'])) {
                    $html[] = '<i class="fa fa-angle-double-down i-right"></i> ';
                }

                $html[] = '<span class="side-menu-item-text">' . Text::_($m['title']) . '</span>';

                $html[] = '</a>';

                if (count($m['children'])) {
                    $style = "";

                    if ($k == $active || array_key_exists($active, $m['children'])) {
                        $style = ' style="display: block;"';
                    }

                    $html[] = '<ul' . $style . '>';

                    foreach ($m['children'] as $kc => $c) {
                        $act = "";

                        if ($kc == $active) {
                            $act = ' class="active"';
                        }

                        $keywc = "";

                        if (!empty($c['keywords'])) {
                            $keywc = ' keywords="' . $c['keywords'] . '"';
                        }

                        $html[] = '<li key="' . $kc . '"' . $act . $keywc . '>';

                        // If we have an empty link we generate it on the key! like jtoolbarhelper does
                        if (empty($c['link'])) {
                            $c['link'] = 'index.php?option=' . $extension . '&view=' . $kc;
                        }

                        // Link
                        $html[] = '<a href="' . JRoute::_($c['link']) . $c['anchor'] . '" title="' . Text::_($c['title']) . '">';

                        // Icon
                        // $html[] = '<i class="fa fa-angle-right"></i> ';

                        // Icon right
                        if (!empty($c['icon'])) {
                            $html[] = '<i class="fa ' . $c['icon'] . '" data-toggle="tooltip" data-placement="right" title="' . Text::_($c['title']) . '"></i> ';
                        }

                        $html[] = '<span class="side-menu-item-text">' . Text::_($c['title']) . '</span>';

                        $html[] = '</a>';

                        $html[] = '</li>';
                    }

                    $html[] = '</ul>';
                }

                if (!empty($m['label'])) {
                    $html[] = '<span class="label label-success new-circle animated double shake c-sp-inline">' . $m['label'] . '</span>';
                }

                $html[] = '</li>';
            }

            $html[] = '</ul>
						<div class="clear clr"></div>
					</div><!-- End div #sidebar-menu -->
				</div><!-- End div .sidebar-inner .slimscroller -->
            </div><!-- End div .body .rows .scroll-y -->
		</div>
		';
        }

        // BEGIN CONTENT
        $html[] = '<div class="' . ($isJoomla3 ? "right" : "") . ' content-page">';

        // BEGIN CONTENT HEADER
        $html[] = '<div class="body content rows scroll-y">';
        $html[] = '<div class="content-inner">';

        $html[] = '<div id="c-debug-container"> </div>';

        $html[] = '<div id="c-system-message-container"> </div>';

        if (!empty($title)) {
            $html[] = '<div class="page-heading animated fadeInDownBig">
							<h1 id="ctitle">' . Text::_($title) . ' <small>' . Text::_($slogan) . '</small></h1>
						</div>';
            $app = Factory::getApplication();

            if (!$app->isClient("site")) {
                $app->JComponentTitle = Text::_($title);
                Factory::getDocument()->setTitle(Factory::getConfig()->get('sitename') . ' - ' . Text::_('JADMINISTRATION') . ' - ' . Text::_($title));
            }
        }

        return implode('', $html);
    }

    /**
     * Gets the footer code (TODO move to JLayout)
     *
     * @param CString $footer - The footer html (e.g. Matukio by compojoom)
     *
     * @return  CString
     * @since   5.0.0
     */
    public static function getFooter($footer)
    {
        if (!empty($footer)) {
            $html[] = '<footer class="text-center">';
            $html[] = $footer;
            $html[] = '</footer>';
        }

        $html[] = '		</div>
					</div>
					</div>
				</div>
				<div class="clear clr"></div>
				<div class="md-overlay"></div>
			</div>';

        return implode('', $html);
    }


    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param CString $email The email address
     * @param int|CString $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param CString $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param CString $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool|\boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     *
     * @return CString containing either just a URL or a complete image tag
     *
     * @source http://gravatar.com/site/implement/images/php/
     */
    public static function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://secure.gravatar.com/avatar/' . md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";

        if ($img) {
            $url = '<img src="' . $url . '"';

            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }

            $url .= ' />';
        }

        return $url;
    }
}
