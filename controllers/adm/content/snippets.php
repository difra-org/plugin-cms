<?php

namespace Controller\Adm\Content;

use Difra\Ajaxer;
use Difra\CMS;
use Difra\Param;

/**
 * Class AdmContentSnippetsController
 */
class Snippets extends \Difra\Controller\Adm
{
    /**
     * /adm/content/snippets
     */
    public function indexAction()
    {
        $listNode = $this->root->appendChild($this->xml->createElement('snippetList'));
        $list = CMS\Snippet::getList();
        if (!empty($list)) {
            foreach ($list as $snip) {
                $snip->getXML($listNode);
            }
        }
    }

    /**
     * /adm/content/snippets/add
     */
    public function addAction()
    {
        $this->root->appendChild($this->xml->createElement('snippetAdd'));
    }

    /**
     * /adm/content/snippets/edit
     * @param Param\AnyInt $id
     * @throws Difra\View\HttpError
     */
    public function editAction(Param\AnyInt $id)
    {
        if (!$snippet = CMS\Snippet::getById($id->val())) {
            throw new \Difra\View\HttpError(404);
        }
        /** @var $editNode \DOMElement */
        $editNode = $this->root->appendChild($this->xml->createElement('snippetEdit', $snippet->getText()));
        $editNode->setAttribute('id', $snippet->getId());
        $editNode->setAttribute('name', $snippet->getName());
        $editNode->setAttribute('description', $snippet->getDescription());
    }

    /**
     * /adm/content/snippets/save
     * @param Param\AjaxString $name
     * @param Param\AjaxString $text
     * @param Param\AjaxInt $id
     * @param Param\AjaxString $description
     * @throws Difra\Exception
     */
    public function saveAjaxAction(
        Param\AjaxString $name,
        Param\AjaxString $text,
        Param\AjaxInt $id = null,
        Param\AjaxString $description = null
    ) {
        if ($id) {
            if (!$snippet = CMS\Snippet::getById($id->val())) {
                throw new \Difra\Exception('Snippet cannot be saved — bad ID');
            }
        } else {
            $snippet = CMS\Snippet::create();
        }
        $snippet->setName($name);
        $snippet->setDescription($description);
        $snippet->setText($text);
        Ajaxer::redirect('/adm/content/snippets');
    }

    /**
     * /adm/content/snippets/del
     * @param Param\AnyInt $id
     * @param Param\AjaxInt $confirm
     */
    public function delAjaxAction(Param\AnyInt $id, Param\AjaxInt $confirm = null)
    {
        if (!$snippet = \Difra\CMS\Snippet::getById($id->val())) {
            Ajaxer::redirect('/adm/cms/snippets');
        }
        if (!$confirm) {
            Ajaxer::confirm(
                \Difra\Locales::get('cms/adm/snippet/del-confirm1') .
                $snippet->getName() .
                \Difra\Locales::get('cms/adm/snippet/del-confirm2')
            );
            return;
        }
        $snippet->del();
        Ajaxer::close();
        Ajaxer::redirect('/adm/content/snippets');
    }
}
