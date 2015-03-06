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
        $navigation = new Navigation();
        $navigationItems = self::setNavigationItems();
        $navigation->setItems($navigationItems);

        return $navigation;
    }

    static function setNavigationItems(){
        $navigationItems = [
            'index' => [
                'href' => 'admin/module/blog',
                'title' => 'Blog',
                'prepend' => '<i class="glyphicon glyphicon-list"></i>'
            ],
            'categories' => [
                'href' => 'admin/module/blog/categories',
                'title' => 'Categories',
                'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
            ],
            'tags' => [
                'href' => 'admin/module/blog/tags',
                'title' => 'Tags',
                'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
            ],
            'comments' => [
                'href' => 'admin/module/blog/comments',
                'title' => 'Comments',
                'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
            ],
            1 => [
                'href' => 'javascript:;',
                'title' => '|'
            ]
        ];

        switch($_GET['_url']){
            case '/admin/module/blog':
                $navigationItems['create'] = [
                    'href' => 'admin/module/blog/create',
                    'title' => 'Create new post',
                    'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
                ];

                break;
            case '/admin/module/blog/categories':
                $navigationItems['create'] = [
                    'href' => 'admin/module/blog/categories/create',
                    'title' => 'Create new categorie',
                    'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
                ];
                break;
            case '/admin/module/blog/tags':
                $navigationItems['create'] = [
                    'href' => 'admin/module/blog/tags/create',
                    'title' => 'Create new tags',
                    'prepend' => '<i class="glyphicon glyphicon-plus-sign"></i>'
                ];
                break;
        }


        return $navigationItems;
    }

}