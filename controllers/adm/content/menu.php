<?php

use Difra\Plugins\CMS;
use Difra\Plugins\CMS\MenuItem;

/**
 * Class AdmContentMenuController
 */
class AdmContentMenuController extends \Difra\Controller\Adm
{
    /**
     * Menu list
     */
    public function indexAction()
    {
        $listNode = $this->root->appendChild($this->xml->createElement('CMSMenuList'));
        \Difra\Plugins\CMS::getInstance()->getMenuListXML($listNode);
    }

    /**
     * Menu elements list
     * @param Difra\Param\AnyInt $menuId
     */
    public function viewAction(\Difra\Param\AnyInt $menuId)
    {
        /** @var $menuNode \DOMElement */
        $menuNode = $this->root->appendChild($this->xml->createElement('CMSMenuItems'));
        $menu = \Difra\Plugins\CMS\Menu::get($menuId->val());
        $menu->getXML($menuNode);
        \Difra\Plugins\CMS::getInstance()->getMenuItemsXML($menuNode, $menuId->val());
    }

    /**
     * Add menu element form
     * @param Difra\Param\AnyInt $menuId
     * @param \Difra\Param\NamedString $parent Parent node
     */
    public function addAction(\Difra\Param\AnyInt $menuId, \Difra\Param\NamedString $parent = null)
    {
        /** @var $addNode \DOMElement */
        $addNode = $this->root->appendChild($this->xml->createElement('CMSMenuItemAdd'));
        $addNode->setAttribute('menu', $menuId->val());
        $addNode->setAttribute('parent', $parent ? $parent->val() : null);
        $this->getEditXML($addNode, $menuId->val());
        \Difra\Plugins\CMS::getInstance()->getAvailablePagesXML($addNode, $menuId->val());
    }

    /**
     * Edit menu element form
     * @param Difra\Param\AnyInt $id
     */
    public function editAction(\Difra\Param\AnyInt $id)
    {
        /** @var $editNode \DOMElement */
        $editNode = $this->root->appendChild($this->xml->createElement('CMSMenuItemEdit'));
        $menuItem = MenuItem::get($id->val());
        $menuItem->getXML($editNode);
        $this->getEditXML($editNode, $menuItem->getMenuId());
        \Difra\Plugins\CMS::getInstance()->getAvailablePagesForItemXML($editNode, $id->val());
    }

    /**
     * @param \DOMNode|\DOMElement $node
     * @param int $menuId
     */
    private function getEditXML($node, $menuId)
    {
        $menu = \Difra\Plugins\CMS\Menu::get($menuId);
        $node->setAttribute('depth', $menu->getDepth());
        $parentsNode = $node->appendChild($this->xml->createElement('parents'));
        \Difra\Plugins\CMS::getInstance()->getMenuItemsXML($parentsNode, $menu->getId());
    }

    /**
     * Save menu element: page
     * @param Difra\Param\AjaxInt $menu
     * @param Difra\Param\AjaxInt $page
     * @param Difra\Param\AjaxInt $id
     * @param Difra\Param\AjaxInt $parent
     */
    public function savepageAjaxAction(
        \Difra\Param\AjaxInt $menu,
        \Difra\Param\AjaxInt $page,
        \Difra\Param\AjaxInt $id = null,
        \Difra\Param\AjaxInt $parent = null
    ) {
        if ($id) {
            $item = \Difra\Plugins\CMS\MenuItem::get($id->val());
        } else {
            $item = \Difra\Plugins\CMS\MenuItem::create();
        }
        $item->setMenu($menu->val());
        $item->setParent($parent ? $parent->val() : null);
        $item->setPage($page->val());
        \Difra\Ajaxer::redirect('/adm/content/menu/view/' . $menu->val());
    }

    /**
     * Save menu element: link
     * @param Difra\Param\AjaxInt $menu
     * @param Difra\Param\AjaxString $link
     * @param Difra\Param\AjaxString $label
     * @param Difra\Param\AjaxInt $id
     * @param Difra\Param\AjaxInt $parent
     */
    public function savelinkAjaxAction(
        \Difra\Param\AjaxInt $menu,
        \Difra\Param\AjaxString $link,
        \Difra\Param\AjaxString $label,
        \Difra\Param\AjaxInt $id = null,
        \Difra\Param\AjaxInt $parent = null
    ) {
        if ($id) {
            $item = \Difra\Plugins\CMS\MenuItem::get($id->val());
        } else {
            $item = \Difra\Plugins\CMS\MenuItem::create();
        }
        $item->setMenu($menu->val());
        $item->setParent($parent ? $parent->val() : null);
        $item->setLink($link);
        $item->setLinkLabel($label);
        \Difra\Ajaxer::redirect('/adm/content/menu/view/' . $menu->val());
    }

    /**
     * Save empty menu element
     * @param \Difra\Param\AjaxInt $menu
     * @param \Difra\Param\AjaxString $label
     * @param \Difra\Param\AjaxInt|null $id
     * @param \Difra\Param\AjaxInt $parent
     */
    public function saveemptyAjaxAction(
        \Difra\Param\AjaxInt $menu,
        \Difra\Param\AjaxString $label,
        \Difra\Param\AjaxInt $id = null,
        \Difra\Param\AjaxInt $parent = null
    ) {
        if ($id) {
            $item = \Difra\Plugins\CMS\MenuItem::get($id->val());
        } else {
            $item = \Difra\Plugins\CMS\MenuItem::create();
        }
        $item->setMenu($menu->val());
        $item->setParent($parent ? $parent->val() : null);
        $item->setLinkLabel($label);
        \Difra\Ajaxer::redirect('/adm/content/menu/view/' . $menu->val());
    }

    /**
     * Delete menu element
     * @param Difra\Param\AnyInt $id
     * @param Difra\Param\AjaxCheckbox $confirm
     */
    public function deleteAjaxAction(\Difra\Param\AnyInt $id, \Difra\Param\AjaxCheckbox $confirm = null)
    {
        if (!$confirm or !$confirm->val()) {
            \Difra\Ajaxer::display(
                '<span>'
                . \Difra\Locales::get('cms/adm/menuitem/delete-item-confirm')
                . '</span>'
                . '<form action="/adm/content/menu/delete/' . $id . '" method="post" class="ajaxer">'
                . '<input type="hidden" name="confirm" value="1"/>'
                . '<input type="submit" value="Да"/>'
                . '<a href="#" onclick="ajaxer.close(this)" class="button">Нет</a>'
                . '</form>'
            );
        } else {
            \Difra\Plugins\CMS\MenuItem::get($id->val())->delete();
            \Difra\Ajaxer::refresh();
            \Difra\Ajaxer::close();
        }
    }

    /**
     * Move menu element up
     * @param Difra\Param\AnyInt $id
     */
    public function upAjaxAction(\Difra\Param\AnyInt $id)
    {
        \Difra\Plugins\CMS\MenuItem::get($id->val())->moveUp();
        \Difra\Ajaxer::refresh();
    }

    /**
     * Move menu element down
     * @param Difra\Param\AnyInt $id
     */
    public function downAjaxAction(\Difra\Param\AnyInt $id)
    {
        \Difra\Plugins\CMS\MenuItem::get($id->val())->moveDown();
        \Difra\Ajaxer::refresh();
    }
}
