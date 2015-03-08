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

use Blog\Controller\Grid\Backend\CommentsGrid;

use Blog\Helper\BlogHelper;
use Blog\Form\Admin\Comments\Create;
use Blog\Form\Admin\Comments\Edit;
use Blog\Model\Comments;

/**
 * Admin comments controller.
 *
 * @category  PhalconEye
 * @package   Blog\Controller
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @RoutePrefix("/admin/module/blog/comments", name="admin-module-blog-comments-index")
 */
class AdminBlogCommentsController extends BlogAbstractAdminController
{
    /**
     * Init controller before actions.
     *
     * @return void
     */
    public function init()
    {

    }

    /**
     * Init controller.
     *
     * @return void|ResponseInterface
     *
     * @Get("/", name="admin-module-blog-comments-index")
     */
    public function indexAction()
    {
        $grid = new CommentsGrid($this->view);
        if ($response = $grid->getResponse()) {
            return $response;
        }
    }

    /**
     * Create comments
     *
     * @return void|ResponseInterface
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-module-blog-comments-create")
     */
    public function createAction()
    {
        $form = new Create();
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('New object created successfully!');
        return $this->response->redirect('admin/module/blog/comments');
    }

    /**
     * Edit comments
     *
     * @param int $id Comments identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-module-blog-comments-edit")
     */
    public function editAction($id)
    {
        $item = Comments::findFirst($id);
        if (!$item) {
            return $this->response->redirect("admin/module/blog/comments");
        }

        $form = new Edit($item);
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('Object saved!');
        return $this->response->redirect('admin/module/blog/comments');
    }

    /**
     * Delete comments
     *
     * @param int $id Comments identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-module-blog-comments-delete")
     */
    public function deleteAction($id)
    {
        $item = Comments::findFirst($id);
        if ($item) {
            if ($item->delete()) {
                $this->flashSession->notice('Object deleted!');
            } else {
                $this->flashSession->error($item->getMessages());
            }
        }

        return $this->response->redirect('admin/module/blog/comments');
    }
}

