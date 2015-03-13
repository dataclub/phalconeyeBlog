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
