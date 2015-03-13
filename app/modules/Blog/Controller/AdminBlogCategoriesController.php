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

use Engine\Navigation;
use Engine\Widget\Controller as WidgetController;
use Phalcon\Http\ResponseInterface;

use Blog\Helper\BlogHelper;
use Blog\Form\Admin\Categories\Create;
use Blog\Form\Admin\Categories\Edit;
use Blog\Form\Admin\Categories\CreateItem;
use Blog\Form\Admin\Categories\EditItem;

use Blog\Controller\Grid\Backend\CategoriesGrid;
use Blog\Model\Categories;
use Blog\Model\CategoriesItem;

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
        $grid = new CategoriesGrid($this->view);
        if ($response = $grid->getResponse()) {
            return $response;
        }
    }

    /**
     * Create categories
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

        return $this->response->redirect("admin/module/blog/categories");
    }

    /**
     * Edit categories
     *
     * @param int $id Categorie identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-module-blog-categories-edit")
     */
    public function editAction($id)
    {
        $item = Categories::findFirst($id);
        if (!$item) {
            return $this->response->redirect('admin/module/blog/categories');
        }

        $form = new Edit($item);
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('Object saved!');
        return $this->response->redirect('admin/module/blog/categories');
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
        $item = Categories::findFirst($id);
        if ($item) {
            if ($item->delete()) {
                $this->flashSession->notice('Object deleted!');
            } else {
                $this->flashSession->error($item->getMessages());
            }
        }

        return $this->response->redirect('admin/module/blog/categories');
    }

    /**
     * Manage categorie items
     *
     * @param int $id Categories identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/manage/{id:[0-9]+}", name="admin-module-blog-categories-manage")
     */
    public function manageAction($id)
    {
        $item = Categories::findFirst($id);
        if (!$item) {
            return $this->response->redirect('admin/module/blog/categories');
        }

        $parentId = $this->request->get('parent_id', 'int');
        if ($parentId) {
            $parent = CategoriesItem::findFirst($parentId);

            // Get all parents.
            $flag = true;
            $parents = [];
            $parents[] = $currentParent = $parent;
            while ($flag) {
                if ($currentParent->parent_id) {
                    $parents[] = $currentParent = $currentParent->getParent();
                } else {
                    $flag = false;
                }
            }
            $parents = array_reverse($parents);

            $this->view->parent = $parent;
            $this->view->parents = $parents;
            $this->view->items = $parent->getCategorieItems(['order' => 'item_order ASC']);
        } else {
            $this->view->items = $item->getCategorieItems(
                [
                    'parent_id IS NULL',
                    'order' => 'item_order ASC'
                ]
            );
        }

        $this->view->categories = $item;

    }



    /**
     * Create categorie item.
     *
     * @return void
     *
     * @Route("/create-item", methods={"GET", "POST"}, name="admin-module-blog-categories-create-item")
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

        $orderItem = CategoriesItem::findFirst($orderData);

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
     * @Route("/edit-item/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-module-blog-categories-edit-item")
     */
    public function editItemAction($id)
    {
        $item = CategoriesItem::findFirst($id);

        $form = new EditItem($item);
        $this->view->form = $form;

        $data = [
            'categorie_id' => $this->request->get('categorie_id'),
            'parent_id' => $this->request->get('parent_id'),
        ];

        $form->setValues($data);
        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $item = $form->getEntity();

        $item->save();
        $this->_clearCategorieCache();
        $this->resolveModal(['reload' => true]);
    }

    /**
     * Delete categories items
     *
     * @param int $id Categories item identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/delete-item/{id:[0-9]+}", name="admin-module-blog-categories-delete-item")
     */
    public function deleteItemAction($id)
    {
        $item = CategoriesItem::findFirst($id);
        $categorieId = null;
        if ($item) {
            $categorieId = $item->categorie_id;
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

        return $this->response->redirect('admin/module/blog/categories');
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
            $this->db->update(CategoriesItem::getTableName(), ['item_order'], [$index], "id = {$id}");
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

        $results = Categories::find(
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

