<?php

namespace Difra\CMS;

use Difra\Cache;
use Difra\Plugins\CMS;

/**
 * Class Menu
 * @package Difra\CMS
 */
class Menu
{
    /** @var int */
    private $id = null;
    /** @var string */
    private $name = '';
    /** @var string */
    private $description = '';
    /** @var int */
    private $depth = 1;
    /** @var bool */
    private $modified = false;

    /**
     * Create new menu
     * @static
     * @return Menu
     */
    public static function create()
    {
        return new self;
    }

    /**
     * Get menu by id
     * @param $id
     * @return Menu
     */
    public static function get($id)
    {
        static $menus = [];
        if (isset($menus[$id])) {
            return $menus[$id] ?: null;
        }
        $data = CMS::getDB()->fetchRow(
            'SELECT * FROM `cms_menu` WHERE `id`=:id LIMIT 1',
            ['id' => $id]
        );
        if (!$data) {
            $menus[$id] = false;
            return null;
        }
        return $menus[$id] = self::load($data);
    }

    /**
     * Get menu list
     * @static
     * @return Menu[]|bool
     */
    public static function getList()
    {
        try {
            $cache = Cache::getInstance();
            $cacheKey = 'cms_menu_list';
            if (!$data = $cache->get($cacheKey)) {
                $data = CMS::getDB()->fetch('SELECT * FROM `cms_menu` ORDER BY `name`');
                $cache->put($cacheKey, $data);
            }
            if (!is_array($data) or empty($data)) {
                return false;
            }
            $res = [];
            foreach ($data as $menuData) {
                $res[] = self::load($menuData);
            }
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->save();
    }

    /**
     * Save menu data
     */
    private function save()
    {
        if (!$this->modified) {
            return;
        }
        $db = CMS::getDB();
        $set = <<<SET
  `name`=:name,
  `description`=:description,
  `depth`=:depth
SET;
        $values = [
            'name' => $this->name,
            'description' => $this->description,
            'depth' => $this->depth
        ];
        if (!$this->id) {
            $db->query(
                "INSERT INTO `cms_menu` SET $set",
                $values
            );
            $this->id = $db->getLastId();
        } else {
            $values['id'] = $this->id;
            $db->query(
                "UPDATE `cms_menu` SET $set WHERE `id`=:id",
                $values
            );
        }
        self::clearCache();
        $this->modified = false;
    }

    /**
     * Clear menu caches
     * @static
     */
    public static function clearCache()
    {
        Cache::getInstance()->remove('cms_menu_list');
    }

    /**
     * Get menu data
     * @param \DOMElement $node
     * @return bool
     */
    public function getXML($node)
    {
        $node->setAttribute('id', $this->id);
        $node->setAttribute('name', $this->name);
        $node->setAttribute('description', $this->description);
        $node->setAttribute('depth', $this->depth);
        return true;
    }

    /**
     * Load menu object from database row
     * @param array $row
     * @return self
     */
    private static function load($row)
    {
        $menu = new self;
        $menu->id = $row['id'];
        $menu->name = $row['name'];
        $menu->description = $row['description'];
        $menu->depth = $row['maxdepth'];
        return $menu;
    }

    /**
     * Delete menu
     */
    public function delete()
    {
        $this->modified = false;
        CMS::getDB()->query("DELETE FROM `cms_menu` WHERE `id`=?", [$this->id]);
        self::clearCache();
    }

    /**
     * Get menu id
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
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }
}
