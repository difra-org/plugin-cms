<?php

use Difra\Ajaxer;
use Difra\CMS;
use Difra\Param;

/**
 * Class AdmContentPagesController
 */
class AdmContentPagesController extends \Difra\Controller\Adm
{
    /**
     * Pages list
     */
    public function indexAction()
    {
        $listNode = $this->root->appendChild($this->xml->createElement('CMSList'));
        CMS::getInstance()->getListXML($listNode);
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
     * @param Param\AnyInt $id
     */
    public function editAction(Param\AnyInt $id)
    {
        /** @var $editNode \DOMElement */
        $editNode = $this->root->appendChild($this->xml->createElement('CMSEdit'));
        CMS\Page::get($id->val())->getXML($editNode);
    }

    /**
     * Save page
     * @param Param\AjaxString $title
     * @param Param\AjaxString $tag
     * @param Param\AjaxHTML $body
     * @param Param\AjaxInt $id
     */
    public function saveAjaxAction(
        Param\AjaxString $title,
        Param\AjaxString $tag,
        Param\AjaxHTML $body,
        Param\AjaxInt $id = null
    ) {
        if ($id) {
            $page = CMS\Page::get($id->val());
        } else {
            $page = CMS\Page::create();
        }
        $page->setTitle($title->val());
        $page->setUri($tag->val());
        $page->setBody($body);
        Ajaxer::redirect('/adm/content/pages');
    }

    /**
     * Delete page
     * @param Param\AnyInt $id
     * @param Param\AjaxCheckbox $confirm
     */
    public function deleteAjaxAction(Param\AnyInt $id, Param\AjaxCheckbox $confirm = null)
    {
        if ($confirm and $confirm->val()) {
            CMS\Page::get($id->val())->delete();
            Ajaxer::close();
            Ajaxer::redirect('/adm/content/pages');
            return;
        }
        $page = CMS\Page::get($id->val());
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
