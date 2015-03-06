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

use Core\Controller\AbstractAdminController;
use Core\Controller\Grid\Admin\MenuGrid;
use Core\Form\Admin\Menu\Create;
use Core\Form\Admin\Menu\CreateItem;
use Core\Form\Admin\Menu\Edit;
use Core\Form\Admin\Menu\EditItem;
use Core\Model\Menu;
use Core\Model\MenuItem;
use Core\Model\Page;
use Engine\Widget\Controller as WidgetController;
use Phalcon\Http\ResponseInterface;

/**
 * Admin menus controller.
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
class AdminBlogCategoriesController extends AbstractAdminController
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
     * @Get("/", name="admin-blog-module-categories-index")
     */
    public function indexAction()
    {
        if($this->view->headerNavigation){
            $this->view->headerNavigation->setActiveItem('admin/module/blog');
        }

        $grid = new MenuGrid($this->view);
        if ($response = $grid->getResponse()) {
            return $response;
        }
    }

    /**
     * Create menu.
     *
     * @return void|ResponseInterface
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-blog-module-categories-create")
     */
    public function createAction()
    {
        $form = new Create();
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $this->flashSession->success('New object created successfully!');
        return $this->response->redirect(['for' => "admin-menus-manage", 'id' => $form->getEntity()->id]);
    }

    /**
     * Edit menu.
     *
     * @param int $id Menu identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-blog-module-categories-edit")
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
     * @Get("/delete/{id:[0-9]+}", name="admin-blog-module-categories-delete")
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
     * Create menu item.
     *
     * @return void
     *
     * @Route("/create-item", methods={"GET", "POST"}, name="admin-blog-module-categories-item")
     */
    public function createItemAction()
    {
        $form = new CreateItem();
        $this->view->form = $form;

        $data = [
            'menu_id' => $this->request->get('menu_id'),
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
            "menu_id = {$data['menu_id']}",
            'order' => 'item_order DESC'
        ];

        if (!empty($data['parent_id'])) {
            $orderData[0] .= " AND parent_id = {$data['parent_id']}";
        }

        $orderItem = MenuItem::findFirst($orderData);

        if ($orderItem->id != $item->id) {
            $item->item_order = $orderItem->item_order + 1;
        }

        $item->save();
        $this->_clearMenuCache();
        $this->resolveModal(['reload' => true]);
    }

    /**
     * Edit menu item.
     *
     * @param int $id Menu item identity.
     *
     * @return void|ResponseInterface
     *
     * @Route("/edit-item/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-blog-module-categories-item")
     */
    public function editItemAction($id)
    {
        $item = MenuItem::findFirst($id);

        $form = new EditItem($item);
        $this->view->form = $form;

        $data = [
            'menu_id' => $this->request->get('menu_id'),
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
        $this->_clearMenuCache();
        $this->resolveModal(['reload' => true]);
    }

    /**
     * Delete menu item.
     *
     * @param int $id Menu item identity.
     *
     * @return void|ResponseInterface
     *
     * @Get("/delete-item/{id:[0-9]+}", name="admin-blog-module-categories-item")
     */
    public function deleteItemAction($id)
    {
        $item = MenuItem::findFirst($id);
        $menuId = null;
        if ($item) {
            $menuId = $item->menu_id;
            $item->delete();
        }

        $parentId = $this->request->get('parent_id');
        $parentLink = '';
        if ($parentId) {
            $parentLink = "?parent_id={$parentId}";
        }
        if ($menuId) {
            return $this->response->redirect("admin/menus/manage/{$menuId}{$parentLink}");
        }

        return $this->response->redirect(['for' => "admin-menus"]);
    }

    /**
     * Order menu items (via json).
     *
     * @return void
     *
     * @Post("/order", name="admin-blog-module-categories-order")
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
     * @Get("/suggest", name="admin-blog-module-categories-suggest")
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

