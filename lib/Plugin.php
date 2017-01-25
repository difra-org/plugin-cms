<?php

namespace Difra\CMS;

use Difra\Events;
use Difra\CMS;

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
        Events::register(Events::EVENT_ACTION_REDEFINE, '\Difra\Plugins\CMS::run');
        Events::register(Events::EVENT_ACTION_DONE, '\Difra\Plugins\CMS::addMenuXML');
        Events::register(Events::EVENT_ACTION_DONE, '\Difra\Plugins\CMS::addSnippetsXML');
    }

    /**
     * @return array|bool
     */
    public function getSitemap()
    {
        return CMS::getSitemap();
    }
}
