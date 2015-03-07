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

namespace Blog\Controller;

use Blog\Helper\BlogHelper;
use Blog\Controller\Grid\Backend\BlogGrid;
use Blog\Form\Admin\Blog\Create;
use Blog\Form\Admin\Blog\Edit;
use Blog\Model\Blog;

use Core\Controller\AbstractAdminController;
use Core\Form\EntityForm;


/**
 * Admin blog.
 *
 * @category  PhalconEye
 * @package   Blog\Controller
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @RoutePrefix("/admin/module/blog", name="admin-module-blog-index")
 */
class AdminBlogController extends BlogAbstractAdminController
{
    /**
     * Init navigation.
     *
     * @return void
     */
    public function init()
    {

        //$this->view->navigation = BlogHelper::getNavigation();
    }

    private function setActiveItem(){
        if($this->view->headerNavigation){
            $this->view->headerNavigation->setActiveItem('admin/module/blog');
        }
    }

    /**
     * Main action.
     *
     * @return void
     *
     * @Get("/", name="admin-module-blog-index")
     */
    public function indexAction()
    {


        $grid = new BlogGrid($this->view);
        if ($response = $grid->getResponse()) {
            return $response;
        }

    }

    /**
     * Create blog.
     *
     * @return void|ResponseInterface
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-module-blog-create")
     */
    public function createAction()
    {
        $form = new Create();
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid(null, true)) {
            return;
        }

        $blog = $form->getEntity();
        $blog->save();

        $this->flashSession->success('New object created successfully!');
        return $this->response->redirect("admin/module/blog");


    }


    /**
     * Edit blog.
     *
     * @param int $id Blog identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-module-blog-edit")
     */
    public function editAction($id)
    {
        $item = Blog::findFirst($id);
        if (!$item) {
            return $this->response->redirect("admin/module/blog");
        }

        $form = new Edit($item);
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('Object saved!');
        return $this->response->redirect("admin/module/blog");
    }

    /**
     * Delete blog post.
     *
     * @param int $id Blog identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-module-blog-delete")
     */
    public function deleteAction($id)
    {
        $item = Blog::findFirst($id);
        if ($item) {
            if ($item->delete()) {
                $this->flashSession->notice('Object deleted!');
            } else {
                $this->flashSession->error($item->getMessages());
            }
        }

        return $this->response->redirect("admin/module/blog");
    }

}

