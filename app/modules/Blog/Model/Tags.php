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

/**
 * Tags
 *
 * @category  PhalconEye
 * @package   Blog\Model
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @Source("tags")
 *
 * @HasMany("id", '\Blog\Model\BlogTags', "tags_id", {
 *  "alias": "BlogTags"
 * })
 *
 * @method static \Blog\Model\Tags findFirst($parameters = null)
 */
class Tags extends AbstractModel
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
     * Return the related "BlogTags" entity.
     *
     * @param array $arguments Entity params.
     *
     * @return BlogTags[]
     */
    public function getBlogTags($arguments = [])
    {
        return $this->getRelated('BlogTags', $arguments);
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


    /**
     * Logic before removal
     *
     * @return void
     */
    public function beforeDelete() {
        $this->getBlogTags()->delete();
    }
}