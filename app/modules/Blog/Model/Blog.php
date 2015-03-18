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
 * @Source("blog")
 *
 * @BelongsTo("user_id", '\User\Model\User', "id", {
 *  "alias": "User"
 * })
 * @HasMany("id", '\Blog\Model\BlogCategories', "blog_id", {
 *  "alias": "BlogCategories"
 * })
 *

 * @method static \Blog\Model\Blog findFirst($parameters = null)
 */
class Blog extends AbstractModel
{
    const
        /**
         * Cache prefix.
         */
        CACHE_PREFIX = 'comment_id';

    // use trait Timestampable for creation_date and modified_date fields.
    use Timestampable;

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false, column="id", size="11")
     */
    public $id;

    /**
     * @Column(type="integer", nullable=false, column="user_id", size="11")
     */
    public $user_id;

    /**
     * @Column(type="string", nullable=false, column="title", size="255")
     */
    public $title;

    /**
     * @Column(type="text", nullable=true, column="body")
     */
    public $body;


    public function setBlogCategories(){
        if($this->getId() == null){

            return false;
        }

        foreach($_POST['categorie_id'] as $categorieID){
            $categorieID = explode('-', $categorieID);
            $source = $categorieID[0];
            $categorieID = $categorieID[1];
            $conditions = "blog_id = ?1 AND ".$source."_id = ?2";
            $parameters = array(1 => $this->getId(), 2 => $categorieID);

            if(BlogCategories::count(array($conditions, "bind" => $parameters)) > 0){
                //Update
            }else{

                //Save
                $blogCategories = new BlogCategories();
                $blogCategories->blog_id = $this->getId();
                if($source == Categories::getTableName()){
                    $blogCategories->setCategorieID($categorieID);
                }else{
                    $blogCategories->setCategorieItemsID($categorieID);
                }

                $returnValues = $blogCategories->save();
            }
        }
        return true;
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

    public function saveForm(){
        if($this->save()){
            $this->setBlogCategories();
            return true;
        }
    }
}