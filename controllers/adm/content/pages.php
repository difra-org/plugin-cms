<?php

use Difra\Ajaxer;
use Difra\Plugins\CMS;

/**
 * Class AdmContentPagesController
 */
class AdmContentPagesController extends \Difra\Controller
{
    public function dispatch()
    {
        \Difra\View::$instance = 'adm';
    }

    /**
     * Pages list
     */
    public function indexAction()
    {
        $listNode = $this->root->appendChild($this->xml->createElement('CMSList'));
        \Difra\Plugins\CMS::getInstance()->getListXML($listNode);
    }

    /**
     * Add page form
     */
    public function addAction()
    {
        $this->root->appendChild($this->xml->createElement('CMSAdd'));
    }

    /**
     * Edit page form
     * @param \Difra\Param\AnyInt $id
     */
    public function editAction(\Difra\Param\AnyInt $id)
    {
        /** @var $editNode \DOMElement */
        $editNode = $this->root->appendChild($this->xml->createElement('CMSEdit'));
        \Difra\Plugins\CMS\Page::get($id->val())->getXML($editNode);
    }

    /**
     * Save page
     * @param \Difra\Param\AjaxString $title
     * @param \Difra\Param\AjaxString $tag
     * @param \Difra\Param\AjaxHTML $body
     * @param \Difra\Param\AjaxInt $id
     */
    public function saveAjaxAction(
        \Difra\Param\AjaxString $title,
        \Difra\Param\AjaxString $tag,
        \Difra\Param\AjaxHTML $body,
        \Difra\Param\AjaxInt $id = null
    ) {
        if ($id) {
            $page = \Difra\Plugins\CMS\Page::get($id->val());
        } else {
            $page = \Difra\Plugins\CMS\Page::create();
        }
        $page->setTitle($title->val());
        $page->setUri($tag->val());
        $page->setBody($body);
        Ajaxer::redirect('/adm/content/pages');
    }

    /**
     * Delete page
     * @param \Difra\Param\AnyInt $id
     * @param \Difra\Param\AjaxCheckbox $confirm
     */
    public function deleteAjaxAction(\Difra\Param\AnyInt $id, \Difra\Param\AjaxCheckbox $confirm = null)
    {
        if ($confirm and $confirm->val()) {
            \Difra\Plugins\CMS\Page::get($id->val())->delete();
            Ajaxer::close();
            Ajaxer::redirect('/adm/content/pages');
            return;
        }
        $page = \Difra\Plugins\CMS\Page::get($id->val());
        Ajaxer::display(
            '<span>'
            . \Difra\Locales::get('cms/adm/delete-page-confirm-1')
            . $page->getTitle()
            . \Difra\Locales::get('cms/adm/delete-page-confirm-2')
            . '</span>'
            . '<form action="/adm/content/pages/delete/' . $id . '" method="post" class="ajaxer">'
            . '<input type="hidden" name="confirm" value="1"/>'
            . '<input type="submit" value="Да"/>'
            . '<a href="#" onclick="ajaxer.close(this)" class="button">Нет</a>'
            . '</form>'
        );
    }
}
