<?php

declare(strict_types=1);

namespace Difra\CMS;

use Difra\CMS;
use Difra\Events\Event;
use Difra\Tools\Sitemap;

class Plugin extends \Difra\Plugin
{
    /**
     * @inheritdoc
     */
    public function init(): void
    {
        Event::getInstance(Event::EVENT_ACTION_REDEFINE)->registerHandler([CMS::class, 'run']);
        Event::getInstance(Event::EVENT_ACTION_DONE)->registerHandler([CMS::class, 'addMenuXML']);
        Event::getInstance(Event::EVENT_ACTION_DONE)->registerHandler([CMS::class, 'addSnippetsXML']);
        Event::getInstance(Sitemap::EVENT_NAME)->registerHandler([CMS::class, 'getSitemap']);
    }
}
