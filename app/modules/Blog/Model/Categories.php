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
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Engine\Db\Model\Behavior\Sortable;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * Categories
 *
 * @category  PhalconEye
 * @package   Blog\Model
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @Source("categories")
 *
 * @HasMany("id", '\Blog\Model\CategoriesItem', "categorie_id", {
 *  "alias": "CategoriesItem"
 * })
 *
 * @HasMany("id", '\Blog\Model\BlogCategories', "categories_id", {
 *  "alias": "BlogCategories"
 * })
 *
 * @method static \Blog\Model\Categories findFirst($parameters = null)
 */
class Categories extends AbstractModel
{

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false, column="id", size="11")
     */
    public $id;

    /**
     * @Column(type="string", nullable=false, column="name", size="255")
     */
    public $name;

    /**
     * Return the related "CategoriesItem" entity.
     *
     * @param array $arguments Entity params.
     *
     * @return CategoriesItem[]
     */
    public function getCategorieItems($arguments = [])
    {
        return $this->getRelated('CategoriesItem', $arguments);
    }

    /**
     * Return the related "BlogCategories" entity.
     *
     * @param array $arguments Entity params.
     *
     * @return BlogCategories[]
     */
    public function getBlogCategories($arguments = [])
    {
        return $this->getRelated('BlogCategories', $arguments);
    }



    /**
     * Return all related "Categories" and "CategoriesItems" as nested keys and values array
     * @param Categories|CategoriesItem $collection
     * @param $collection
     */
    public static function getCategories($collection = null, $using = ['id', 'name'], &$nestedArray = [], $depth = 0){
        $source = $collection == null ? Categories::getTableName() : CategoriesItem::getTableName();
        $collection = $collection == null ? Categories::find() : $collection;

        if(!empty($using)){
            $keyAttribute =  array_shift($using);
            $valueAttribute = array_shift($using);

            foreach ($collection as $item) {
                /** @var \Phalcon\Mvc\Model $option */
                $keyValue = $item->readAttribute($keyAttribute);
                $nestedArray[$source.'-'.$keyValue] = array(
                    'key' => $keyValue,
                    'value' => $item->readAttribute($valueAttribute),
                    'class' => 'checkbox-depth'.$depth
                );


                if($item instanceof Categories){
                    $categorieItems = CategoriesItem::find(array("parent_id is null and categorie_id='".$keyValue."'"));
                }else{
                    $categorieItems = CategoriesItem::find(array("parent_id='".$keyValue."'"));
                }
                Categories::getCategories($categorieItems, ['id', 'title'], $nestedArray, $depth+1);
            }
        }

        return $nestedArray;
    }

    /**
     * Return all values as [categories|categorie_items]-[id] array
     * @param $entity \Blog\Model\Blog
     * @return mixed
     */
    public static function getEntityValues($entity){
        $categorieValues = [];

        /** @var \Blog\Model\Categories $categories */
        foreach ($entity->getBlogCategories() as $blogCategories) {
            if (empty($blogCategories->categories_id)) {
                array_push($categorieValues, 'categorie_items-' . $blogCategories->categorie_items_id);
            } else {
                array_push($categorieValues, 'categories-' . $blogCategories->categories_id);
            }
        }

        return $categorieValues;
    }

    /**
     * Logic before removal
     *
     * @return void
     */
    public function beforeDelete()
    {
        $this->getCategorieItems()->delete();
        $this->getBlogCategories()->delete();
    }



    /**
     * Validations and business logic.
     *
     * @return bool
     */
    public function validation()
    {
        if ($this->_errorMessages === null) {
            $this->_errorMessages = [];
        }


        $this->validate(new Uniqueness(["field" => "name"]));

        return $this->validationHasFailed() !== true;
    }


}
