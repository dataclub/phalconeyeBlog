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

use Blog\Controller\Grid\Backend\TagsGrid;

use Blog\Helper\BlogHelper;
use Blog\Form\Admin\Tags\Create;
use Blog\Form\Admin\Tags\Edit;
use Blog\Model\Tags;

/**
 * Admin tags controller.
 *
 * @category  PhalconEye
 * @package   Blog\Controller
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @RoutePrefix("/admin/module/blog/tags", name="admin-module-blog-tags-index")
 */
class AdminBlogTagsController extends BlogAbstractAdminController
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
     * @Get("/", name="admin-module-blog-tags-index")
     */
    public function indexAction()
    {
        $grid = new TagsGrid($this->view);
        if ($response = $grid->getResponse()) {
            return $response;
        }
    }

    /**
     * Create tags.
     *
     * @return void|ResponseInterface
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-module-blog-tags-create")
     */
    public function createAction()
    {
        $form = new Create();
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('New object created successfully!');
        return $this->response->redirect('admin/module/blog/tags');
    }

    /**
     * Edit tags.
     *
     * @param int $id Tags identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-module-blog-tags-edit")
     */
    public function editAction($id)
    {
        $item = Tags::findFirst($id);
        if (!$item) {
            return $this->response->redirect("admin/module/blog/tags");
        }

        $form = new Edit($item);
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('Object saved!');
        return $this->response->redirect('admin/module/blog/tags');
    }

    /**
     * Delete tags.
     *
     * @param int $id Tags identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-module-blog-tags-delete")
     */
    public function deleteAction($id)
    {
        $item = Tags::findFirst($id);
        if ($item) {
            if ($item->delete()) {
                $this->flashSession->notice('Object deleted!');
            } else {
                $this->flashSession->error($item->getMessages());
            }
        }

        return $this->response->redirect('admin/module/blog/tags');
    }



    /**
     * Suggest tags (via json).
     *
     * @return void
     *
     * @Get("/suggest", name="admin-module-blog-tags-suggest")
     */
    public function suggestAction()
    {
        $this->view->disable();
        $query = $this->request->get('query');
        if (!$query) {
            $this->response->setContent('[]')->send();

            return;
        }

        $results = Tags::find(
            [
                "conditions" => "name LIKE ?1",
                "bind" => [1 => '%' . $query . '%']
            ]
        );

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result->id,
                'label' => $result->name
            ];
        }

        $this->response->setContent(json_encode($data))->send();
    }

}

