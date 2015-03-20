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
use Engine\Db\Model\Behavior\Timestampable;
use Phalcon\DI;
use Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Validator\Uniqueness;

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
 *
 * @HasMany("id", '\Blog\Model\BlogCategories', "blog_id", {
 *  "alias": "BlogCategories"
 * })
 *
 * @HasMany("id", '\Blog\Model\BlogTags', "tags_id", {
 *  "alias": "BlogTags"
 * })
 *
 * @HasMany("id", '\Blog\Model\Comments', "blog_id", {
 *  "alias": "Comments"
 * })
 *
 *

 * @method static \Blog\Model\Blog findFirst($parameters = null)
 */
class Blog extends AbstractModel
{
    public function initialize()
    {
    }

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

    /**
     * Populate BlogCategories[] array with categories to save
     * @return BlogCategories
     */
    public function populateBlogCategories(){
        if($this->getId() == null){
            return false;
        }

        $categories = [];
        if(isset($_POST['blogCategories']))
        foreach($_POST['blogCategories'] as $key => $value){
            $value = explode('-', $value);
            $source = $value[0];
            $categorieID = $value[1];

            $parameters = array(
                "blog_id = ?1 AND ".$source."_id = ?2",
                "bind" => array(1 => $this->getId(), 2 => $categorieID)
            );

            if(BlogCategories::count($parameters) == 0){
                $blogCategories = new BlogCategories();
                $blogCategories->setBlogID($this->getId());
                if($source == Categories::getTableName()){
                    $blogCategories->setCategorieID($categorieID);
                }else{
                    $blogCategories->setCategorieItemsID($categorieID);
                }
                array_push($categories, $blogCategories);
            }
        }

        return $categories;
    }

    public function populateBlogTags(){
        if($this->getId() == null){
            return false;
        }

        $tags = [];
        if(isset($_POST['blogTags']))
            foreach($_POST['blogTags'] as $key => $tags_id){
                $parameters = array(
                    "blog_id = ?1 AND tags_id = ?2",
                    "bind" => array(1 => $this->getId(), 2 => $tags_id)
                );

                if(BlogTags::count($parameters) == 0){
                    $blogTags = new BlogTags();
                    $blogTags->setBlogID($this->getId());
                    $blogTags->setTagsID($tags_id);
                    array_push($tags, $blogTags);
                }
            }

        return $tags;
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
            $blogCategories = $this->populateBlogCategories();
            $blogCategoriesFlag = BlogCategories::saveBlogCategories($blogCategories);

            $blogTags = $this->populateBlogTags();
            $blogTagsFlag = BlogTags::saveBlogTags($blogTags);
            return $blogCategoriesFlag && $blogTagsFlag;
        }
        return false;
    }

    public function updateForm(){
        $blogCategories = $this->populateBlogCategories();
        $blogCategoriesFlag = BlogCategories::saveBlogCategories($blogCategories);

        $blogTags = $this->populateBlogTags();
        $blogTagsFlag = BlogTags::saveBlogTags($blogTags);

        return $blogCategoriesFlag && $blogTagsFlag;
    }


    /**
     * Delete all related data, so the blog can be deleted
     *
     * @return bool
     */
    protected function beforeDelete()
    {
        if($this->getId() == null){
            return;
        }

        $blogCategoriesFlag = BlogCategories::find(array('blog_id='.$this->getId()))->delete();
        $commentsFlag = Comments::find(array('blog_id='.$this->getId()))->delete();
        $blogTags = BlogTags::find(array('blog_id='.$this->getId()))->delete();

        return $blogCategoriesFlag && $commentsFlag && $blogTags;
    }

    /**
     * Remove all related data to save it again
     */
    protected function beforeSave()
    {
        if($this->getId() == null){
            return;
        }
        BlogCategories::find(array('blog_id='.$this->getId()))->delete();
        BlogTags::find(array('blog_id='.$this->getId()))->delete();
    }
}