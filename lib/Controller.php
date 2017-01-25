<?php

namespace Difra\CMS;

use Difra\Param;

/**
 * Class Controller
 * CMS plugin adds hook for pre-action event. If hook detects CMS page is requested, Action is configured to call this
 * controller.
 * @package Difra\CMS
 */
class Controller extends \Difra\Controller
{
    /**
     * @param \Difra\Param\AnyInt $id
     */
    public function pageAction(Param\AnyInt $id)
    {
        /** @var $pageNode \DOMElement */
        $pageNode = $this->root->appendChild($this->xml->createElement('page'));
        $page = Page::get($id->val());
        $page->getXML($pageNode);
        $this->root->setAttribute('pageTitle', $page->getTitle());
    }
}
