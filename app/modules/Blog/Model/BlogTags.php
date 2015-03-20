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
 * @Source("blog_tags")
 *
 * @BelongsTo("blog_id", '\User\Model\Blog', "id", {
 *  "alias": "Blog"
 * })
 *
 * @BelongsTo("tags_id", '\User\Model\Tags', "id", {
 *  "alias": "Tags"
 * })
 *
 *
 * @method static \Blog\Model\Tags findFirst($parameters = null)
 */
class BlogTags extends AbstractModel
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
     * @Column(type="integer", nullable=false, column="tags_id", size="11")
     */
    public $tags_id;


    public function setBlogID($id){
        $di = $this->getId();
        if ($di === null || !empty($id)) {
            $this->blog_id = $id;

        }
    }

    public function setTagsID($id){
        $di = $this->getId();
        if ($di === null || !empty($id)) {
            $this->tags_id = $id;

        }
    }

    /**
     * Saves given $blogTags
     * @param $blogTags
     * @return bool
     */
    public static function saveBlogTags($blogTags){
        foreach ($blogTags as $tags) {
            if(!$tags->save()){
                return false;
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
}