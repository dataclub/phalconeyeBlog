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

/**
 * Index controller.
 *
 * @category PhalconEye\Module
 * @package  Blog\Controller
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 *
 * @RoutePrefix("/blog", name="blog")
 */
class IndexController extends BlogAbstractController
{
    /**
     * Module index action.
     *
     * @return void
     *
     * @Route("/", methods={"GET"}, name="blog")
     */
    public function indexAction()
    {
        $view = $this->getView('blogView');

        $content = $view->content;
        $delimiters = array(
            'blog_content' => 'newContent',
            'blog_recent_posts' => 'recentPosts',
            'blog_recent_comments' => 'recentComments',
            'blog_archives' => 'archives',
            'blog_categories' => 'categories',
            'blog_tags' => 'tags'
        );
        foreach($content as $cIndex => $cValue){
            foreach($cValue as $rIndex => $rValue){
                foreach ($delimiters as $key => $value) {
                    $rValue = str_replace('{{'.$key.'}}', $value, $rValue);
                }
                $cValue[$rIndex] = $rValue;
            }
            $content[$cIndex] = $cValue;
        }

        $view->setVars(array('page' => $view->page, 'content' => $content), false);
        $this->renderView($view);

    }
}
