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

use Core\Api\Acl;
use Engine\Db\AbstractModel;
use Engine\Db\Model\Behavior\Timestampable;
use Phalcon\DI;
use Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use User\Model\User;

/**
 * @category  PhalconEye
 * @package   Blog\Model
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @Source("blog_categories")
 *
 * @HasMany("blog_id", '\Blog\Model\Blog', "id", {
 *  "alias": "Blog"
 * })
 *
 * @HasMany("categories_id", '\Blog\Model\Categories', "id", {
 *  "alias": "Categories"
 * })
 *
 * @HasMany("categorie_items_id", '\Blog\Model\CategoriesItem', "id", {
 *  "alias": "CategoriesItem"
 * })
 * @method static \Blog\Model\BlogCategories findFirst($parameters = null)
 */
class BlogCategories extends AbstractModel
{
    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false, column="id", size="11")
     */
    public $id;

    /**
     * @Column(type="integer", nullable=false, column="blog_id", size="11")
     */
    public $blog_id;

    /**
     * @Column(type="integer", nullable=true, column="categories_id", size="11")
     */
    public $categories_id;

    /**
     * @Column(type="integer", nullable=true, column="categorie_items_id", size="11")
     */
    public $categorie_items_id;

    public function setBlogID($id){
        $di = $this->getId();
        if ($di === null || !empty($id)) {
            $this->blog_id = $id;

        }
    }

    public function setCategorieID($id){
        $di = $this->getId();
        if ($di === null || !empty($id)) {
            $this->categories_id = $id;

        }
    }

    public function setCategorieItemsID($id){
        $di = $this->getId();
        if ($di === null || !empty($id)) {
            $this->categorie_items_id = $id;

        }
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

        return $this->validationHasFailed() !== true;
    }
}