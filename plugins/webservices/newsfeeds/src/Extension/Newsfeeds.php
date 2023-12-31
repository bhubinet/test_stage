<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Webservices.newsfeeds
 *
 * @copyright   (C) 2019 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\WebServices\Newsfeeds\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\ApiRouter;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Web Services adapter for com_newsfeeds.
 *
 * @since  4.0.0
 */
final class Newsfeeds extends CMSPlugin
{
    /**
     * Registers com_newsfeeds's API's routes in the application
     *
     * @param   ApiRouter  &$router  The API Routing object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function onBeforeApiRoute(&$router)
    {
        $router->createCRUDRoutes(
            'v1/newsfeeds/feeds',
            'feeds',
            ['component' => 'com_newsfeeds']
        );

        $router->createCRUDRoutes(
            'v1/newsfeeds/categories',
            'categories',
            ['component' => 'com_categories', 'extension' => 'com_newsfeeds']
        );
    }
}
