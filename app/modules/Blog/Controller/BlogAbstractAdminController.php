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
  | Author: Ivan Vorontsov <ivan.vorontsov@phalconeye.com>                 |
  +------------------------------------------------------------------------+
*/

namespace Blog\Controller;

use Core\Model\Package;
use Core\Model\Settings;
use Engine\Navigation;
use Engine\Package\Manager;
use Engine\Asset\Manager as AssetManager;

use Core\Controller\AbstractAdminController;
/**
 * Base admin controller.
 *
 * @category  PhalconEye
 * @package   Blog\Controller
 * @author    Ivan Vorontsov <ivan.vorontsov@phalconeye.com>
 * @copyright 2013-2014 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
abstract class BlogAbstractAdminController extends AbstractAdminController
{
    /**
     * Setup navigation.
     *
     * @return void
     */
    protected function _setupNavigation()
    {
        parent::_setupNavigation();

        $path = explode('/', $this->request->get('_url', 'string'));
        $tmpPath = $path;
        $activeItem = '';

        //Remove empty values from $path-Array
        foreach ($tmpPath as $key => $value) {
            if(empty($value)){
                unset($path[$key]);
            }
        }

        $limit = (count($path) > 3 ? 1 : 0);
        for ($i = 1, $count = count($path); $i <= $count - $limit && $i < 4; $i++) {
            $activeItem .= $path[$i] . '/';
        }
        $activeItem = substr($activeItem, 0, -1);

        $tmpNavi = $this->view->navigation;
        $this->view->headerNavigation->setActiveItem($activeItem);




        $navigation = new Navigation();

        $navigationItems = [
            'index' => [
                'href' => 'admin/module/blog',
                'title' => 'Blog',
                'title_single' => 'blog',
                'prepend' => '<i class="glyphicon glyphicon-list"></i>'
            ],
            'categories' => [
                'href' => 'admin/module/blog/categories',
                'title' => 'Categories',
                'title_single' => 'categorie',
                'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
            ],
            'tags' => [
                'href' => 'admin/module/blog/tags',
                'title' => 'Tags',
                'title_single' => 'tag',
                'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
            ],
            'comments' => [
                'href' => 'admin/module/blog/comments',
                'title' => 'Comments',
                'title_single' => 'comment',
                'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
            ],
            1 => [
                'href' => 'javascript:;',
                'title' => '|'
            ]
        ];

/*
        $choosenNavItem = [];
        foreach ($navigationItems as $key => $item) {
            if(is_numeric($key)){
                continue;
            }

            if(strpos($_REQUEST['_url_'], $key) !== false || $key == 'index'){
                $choosenNavItem['key'] = $key;
                $choosenNavItem[$key] = $item;
                break;
            }
        }
        $key = $choosenNavItem['key'];
        $navigationItems['create'] = [
            'href' => $choosenNavItem[$key]['href'].'/create',
            'title' => 'Create new ' . $choosenNavItem[$key]['title_single'],
            'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
        ];
*/
        $navigation->setItems($navigationItems);
        $navigation->setActiveItem($activeItem);
        $this->view->navigation = $navigation;
    }
}

