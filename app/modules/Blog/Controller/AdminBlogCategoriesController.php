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

use Blog\Controller\Grid\Backend\CategorieGrid;


use Blog\Helper\BlogHelper;
use Blog\Form\Admin\Categorie\Create;
use Blog\Form\Admin\Categorie\Edit;
use Blog\Model\Categorie;
use Blog\Model\CategorieItem;

use Core\Form\EntityForm;
use Core\Model\Page;

/**
 * Admin categories controller.
 *
 * @category  PhalconEye
 * @package   Blog\Controller
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @RoutePrefix("/admin/module/blog/categories", name="admin-blog-module-categories-index")
 */
class AdminBlogCategoriesController extends BlogAbstractAdminController
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
     * @Get("/", name="admin-module-blog-categories-index")
     */
    public function indexAction()
    {


        $grid = new CategorieGrid($this->view);
        if ($response = $grid->getResponse()) {
            return $response;
        }
    }

    /**
     * Create categorie
     *
     * @return void|ResponseInterface
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-module-blog-categories-create")
     */
    public function createAction()
    {
        $form = new Create();
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('New object created successfully!');
        return $this->response->redirect(['for' => "admin-module-blog-categories-manage", 'id' => $form->getEntity()->id]);
    }

    /**
     * Edit categorie
     *
     * @param int $id Categorie identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-module-blog-categories-edit")
     */
    public function editAction($id)
    {
        $item = Categorie::findFirst($id);
        if (!$item) {
            return $this->response->redirect(['for' => "admin-module-blog-categories"]);
        }

        $form = new Edit($item);
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('Object saved!');
        return $this->response->redirect(['for' => "admin-module-blog-categories"]);
    }

    /**
     * Delete categorie
     *
     * @param int $id Categorie identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-module-blog-categories-delete")
     */
    public function deleteAction($id)
    {
        $item = Categorie::findFirst($id);
        if ($item) {
            if ($item->delete()) {
                $this->flashSession->notice('Object deleted!');
            } else {
                $this->flashSession->error($item->getMessages());
            }
        }

        return $this->response->redirect(['for' => "admin-module-blog-categories"]);
    }


    /**
     * Create categorie item.
     *
     * @return void
     *
     * @Route("/create-item", methods={"GET", "POST"}, name="admin-module-blog-categories-item")
     */
    public function createItemAction()
    {
        $form = new CreateItem();
        $this->view->form = $form;

        $data = [
            'categorie_id' => $this->request->get('categorie_id'),
            'parent_id' => $this->request->get('parent_id')
        ];

        $form->setValues($data);
        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $item = $form->getEntity();

        // Clear url type.
        if ($form->getValue('url_type') == 0) {
            $item->pageId = null;
        } else {
            $item->url = null;
        }

        // Set proper order.
        $orderData = [
            "categorie_id = {$data['categorie_id']}",
            'order' => 'item_order DESC'
        ];

        if (!empty($data['parent_id'])) {
            $orderData[0] .= " AND parent_id = {$data['parent_id']}";
        }

        $orderItem = CategorieItem::findFirst($orderData);

        if ($orderItem->id != $item->id) {
            $item->item_order = $orderItem->item_order + 1;
        }

        $item->save();
        $this->_clearCategorieCache();
        $this->resolveModal(['reload' => true]);
    }

    /**
     * Edit categorie items
     *
     * @param int $id Categorie item identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit-item/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-module-blog-categories-item")
     */
    public function editItemAction($id)
    {
        $item = CategorieItem::findFirst($id);

        $form = new EditItem($item);
        $this->view->form = $form;

        $data = [
            'categorie_id' => $this->request->get('categorie_id'),
            'parent_id' => $this->request->get('parent_id'),
            'url_type' => ($item->page_id == null ? 0 : 1),
        ];

        if ($item->page_id) {
            $page = Page::findFirst($item->page_id);
            if ($page) {
                $data['page_id'] = $page->id;
                $data['page'] = $page->title;
            }
        }

        $form->setValues($data);
        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $item = $form->getEntity();

        // Clear url type.
        if ($form->getValue('url_type') == 0) {
            $item->pageId = null;
        } else {
            $item->url = null;
        }

        $item->save();
        $this->_clearCategorieCache();
        $this->resolveModal(['reload' => true]);
    }

    /**
     * Delete categorie items
     *
     * @param int $id Categorie item identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/delete-item/{id:[0-9]+}", name="admin-module-blog-categories-item")
     */
    public function deleteItemAction($id)
    {
        $item = CategorieItem::findFirst($id);
        $categorieId = null;
        if ($item) {
            $categorieId = $item->categorieId;
            $item->delete();
        }

        $parentId = $this->request->get('parent_id');
        $parentLink = '';
        if ($parentId) {
            $parentLink = "?parent_id={$parentId}";
        }
        if ($categorieId) {
            return $this->response->redirect("admin/module/blog/categories/manage/{$categorieId}{$parentLink}");
        }

        return $this->response->redirect(['for' => "admin-module-blog-categories"]);
    }

    /**
     * Order categorie items (via json).
     *
     * @return void
     *
     * @Post("/order", name="admin-module-blog-categories-order")
     */
    public function orderAction()
    {
        $order = $this->request->get('order', null, []);
        foreach ($order as $index => $id) {
            $this->db->update(CategorieItem::getTableName(), ['item_order'], [$index], "id = {$id}");
        }
        $this->view->disable();
    }

    /**
     * Suggest categorie (via json).
     *
     * @return void
     *
     * @Get("/suggest", name="admin-module-blog-categories-suggest")
     */
    public function suggestAction()
    {
        $this->view->disable();
        $query = $this->request->get('query');
        if (!$query) {
            $this->response->setContent('[]')->send();

            return;
        }

        $results = Categorie::find(
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
     * Clear categorie items cache.
     *
     * @return void
     */
    protected function _clearCategorieCache()
    {
        $cache = $this->getDI()->get('cacheOutput');
        $prefix = $this->config->application->cache->prefix;
        $widgetKeys = $cache->queryKeys($prefix . WidgetController::CACHE_PREFIX);
        foreach ($widgetKeys as $key) {
            $cache->delete(str_replace($prefix, '', $key));
        }
    }
}

