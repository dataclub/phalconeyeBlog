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

namespace Blog\Helper;

use Engine\Navigation;
use Core\Model\Settings;
use Engine\Helper;
use Phalcon\Tag;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\DI;

/**
 * BlogHelper class.
 *
 * @category  PhalconEye
 * @package   Blog\Helper
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class BlogHelper extends Helper
{
    /**
     * Get setting by name.
     *
     * @param string     $name    Setting name.
     * @param null|mixed $default Default value.
     *
     * @return null|string
     */
    public function get($name, $default = null)
    {
        return Settings::getSetting($name, $default);
    }

    public static function getNavigation(){
        $_REQUEST['_url_'] = substr($_GET['_url'], 1, strlen($_GET['_url']));
        $navigation = new Navigation();
        $navigationItems = self::setNavigationItems();
        $navigation->setItems($navigationItems);
        $navigation->setActiveItem($_REQUEST['_url_']);

        return $navigation;
    }

    static function setNavigationItems(){
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

        return $navigationItems;
    }

    /**
     * @param Builder $builder
     */
    public static function getQuery($builder){
        $intermediate = $builder->getQuery()->parse();
        $dialect      = DI::getDefault()->get('db')->getDialect();
        return $dialect->select($intermediate);
    }
}