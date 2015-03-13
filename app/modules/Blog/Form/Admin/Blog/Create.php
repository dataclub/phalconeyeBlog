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

namespace Blog\Form\Admin\Blog;

use Blog\Model\Blog;
use Blog\Model\BlogCategories;
use Blog\Model\Categories;
use Core\Form\CoreForm;
use Core\Model\MenuItem;
use Engine\Db\AbstractModel;
use Engine\Form\Element\Checkbox;
use Engine\Form\Element\MultiCheckbox;
use Engine\Form\Element\Radio;
use Engine\Form\FieldSet;
use User\Model\User;

use Phalcon\Mvc\Model\Query\Builder;

use Engine\Form\AbstractElement;
/**
 * Create blog.
 *
 * @category  PhalconEye
 * @package   Blog\Form\Admin\Blog
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class Create extends CoreForm
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
            $entity = new Blog();

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


/*
        $html = $this
            ->addMultiCheckbox('categorie_id', 'Categories', 'Select categories', Categories::find(), null, ['using' => ['id', 'name']])
            ->render();
*/


        /*

        Blog::query()
            ->leftJoin('User\Model\User', 'User\Model\User.id = Blog\Model\Blog.user_id')
            //->where('Common\WideZike\Models\UsersFollowers.followerId = :userId:', array('userId' => $user->getId()))
            ->columns(array(
                'Blog\Model\Blog.title',
                'Blog\Model\Blog.creation_date',
                //'Blog\Model\Blog.categorie',
                'Blog\Model\Blog.body',
                'User\Model\User.username'))
            ->orderBy('Blog\Model\Blog.modified_date DESC')
            ->execute();
*/
/*

        $builder = new Builder();
        $builder
            ->addFrom('Core\Model\MenuItem', 'm')
            ->leftJoin('Core\Model\MenuItem', 'mi.parent_id = m.menu_id', 'mi')
            ->columns(['m.id', 'm.title']);

        $bla = $builder->getQuery();
        $bla2 = $bla->execute();

  */
        $menus = MenuItem::query()
            ->leftJoin('Core\Model\MenuItem', 'mi.parent_id = Core\Model\MenuItem.menu_id', 'mi')
            ->execute();
            ;


        $categories = $this->getCategories($menus);
        $content = $this->addContentFieldSet()
            ->addMultiCheckbox('categorie_id', 'Categories', 'Select categories', $menus, null, ['using' => ['id', 'title']])
            ->addText('title', 'Title', 'Blog title', null, [], ['autocomplete' => 'off'])
            ->addCkEditor('body', 'Content', 'Put your content here')
            ->addSelect('user_id', 'User', 'Select user', User::find(), null, ['using' => ['id', 'username']])
            ;



        $html = '';
        /** @var AbstractElement[] $elements */
        foreach($content->getElements() as $element){
            if($element->getName() == 'categorie_id'){
                $html = $element->render();
                $content->remove($element->getName());
                break;
            }
        }
        //$this->addHtml('header_options', '<br/><br/><h4>' . $this->di->get('i18n')->_('Options') . '</h4>');
        $u = $this->_resolveView('AdminBlog/bla', 'Blog');
        $this->addFooterFieldSet()->addHtml('asd', $u, ['grid' => $html]);




        $content
            ->setRequired('title')
            ->setRequired('body')
            //->setRequired('categorie_id')
            ->setRequired('user_id');

        $this->addFooterFieldSet()
            ->addButton('create')
            ->addButtonLink('cancel', 'Cancel', 'admin/module/blog');
    }

    private function getCategories($collection){

        $items = [];
        return $collection;
        foreach ($collection as $item) {
            array_push($items, $item);
        }

        return $items;
    }
}