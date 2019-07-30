<?php

namespace Controller\Adm\Content;

use
    Difra\Ajaxer,
    Difra\CMS,
    Difra\Param;

/**
 * Class AdmContentMenuController
 */
class Menu extends \Difra\Controller\Adm
{
    /**
     * Menu list
     */
    public function indexAction()
    {
        $listNode = $this->root->appendChild($this->xml->createElement('CMSMenuList'));
        CMS::getInstance()->getMenuListXML($listNode);
    }

    /**
     * Menu elements list
     * @param Param\AnyInt $menuId
     */
    public function viewAction(Param\AnyInt $menuId)
    {
        /** @var $menuNode \DOMElement */
        $menuNode = $this->root->appendChild($this->xml->createElement('CMSMenuItems'));
        $menu = CMS\Menu::get($menuId->val());
        $menu->getXML($menuNode);
        CMS::getInstance()->getMenuItemsXML($menuNode, $menuId->val());
    }

    /**
     * Add menu element form
     * @param Param\AnyInt $menuId
     * @param Param\NamedString $parent Parent node
     */
    public function addAction(Param\AnyInt $menuId, Param\NamedString $parent = null)
    {
        /** @var $addNode \DOMElement */
        $addNode = $this->root->appendChild($this->xml->createElement('CMSMenuItemAdd'));
        $addNode->setAttribute('menu', $menuId->val());
        $addNode->setAttribute('parent', $parent ? $parent->val() : null);
        $this->getEditXML($addNode, $menuId->val());
        CMS::getInstance()->getAvailablePagesXML($addNode, $menuId->val());
    }

    /**
     * Edit menu element form
     * @param Param\AnyInt $id
     */
    public function editAction(Param\AnyInt $id)
    {
        /** @var $editNode \DOMElement */
        $editNode = $this->root->appendChild($this->xml->createElement('CMSMenuItemEdit'));
        $menuItem = CMS\MenuItem::get($id->val());
        $menuItem->getXML($editNode);
        $this->getEditXML($editNode, $menuItem->getMenuId());
        CMS::getInstance()->getAvailablePagesForItemXML($editNode, $id->val());
    }

    /**
     * @param \DOMNode|\DOMElement $node
     * @param int $menuId
     */
    private function getEditXML($node, $menuId)
    {
        $menu = CMS\Menu::get($menuId);
        $node->setAttribute('depth', $menu->getDepth());
        $parentsNode = $node->appendChild($this->xml->createElement('parents'));
        CMS::getInstance()->getMenuItemsXML($parentsNode, $menu->getId());
    }

    /**
     * Save menu element: page
     * @param Param\AjaxInt $menu
     * @param Param\AjaxInt $page
     * @param Param\AjaxInt $id
     * @param Param\AjaxInt $parent
     */
    public function savepageAjaxAction(
        Param\AjaxInt $menu,
        Param\AjaxInt $page,
        Param\AjaxInt $id = null,
        Param\AjaxInt $parent = null
    ) {
        if ($id) {
            $item = CMS\MenuItem::get($id->val());
        } else {
            $item = CMS\MenuItem::create();
        }
        $item->setMenu($menu->val());
        $item->setParent($parent ? $parent->val() : null);
        $item->setPage($page->val());
        Ajaxer::redirect('/adm/content/menu/view/' . $menu->val());
    }

    /**
     * Save menu element: link
     * @param Param\AjaxInt $menu
     * @param Param\AjaxString $link
     * @param Param\AjaxString $label
     * @param Param\AjaxInt $id
     * @param Param\AjaxInt $parent
     */
    public function savelinkAjaxAction(
        Param\AjaxInt $menu,
        Param\AjaxString $link,
        Param\AjaxString $label,
        Param\AjaxInt $id = null,
        Param\AjaxInt $parent = null
    ) {
        $item = $id ? CMS\MenuItem::get($id->val()) : CMS\MenuItem::create();
        $item->setMenu($menu->val());
        $item->setParent($parent ? $parent->val() : null);
        $item->setLink($link);
        $item->setLinkLabel($label);
        Ajaxer::redirect('/adm/content/menu/view/' . $menu->val());
    }

    /**
     * Save empty menu element
     * @param Param\AjaxInt $menu
     * @param Param\AjaxString $label
     * @param Param\AjaxInt|null $id
     * @param Param\AjaxInt $parent
     */
    public function saveemptyAjaxAction(
        Param\AjaxInt $menu,
        Param\AjaxString $label,
        Param\AjaxInt $id = null,
        Param\AjaxInt $parent = null
    ) {
        if ($id) {
            $item = CMS\MenuItem::get($id->val());
        } else {
            $item = CMS\MenuItem::create();
        }
        $item->setMenu($menu->val());
        $item->setParent($parent ? $parent->val() : null);
        $item->setLinkLabel($label);
        Ajaxer::redirect('/adm/content/menu/view/' . $menu->val());
    }

    /**
     * Delete menu element
     * @param Param\AnyInt $id
     * @param Param\AjaxCheckbox $confirm
     */
    public function deleteAjaxAction(Param\AnyInt $id, \Difra\Param\AjaxCheckbox $confirm = null)
    {
        if (!$confirm or !$confirm->val()) {
            Ajaxer::display(
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
            CMS\MenuItem::get($id->val())->delete();
            Ajaxer::refresh();
            Ajaxer::close();
        }
    }

    /**
     * Move menu element up
     * @param Param\AnyInt $id
     */
    public function upAjaxAction(Param\AnyInt $id)
    {
        CMS\MenuItem::get($id->val())->moveUp();
        Ajaxer::refresh();
    }

    /**
     * Move menu element down
     * @param Param\AnyInt $id
     */
    public function downAjaxAction(Param\AnyInt $id)
    {
        CMS\MenuItem::get($id->val())->moveDown();
        Ajaxer::refresh();
    }
}
