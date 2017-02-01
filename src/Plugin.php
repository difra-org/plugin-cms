<?php

namespace Difra\CMS;

use Difra\CMS;
use Difra\Events\Event;

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
        Event::getInstance(Event::EVENT_ACTION_REDEFINE)->registerHandler('Difra\CMS::run');
        Event::getInstance(Event::EVENT_ACTION_DONE)->registerHandler('Difra\CMS::addMenuXML');
        Event::getInstance(Event::EVENT_ACTION_DONE)->registerHandler('Difra\CMS::addSnippetsXML');
    }

    /**
     * @return array|bool
     */
    public function getSitemap()
    {
        return CMS::getSitemap();
    }
}
