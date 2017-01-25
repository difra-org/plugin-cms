<?php

/**
 * Snippets are short texts to be used in page layout. For example, copyrights, phone numbers, emails, etc.
 */

namespace Difra\CMS;

use Difra\Cache;
use Difra\Exception;
use Difra\CMS;

/**
 * Class Snippet
 * @package Difra\CMS
 */
class Snippet
{
    const CACHE_KEY = 'snippets';
    /** @var int */
    private $id = null;
    /** @var string */
    private $name = null;
    /** @var string */
    private $description = null;
    /** @var string */
    private $text = null;
    /** @var bool */
    private $isModified = false;

    /**
     * Get snippet by id
     * @static
     * @param int $id
     * @return self|null
     */
    public static function getById($id)
    {
        $data = CMS::getDB()->fetchRow('SELECT * FROM `cms_snippets` WHERE `id`=?', [$id]);
        return self::data2obj($data);
    }

    /**
     * Convert snippets array[] to Snippet[]
     * @static
     * @param array $data
     * @return Snippet|null
     */
    private static function data2obj($data)
    {
        if (empty($data)) {
            return null;
        }
        $snippet = new self;
        $snippet->id = $data['id'];
        $snippet->name = $data['name'];
        $snippet->description = $data['description'];
        $snippet->text = $data['text'];
        return $snippet;
    }

    /**
     * Get snippet by name
     * @static
     * @param string $name
     * @return Snippet|null
     */
    public static function getByName($name)
    {
        $data = CMS::getDB()->fetchRow('SELECT * FROM `cms_snippets` WHERE `name`=?', [$name]);
        return self::data2obj($data);
    }

    /**
     * Get all snippets as XML nodes
     * @static
     * @param \DOMNode $node
     */
    public static function getAllXML($node)
    {
        $cache = Cache::getInstance();
        $res = $cache->get(self::CACHE_KEY);
        if (!is_array($res)) {
            try {
                $res = CMS::getDB()->fetch("SELECT `id`, `name`, `text` FROM `cms_snippets`");
                $cache->put(self::CACHE_KEY, $res ? $res : []);
            } catch (Exception $ex) {
            }
        }
        if (!empty($res)) {
            foreach ($res as $data) {
                /** @var \DOMElement $sNode */
                $sNode = $node->appendChild($node->ownerDocument->createElement($data['name'], $data['text']));
                $sNode->setAttribute('id', $data['id']);
            }
        }
    }

    /**
     * Get snippets list
     * @static
     * @return self[]
     */
    public static function getList()
    {
        $data = CMS::getDB()->fetch('SELECT * FROM `cms_snippets`');
        $res = [];
        if (!empty($data)) {
            foreach ($data as $snip) {
                $res[] = self::data2obj($snip);
            }
        }
        return $res;
    }

    /**
     * Create snippet
     * @static
     * @return Snippet
     */
    public static function create()
    {
        return new self;
    }

    /**
     * Destructor
     * @throws Exception
     */
    public function __destruct()
    {
        if (!$this->isModified) {
            return;
        }
        if ($this->id) {
            CMS::getDB()->query(
                'UPDATE `cms_snippets` SET `name`=:name,`text`=:text,`description`=:description WHERE `id`=:id',
                [
                    'id' => $this->id,
                    'name' => $this->name,
                    'text' => $this->text,
                    'description' => $this->description
                ]
            );
        } else {
            CMS::getDB()->query(
                'INSERT INTO `cms_snippets` SET `name`=:name,`text`=:text,`description`=:description',
                [
                    'name' => $this->name,
                    'text' => $this->text,
                    'description' => $this->description
                ]
            );
        }
        $this->cleanCache();
    }

    /**
     * Clear cache
     */
    public static function cleanCache()
    {
        Cache::getInstance()->remove(self::CACHE_KEY);
    }

    /**
     * Get snippet id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get snippet name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set snippet name
     * @param string $name
     */
    public function setName($name)
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->isModified = true;
        }
    }

    /**
     * Get snippet text
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set snippet text
     * @param string $text
     */
    public function setText($text)
    {
        if ($text !== $this->text) {
            $this->text = $text;
            $this->isModified = true;
        }
    }

    /**
     * Get snippet as XML node
     * @param \DOMNode $node
     */
    public function getXML($node)
    {
        /** @var \DOMElement $sub */
        $sub = $node->appendChild($node->ownerDocument->createElement('snippet', $this->text));
        $sub->setAttribute('id', $this->id);
        $sub->setAttribute('name', $this->name);
        $sub->setAttribute('description', $this->description);
    }

    /**
     * Get snippet description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set snippet description
     * @param $description
     */
    public function setDescription($description)
    {
        if ($this->description !== $description) {
            $this->description = $description;
            $this->isModified = true;
        }
    }

    /**
     * Delete snippet
     * @throws Exception
     */
    public function del()
    {
        $this->isModified = false;
        CMS::getDB()->query('DELETE FROM `cms_snippets` WHERE `id`=?', [$this->id]);
    }
}
