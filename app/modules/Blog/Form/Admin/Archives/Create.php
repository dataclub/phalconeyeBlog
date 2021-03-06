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

namespace Blog\Form\Admin\Archives;

use Blog\Model\Archives;
use Blog\Form\BlogForm;
use Engine\Db\AbstractModel;

/**
 * Create Archives.
 *
 * @category  PhalconEye
 * @package   Blog\Form\Admin\Archives
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class Create extends BlogForm
{
    /**
     * Create form.
     *
     * @param AbstractModel $entity Entity object.
     */
    public function __construct(AbstractModel $entity = null)
    {
        parent::__construct();

        if (!$entity) {
            $entity = new Archives();
        }

        $this->addEntity($entity);
    }

    /**
     * Initialize form.
     *
     * @return void
     */
    public function initialize()
    {
        $this
            ->setTitle('Blog Creation')
            ->setDescription('Create new blog post.');

        $content = $this->addContentFieldSet()
            ->addText('title', null, null, null, [], ['autocomplete' => 'off'])
            ->addCkEditor('body')
            ->addSelect('user_id', 'User', 'Select user', User::find(), null, ['using' => ['id', 'username']]);

        $this->addFooterFieldSet()
            ->addButton('create')
            ->addButtonLink('cancel', 'Cancel', 'admin/module/blog/archives');

        $content
            ->setRequired('title')
            ->setRequired('body');
    }
}