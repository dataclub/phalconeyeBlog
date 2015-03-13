<?php
/*
  +------------------------------------------------------------------------+
  | PhalconEye CMS                                                         |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013-2014 PhalconEye Team (http://phalconeye.com/)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconeye.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
*/

namespace Blog\Model;

use Engine\Db\AbstractModel;
use Engine\Db\Model\Behavior\Sortable;

/**
 * Categories item.
 *
 * @category  PhalconEye
 * @package   Blog\Model
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @Source("categorie_items")
 * @BelongsTo("categorie_id", '\Blog\Model\Categories', "id", {
 *  "alias": "Categories"
 * })
 * @BelongsTo("parent_id", '\Blog\Model\CategoriesItem', "id", {
 *  "alias": "CategoriesItem"
 * })
 * @HasMany("id", "\Blog\Model\CategoriesItem", "parent_id", {
 *  "alias": "CategoriesItem"
 * })
 *
 * @method static \Blog\Model\CategoriesItem findFirst($parameters = null)
 */
class CategoriesItem extends AbstractModel
{
    use Sortable;

    const
        /**
         * Link target type - blank.
         */
        ITEM_TARGET_BLANK = '_blank',

        /**
         * Link target type - parent window.
         */
        ITEM_TARGET_PARENT = '_parent',

        /**
         * Link target type - top window.
         */
        ITEM_TARGET_TOP = '_top',

        /**
         * Tooltip position - top.
         */
        ITEM_TOOLTIP_POSITION_TOP = 'top',

        /**
         * Tooltip position - bottom.
         */
        ITEM_TOOLTIP_POSITION_BOTTOM = 'bottom',

        /**
         * Tooltip position - left.
         */
        ITEM_TOOLTIP_POSITION_LEFT = 'left',

        /**
         * Tooltip position - right.
         */
        ITEM_TOOLTIP_POSITION_RIGHT = 'right',

        /**
         * Icon position - right.
         */
        ITEM_ICON_POSITION_LEFT = 'left',

        /**
         * Icon position - right.
         */
        ITEM_ICON_POSITION_RIGHT = 'right';

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false, column="id", size="11")
     */
    public $id;

    /**
     * @Column(type="string", nullable=false, column="title", size="255")
     */
    public $title;

    /**
     * @Column(type="integer", nullable=false, column="categorie_id", size="11")
     */
    public $categorie_id;

    /**
     * @Column(type="integer", nullable=true, column="parent_id", size="11")
     */
    public $parent_id = null;

    /**
     * @Column(type="string", nullable=true, column="tooltip", size="255")
     */
    public $tooltip = null;

    /**
     * @Column(type="string", nullable=true, column="tooltip_position", size="10")
     */
    public $tooltip_position = 'top';

    /**
     * @Column(type="string", nullable=true, column="icon", size="255")
     */
    public $icon = null;

    /**
     * @Column(type="string", nullable=false, column="icon_position", size="10")
     */
    public $icon_position = 'left';

    /**
     * @Column(type="string", nullable=true, column="languages", size="150")
     */
    public $languages = null;

    /**
     * @Column(type="string", nullable=true, column="roles", size="150")
     */
    public $roles = null;

    /**
     * @Column(type="boolean", column="is_enabled")
     */
    public $is_enabled = true;

    /**
     * Return the related "Categories" entity.
     *
     * @param array $arguments Entity params.
     *
     * @return Categories
     */
    public function getCategories($arguments = [])
    {
        return $this->getRelated('Categories', $arguments);
    }

    /**
     * Return the related "Categories" entity.
     *
     * @param array $arguments Entity params.
     *
     * @return Categories
     */
    public function getCategorieItems($arguments = [])
    {
        return $this->getRelated('CategoriesItem', $arguments);
    }

    /**
     * Returns parent object, it can be MenuItem or Menu (if there is no parent_id).
     *
     * @return CategoriesItem|Categories
     */
    public function getParent()
    {
        if ($this->parent_id) {
            return self::findFirst($this->parent_id);
        } else {
            return Categories::findFirst($this->categorie_id);
        }
    }

    /**
     * Returns the value of field tooltip.
     *
     * @return string
     */
    public function getTooltip()
    {
        return str_replace('"', "'", $this->tooltip);
    }

    /**
     * Returns the value of field languages.
     *
     * @return string
     */
    public function getLanguages()
    {
        if (is_array($this->languages)) {
            return $this->languages;
        }

        return json_decode($this->languages);
    }

    /**
     * Prepare json string to object to interact.
     *
     * @return void
     */
    public function prepareLanguages()
    {
        if (!is_array($this->languages)) {
            $this->languages = json_decode($this->languages);
        }
    }

    /**
     * Returns the value of field roles.
     *
     * @return string
     */
    public function getRoles()
    {
        if (is_array($this->roles)) {
            return $this->roles;
        }

        return json_decode($this->roles);
    }

    /**
     * Check if categories item output is allowed.
     *
     * @return bool
     */
    public function isAllowed()
    {
        $valid = true;
        $viewer = User::getViewer();
        $roles = $this->getRoles();

        if (!empty($roles)) {
            $valid = in_array($viewer->role_id, $roles);
        }

        if (!$valid) {
            return false;
        }

        $valid = true;
        $language = $this->getDI()->get('session')->get('language', 'en');
        $languages = $this->getLanguages();

        if (!empty($languages)) {
            $valid = in_array($language, $languages);
        }

        return $valid;
    }

    /**
     * Logic before removal.
     *
     * @return bool
     */
    protected function beforeDelete()
    {
        $flag = true;
        if ($categorieItems = $this->getCategorieItems()) {
            foreach ($categorieItems as $item) {
                $flag = $item->delete();
                if (!$flag) {
                    break;
                }
            }
        }

        return $flag;
    }

    /**
     * Spell some logic after fetching.
     *
     * @return void
     */
    protected function afterFetch()
    {
        if (!empty($this->roles)) {
            $this->roles = json_decode($this->roles);
        }

        if (!empty($this->languages)) {
            $this->languages = json_decode($this->languages);
        }
    }

    /**
     * Logic before save.
     *
     * @return void
     */
    protected function beforeSave()
    {
        if (empty($this->roles)) {
            $this->roles = null;
        } elseif (is_array($this->roles)) {
            $this->roles = json_encode($this->roles);
        }

        if (empty($this->languages)) {
            $this->languages = null;
        } elseif (is_array($this->languages)) {
            $this->languages = json_encode($this->languages);
        }
    }
}