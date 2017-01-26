<?php

namespace Difra\CMS;

use Difra\Cache;
use Difra\Envi;
use Difra\Exception;
use Difra\Param;
use Difra\CMS;

/**
 * Class Page
 * @package Difra\CMS
 */
class Page
{
    /** @var int */
    private $id = null;
    /** @var string */
    private $title = '';
    /** @var string */
    private $uri = '';
    /** @var string */
    private $body = '';
    /** @var bool */
    private $hidden = 0;
    /** @var bool */
    private $modified = false;
    /** @var bool */
    private $loaded = true;

    /**
     * Create new page
     * @static
     * @return Page
     */
    public static function create()
    {
        return new self;
    }

    /**
     * Get page by id
     * @static
     * @param int $id
     * @return Page
     */
    public static function get($id)
    {
        $page = new self;
        $page->id = $id;
        $page->loaded = false;
        return $page;
    }

    /**
     * Get pages list
     * @static
     * @param true|false|null $visible Visibility filter
     * @return self[]|bool
     */
    public static function getList($visible = null)
    {
        $where = [];
        if (!is_null($visible)) {
            $where[] = '`hidden`=' . ($visible ? '0' : '1');
        }
        try {
            $data =
                CMS::getDB()->fetch(
                    'SELECT * FROM `cms`' . (
                    !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '')
                    . ' ORDER BY `tag`'
                );
            if (!is_array($data) or empty($data)) {
                return false;
            }
            $res = [];
            $cache = Cache::getInstance();
            foreach ($data as $pageData) {
                $cache->put('cms_page_' . $pageData['id'], $pageData);
                $page = new self;
                $page->id = $pageData['id'];
                $page->title = $pageData['title'];
                $page->uri = $pageData['tag'];
                $page->body = $pageData['body'];
                $page->hidden = $pageData['hidden'];
                $page->loaded = true;
                $res[] = $page;
            }
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Detect page matching current URL
     * @static
     * @return int|false
     */
    public static function find()
    {
        $uri = Envi::getUri();
        $cache = Cache::getInstance();
        if (!$data = $cache->get('cms_tags')) {
            try {
                $data1 = CMS::getDB()->fetch('SELECT `id`,`tag` FROM `cms` WHERE `hidden`=0');
            } catch (Exception $ex) {
                return false;
            }
            $data = [];
            if (!empty($data1)) {
                foreach ($data1 as $v) {
                    $data[$v['tag']] = $v['id'];
                }
            }
            $cache->put('cms_tags', $data);
        }
        return isset($data[$uri]) ? $data[$uri] : false;
    }

    /**
     * деструктор
     */
    public function __destruct()
    {
        if ($this->modified and $this->loaded) {
            $this->save();
        }
    }

    /**
     * Save data
     */
    private function save()
    {
        $db = CMS::getDB();
        if (!$this->id) {
            $db->query(
                'INSERT INTO `cms` SET
                    `title`=:title,
                    `tag`=:tag,
                    `body`=:body,
                    `hidden`=:hidden',
                [
                    'title' => $this->title,
                    'tag' => $this->uri,
                    'body' => $this->body,
                    'hidden' => $this->hidden ? '1' : '0'
                ]
            );
            $this->id = $db->getLastId();
        } else {
            $db->query(
                'UPDATE `cms` SET
                    `title`=:title,
                    `tag`=:tag,
                    `body`=:body,
                    `hidden`=:hidden
                    WHERE `id`=:id',
                [
                    'id' => $this->id,
                    'title' => $this->title,
                    'tag' => $this->uri,
                    'body' => $this->body,
                    'hidden' => $this->hidden ? '1' : '0'
                ]
            );
            Cache::getInstance()->remove('cms_page_' . $this->id);
        }
        $this->modified = false;
        self::cleanCache();
    }

    /**
     * Clear cache
     */
    public static function cleanCache()
    {
        Cache::getInstance()->remove('cms_tags');
    }

    /**
     * Get page id
     * @return int
     */
    public function getId()
    {
        if (!$this->id) {
            $this->save();
        }
        return $this->id;
    }

    /**
     * Get page title
     * @return string
     */
    public function getTitle()
    {
        $this->load();
        return $this->title;
    }

    /**
     * Set page title
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->load();
        if ($title == $this->title) {
            return;
        }
        $this->title = $title;
        $this->modified = true;
    }

    /**
     * Load page data
     * @return bool
     */
    private function load()
    {
        if ($this->loaded) {
            return true;
        }
        if (!$this->id) {
            return false;
        }
        $cache = Cache::getInstance();
        if (!$data = $cache->get('cms_page_' . $this->id)) {
            $data = CMS::getDB()->fetchRow("SELECT * FROM `cms` WHERE `id`=?", [$this->id]);
            $cache->put('cms_page_' . $this->id, $data);
        }
        if (!$data) {
            return false;
        }
        $this->title = $data['title'];
        $this->uri = $data['tag'];
        $this->body = $data['body'];
        $this->hidden = $data['hidden'];
        $this->loaded = true;
        return true;
    }

    /**
     * Get page body
     * @return string
     */
    public function getBody()
    {
        $this->load();
        return $this->body;
    }

    /**
     * Set page body
     * @param \Difra\Param\AjaxHTML|\Difra\Param\AjaxSafeHTML|string $body
     * @throws \Difra\Exception
     */
    public function setBody($body)
    {
        $this->load();
        if ($body instanceof Param\AjaxHTML or $body instanceof Param\AjaxSafeHTML) {
            if (!$this->id) {
                $this->save();
            }
            if ($body->val(true) == $this->body) {
                return;
            }
            $body->saveImages(DIR_DATA . 'cms/img/' . $this->id, '/i/' . $this->id);
            $this->body = $body->val();
        } else {
            if ($body == $this->body) {
                return;
            }
            $this->body = $body;
        }
        $this->modified = true;
    }

    /**
     * Get page URI
     * @return string
     */
    public function getUri()
    {
        $this->load();
        return $this->uri;
    }

    /**
     * Set page URI
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->load();
        if (!strlen($uri) or $uri{0} != '/') {
            $uri = '/' . $uri;
        }
        if ($uri == $this->uri) {
            return;
        }
        $this->uri = $uri;
        $this->modified = true;
    }

    /**
     * Get page hidden status
     * @return bool
     */
    public function getHidden()
    {
        $this->load();
        return $this->hidden;
    }

    /**
     * Make page hidden
     * @param bool|int $hidden
     */
    public function setHidden($hidden)
    {
        $this->load();
        $hidden = $hidden ? '1' : '0';
        if ($hidden == $this->hidden) {
            return;
        }
        $this->hidden = $hidden;
        $this->modified = true;
    }

    /**
     * Get page data as XML node
     * @param \DOMElement|\DOMNode $node
     * @return bool
     */
    public function getXML($node)
    {
        if (!$this->load()) {
            return false;
        }
        $node->setAttribute('id', $this->id);
        $node->setAttribute('title', $this->title);
        $node->setAttribute('uri', $this->uri);
        $node->setAttribute('body', $this->body);
        $node->setAttribute('hidden', $this->hidden);
        return true;
    }

    /**
     * Delete page
     */
    public function delete()
    {
        $this->loaded = true;
        $this->modified = false;
        if ($this->id) {
            $path = DIR_DATA . 'cms/img/' . $this->id;
            if (is_dir($path)) {
                $dir = opendir($path);
                while (false !== ($file = readdir($dir))) {
                    if ($file{0} == '.') {
                        continue;
                    }
                    /** @noinspection PhpUsageOfSilenceOperatorInspection */
                    @unlink("$path/$file");
                }
                rmdir($path);
            }
        }
        CMS::getDB()->query("DELETE FROM `cms` WHERE `id`=?", [$this->id]);
        self::cleanCache();
    }
}
