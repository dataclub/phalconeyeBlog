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
use Blog\Controller\Grid\Backend\BlogGrid;
use Blog\Form\Admin\Comments\Create;
use Blog\Form\Admin\Comments\Edit;
use Blog\Model\Blog;

use Core\Form\EntityForm;

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
 * @RoutePrefix("/admin/module/blog/comments", name="admin-blog-module-comments-index")
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
        $this->view->navigation = BlogHelper::getNavigation();
    }

    /**
     * Init controller.
     *
     * @return void|ResponseInterface
     *
     * @Get("/", name="admin-blog-module-comments-index")
     */
    public function indexAction()
    {
        if($this->view->headerNavigation){
            $this->view->headerNavigation->setActiveItem('admin/module/blog');
        }
        
        $grid = new CommentsGrid($this->view);
        if ($response = $grid->getResponse()) {
            return $response;
        }
    }

    /**
     * Edit menu.
     *
     * @param int $id Menu identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-blog-module-comments-edit")
     */
    public function editAction($id)
    {
        $item = Menu::findFirst($id);
        if (!$item) {
            return $this->response->redirect(['for' => "admin-menus"]);
        }

        $form = new Edit($item);
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('Object saved!');
        return $this->response->redirect(['for' => "admin-menus"]);
    }

    /**
     * Delete menu.
     *
     * @param int $id Menu identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-blog-module-comments-delete")
     */
    public function deleteAction($id)
    {
        $item = Menu::findFirst($id);
        if ($item) {
            if ($item->delete()) {
                $this->flashSession->notice('Object deleted!');
            } else {
                $this->flashSession->error($item->getMessages());
            }
        }

        return $this->response->redirect(['for' => "admin-menus"]);
    }




    /**
     * Order menu items (via json).
     *
     * @return void
     *
     * @Post("/order", name="admin-blog-module-comments-order")
     */
    public function orderAction()
    {
        $order = $this->request->get('order', null, []);
        foreach ($order as $index => $id) {
            $this->db->update(MenuItem::getTableName(), ['item_order'], [$index], "id = {$id}");
        }
        $this->view->disable();
    }

    /**
     * Suggest menus (via json).
     *
     * @return void
     *
     * @Get("/suggest", name="admin-blog-module-comments-suggest")
     */
    public function suggestAction()
    {
        $this->view->disable();
        $query = $this->request->get('query');
        if (!$query) {
            $this->response->setContent('[]')->send();

            return;
        }

        $results = Menu::find(
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

    /**
     * Clear menu items cache.
     *
     * @return void
     */
    protected function _clearMenuCache()
    {
        $cache = $this->getDI()->get('cacheOutput');
        $prefix = $this->config->application->cache->prefix;
        $widgetKeys = $cache->queryKeys($prefix . WidgetController::CACHE_PREFIX);
        foreach ($widgetKeys as $key) {
            $cache->delete(str_replace($prefix, '', $key));
        }
    }
}

