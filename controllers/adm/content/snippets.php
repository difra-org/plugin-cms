<?php

use Difra\Plugins\CMS;

/**
 * Class AdmContentSnippetsController
 */
class AdmContentSnippetsController extends \Difra\Controller
{
    public function dispatch()
    {
        \Difra\View::$instance = 'adm';
    }

    public function indexAction()
    {
        $listNode = $this->root->appendChild($this->xml->createElement('snippetList'));
        $list = \Difra\Plugins\CMS\Snippet::getList();
        if (!empty($list)) {
            foreach ($list as $snip) {
                $snip->getXML($listNode);
            }
        }
    }

    public function addAction()
    {
        $this->root->appendChild($this->xml->createElement('snippetAdd'));
    }

    /**
     * @param \Difra\Param\AnyInt $id
     * @throws Difra\View\HttpError
     */
    public function editAction(\Difra\Param\AnyInt $id)
    {
        if (!$snippet = \Difra\Plugins\CMS\Snippet::getById($id->val())) {
            throw new \Difra\View\HttpError(404);
        }
        /** @var $editNode \DOMElement */
        $editNode = $this->root->appendChild($this->xml->createElement('snippetEdit', $snippet->getText()));
        $editNode->setAttribute('id', $snippet->getId());
        $editNode->setAttribute('name', $snippet->getName());
        $editNode->setAttribute('description', $snippet->getDescription());
    }

    /**
     * @param \Difra\Param\AjaxString $name
     * @param \Difra\Param\AjaxString $text
     * @param \Difra\Param\AjaxInt $id
     * @param \Difra\Param\AjaxString $description
     * @throws Difra\Exception
     */
    public function saveAjaxAction(
        \Difra\Param\AjaxString $name,
        \Difra\Param\AjaxString $text,
        \Difra\Param\AjaxInt $id = null,
        \Difra\Param\AjaxString $description = null
    ) {
        if ($id) {
            if (!$snippet = \Difra\Plugins\CMS\Snippet::getById($id->val())) {
                throw new \Difra\Exception('Snippet cannot be saved — bad ID');
            }
        } else {
            $snippet = \Difra\Plugins\CMS\Snippet::create();
        }
        $snippet->setName($name);
        $snippet->setDescription($description);
        $snippet->setText($text);
        \Difra\Ajaxer::redirect('/adm/content/snippets');
    }

    /**
     * @param \Difra\Param\AnyInt $id
     * @param \Difra\Param\AjaxInt $confirm
     */
    public function delAjaxAction(\Difra\Param\AnyInt $id, \Difra\Param\AjaxInt $confirm = null)
    {
        if (!$snippet = \Difra\Plugins\CMS\Snippet::getById($id->val())) {
            \Difra\Ajaxer::redirect('/adm/cms/snippets');
        }
        if (!$confirm) {
            \Difra\Ajaxer::confirm(
                \Difra\Locales::get('cms/adm/snippet/del-confirm1') .
                $snippet->getName() .
                \Difra\Locales::get('cms/adm/snippet/del-confirm2')
            );
            return;
        }
        $snippet->del();
        \Difra\Ajaxer::close();
        \Difra\Ajaxer::redirect('/adm/content/snippets');
    }
}
