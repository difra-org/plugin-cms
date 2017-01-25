<?php

namespace Difra;

use Difra\Envi\Action;
use Difra\CMS\Menu;
use Difra\CMS\MenuItem;
use Difra\CMS\Page;
use Difra\CMS\Snippet;

/**
 * Class CMS
 * @package Difra\CMS
 */
class CMS
{
    /** Database configuration */
    const DB = 'cms';

    /**
     * @static
     * @return CMS
     */
    public static function getInstance()
    {
        static $_instance = null;
        return $_instance ? $_instance : $_instance = new self;
    }

    /**
     * Detect if CMS page is requested
     */
    public static function run()
    {
        if ($page = Page::find()) {
            Action::setCustomAction('\Difra\Plugins\CMS\Controller', 'pageAction', [$page]);
        }
    }

    /**
     * Add menus to output XML
     */
    public static function addMenuXML()
    {
        if (View::$instance == 'adm') {
            return;
        }
        $controller = Controller::getInstance();
        self::getMenuXML($controller->realRoot);
    }

    /**
     * Get all menus with all elements
     * @param \DOMElement $node
     * @return bool
     */
    public static function getMenuXML($node)
    {
        $data = Menu::getList();
        if (empty($data)) {
            return false;
        }
        foreach ($data as $menu) {
            /** @var \DOMElement $menuNode */
            $menuNode = $node->appendChild($node->ownerDocument->createElement('CMSMenu'));
            $menu->getXML($menuNode);
            self::getMenuItemsXML($menuNode, $menu->getId());
        }
        return true;
    }

    /**
     * Get menu items
     * @param \DOMNode $node
     * @param          $menuId
     * @return bool
     */
    public static function getMenuItemsXML($node, $menuId)
    {
        $data = MenuItem::getList($menuId);
        if (empty($data)) {
            return false;
        }
        foreach ($data as $item) {
            /** @var $itemNode \DOMElement */
            $itemNode = $node->appendChild($node->ownerDocument->createElement('menuitem'));
            $item->getXML($itemNode);
        }
        return true;
    }

    /**
     * Add text snippets to output XML
     */
    public static function addSnippetsXML()
    {
        if (View::$instance != 'main') {
            return;
        }

        $controller = Controller::getInstance();
        $snippetNode = $controller->realRoot->appendChild($controller->xml->createElement('snippets'));
        Snippet::getAllXML($snippetNode);
    }

    /**
     * Get URL list for sitemap
     * @return array|false
     */
    public static function getSitemap()
    {
        $data = CMS::getDB()->fetch(
            'SELECT `tag`,date_format(`modified`,\'%Y-%m-%d\') AS `modified` FROM `cms` WHERE `hidden`=0'
        );
        $res = [];
        if (empty($data)) {
            return false;
        }
        foreach ($data as $t) {
            $rec = ['loc' => $t['tag']];
            if (!empty($t['modified'])) {
                $rec['lastmod'] = $t['modified'];
            }
            $res[] = $rec;
        }
        return $res;
    }

    /**
     * Get database connection name
     */
    public static function getDB()
    {
        return DB::getInstance(self::DB);
    }

    /**
     * Get pages list
     * @param \DOMElement|\DOMNode $node
     * @param bool|int $visible
     * @return bool
     */
    public function getListXML($node, $visible = null)
    {
        $data = Page::getList($visible);
        if (empty($data)) {
            return false;
        }
        foreach ($data as $page) {
            $pageNode = $node->appendChild($node->ownerDocument->createElement('page'));
            $page->getXML($pageNode);
        }
        return true;
    }

    /**
     * Get menu list
     * @param \DOMNode $node
     * @return bool
     */
    public function getMenuListXML($node)
    {
        $data = Menu::getList();
        if (empty($data)) {
            return false;
        }
        foreach ($data as $menu) {
            /** @var \DOMElement $menuNode */
            $menuNode = $node->appendChild($node->ownerDocument->createElement('menuobj'));
            $menu->getXML($menuNode);
        }
        return true;
    }

    /**
     * Get menu items for parent menu of menu element
     * @param \DOMElement $node
     * @param int $id
     */
    public function getAvailablePagesForItemXML($node, $id)
    {
        $item = MenuItem::get($id);
        $this->getAvailablePagesXML($node, $item->getMenuId(), $item);
    }

    /**
     * Get pages list
     * @param \DOMElement $node
     * @param int $menuId
     * @param MenuItem $currentItem
     */
    public function getAvailablePagesXML($node, $menuId, $currentItem = null)
    {
        $current = MenuItem::getList($menuId);
        $currentIds = [];
        if (!empty($current)) {
            foreach ($current as $item) {
                $currentIds[] = $item->getPage();
            }
        }
        $all = CMS\Page::getList(true);
        if (!empty($all)) {
            foreach ($all as $item) {
                if (!$currentItem or $item->getId() != $currentItem->getPage()) {
                    if (in_array($item->getId(), $currentIds)) {
                        continue;
                    }
                }
                /** @var $pageNode \DOMElement */
                $pageNode = $node->appendChild($node->ownerDocument->createElement('page'));
                $item->getXML($pageNode);
            }
        }
    }
}
