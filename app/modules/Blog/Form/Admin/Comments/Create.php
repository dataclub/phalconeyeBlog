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

namespace Blog\Form\Admin\Comments;

use Blog\Model\Blog;
use Blog\Model\Comments;

use Blog\Form\BlogForm;
use Engine\Db\AbstractModel;
/**
 * Create comments
 *
 * @category  PhalconEye
 * @package   Blog\Form\Admin\Comments
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
            $entity = new Comments();
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
            ->setTitle('Comments Creation')
            ->setDescription('Create new comments post');


        $content = $this->addContentFieldSet()
            ->addText('name', 'Name', 'Give your full name', null, [], ['autocomplete' => 'off'])
            ->addText('email', 'E-Mail', 'Give your email', null, [], ['autocomplete' => 'on'])
            ->addTextArea('body', 'Content', 'Put your content here')
            ->addCheckbox('is_published', 'Publish ?', 'To publish check the item', 1, true, false)
            ->addSelect('blog_id', 'Blog', 'Select blog', Blog::find(), null, ['using' => ['id', 'title']]);

        $this->addFooterFieldSet()
            ->addButton('create')
            ->addButtonLink('cancel', 'Cancel', 'admin/module/blog/comments');

        $content
            ->setRequired('name')
            ->setRequired('email')
            ->setRequired('body')
            ->setRequired('blog_id')
        ;
    }
}