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
     * Return all related "Categories" and "CategoriesItems" as nested keys and values array
     * @param Categories|CategoriesItem $collection
     * @param $collection
     */
    public static function getCategories($collection = null, $using = ['id', 'name'], &$nestedArray = [], $depth = 0){
        $collection = $collection == null ? Categories::find() : $collection;

        if(!empty($using)){
            $keyAttribute =  array_shift($using);
            $valueAttribute = array_shift($using);

            foreach ($collection as $item) {
                /** @var \Phalcon\Mvc\Model $option */
                $keyValue = $item->readAttribute($keyAttribute);
                $nestedArray[$keyValue] = array(
                    'value' => $item->readAttribute($valueAttribute),
                    'class' => 'checkbox-depth'.$depth
                );


                if($item instanceof Categories){
                    $nulls = 'parent_id is null';
                    $categorieItems = CategoriesItem::find(array($nulls, "categorie_id='".$keyValue."'"));
                    Categories::getCategories($categorieItems, ['id', 'title'], $nestedArray, $depth+1);
                }else{
                    $categorieItems = CategoriesItem::find(array("parent_id='".$keyValue."'"));
                    Categories::getCategories($categorieItems, ['id', 'title'], $nestedArray, $depth+1);


                }


/*
                    $using = ['id', 'title'];
                    $keyAttribute2 =  array_shift($using);
                    $valueAttribute2 = array_shift($using);

                    foreach ($categorieItems as $value) {
                        $keyValue2 = $value->readAttribute($keyAttribute2);
                        $nestedArray[$keyValue2] = array(
                            'value' => $value->readAttribute($valueAttribute2),
                            'class' => 'checkbox-depth1'
                        )
                        ;
                    }
*/




            }
        }

        return $nestedArray;
    }
    /**
     * Logic before removal
     *
     * @return void
     */
    public function beforeDelete()
    {
        $this->getCategorieItems()->delete();
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
