<?php

namespace Difra\CMS;

use Difra\CMS;
use Difra\Events\Event;
use Difra\Tools\Sitemap;

/**
 * Class Plugin
 * @package Difra\Plugins\CMS
 */
class Plugin extends \Difra\Plugin
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        Event::getInstance(Event::EVENT_ACTION_REDEFINE)->registerHandler([CMS::class, 'run']);
        Event::getInstance(Event::EVENT_ACTION_DONE)->registerHandler([CMS::class, 'addMenuXML']);
        Event::getInstance(Event::EVENT_ACTION_DONE)->registerHandler([CMS::class, 'addSnippetsXML']);
        Event::getInstance(Sitemap::EVENT_NAME)->registerHandler([CMS::class, 'getSitemap']);
    }
}
