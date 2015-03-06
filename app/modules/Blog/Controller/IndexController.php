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

use Blog\Controller\Grid\Frontend\BlogContentGrid;
use Blog\Controller\Grid\Frontend\BlogRecentPostsGrid;
use Blog\Controller\Grid\Frontend\BlogRecentCommentsGrid;
use Blog\Controller\Grid\Frontend\BlogCategoriesGrid;
use Blog\Controller\Grid\Frontend\BlogArchivesGrid;
use Blog\Controller\Grid\Frontend\BlogTagsGrid;

use Blog\Helper\BlogPlaceholders;
use Phalcon\DI;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Phalcon\Mvc\ViewInterface;

/**
 * Index controller.
 *
 * @category  PhalconEye
 * @package   Blog\Controller
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 *
 * @RoutePrefix("/blog", name="blog")
 */
class IndexController extends BlogAbstractController
{
    /**
     * @param $placeHolder
     * @return BlogCategoriesGrid|BlogContentGrid|BlogRecentCommentsGrid|BlogRecentPostsGrid|null|\Phalcon\Http\ResponseInterface|string|void
     */
    private function getBlogContent($placeHolder){
        switch($placeHolder){
            case BlogPlaceholders::content:
                return $this->getContent();
            case BlogPlaceholders::recentPosts:
                return $this->getRecentPosts();
            case BlogPlaceholders::recentComments:
                return $this->getRecentComments();
            case BlogPlaceholders::categories:
                return $this->getCategories();
            case BlogPlaceholders::archives:
                return $this->getArchives();
            case BlogPlaceholders::tags:
                return $this->getTags();
        }
    }

    /**
     * Module index action.
     *
     * @return void
     *
     * @Route("/", methods={"GET"}, name="blog")
     * @Route("/index", methods={"GET"}, name="blog")
     */
    public function indexAction()
    {
        $page = $this->request->getQuery("page", "int");
        $tableID = $this->request->getQuery("table_id", "string");

        $placeHolder = BlogPlaceholders::getConstants();
        if($page != null){
            //If response was returned (page number was clicked)
            foreach($placeHolder as $key => $gridName){
                if(strpos($tableID, $gridName) !== false){
                    return $this->getBlogContent($gridName);
                }
            }
            return;
        }

        //Replace content to placeholders of dynamic page
        $this->setView('blogView');
        $content = $this->view->content;

        foreach ($content as $cIndex => $cValue) {
            foreach ($cValue as $rIndex => $rValue) {
                foreach ($placeHolder as $key => $value) {
                    $rValue = str_replace('{{' . $key . '}}', $this->getBlogContent($value), $rValue);
                }
                $cValue[$rIndex] = $rValue;
            }
            $content[$cIndex] = $cValue;
        }

        $this->view->content = $content;
        $this->renderView($this->view, false);
    }

    private function getContent(){
        $contentGrid = new BlogContentGrid($this->view);
        if ($contentResponse = $contentGrid->getResponse()) {
            return $contentResponse;
        }
        $contentGrid = $contentGrid->render();
        return $contentGrid;
/*
        $blogPosts = Blog::query()
            ->leftJoin('User\Model\User', 'User\Model\User.id = Blog\Model\Blog.user_id')
            //->where('Common\WideZike\Models\UsersFollowers.followerId = :userId:', array('userId' => $user->getId()))
            ->columns(array(
                'Blog\Model\Blog.title',
                'Blog\Model\Blog.creation_date',
                //'Blog\Model\Blog.categorie',
                'Blog\Model\Blog.body',
                'User\Model\User.username'))
            ->orderBy('Blog\Model\Blog.modified_date DESC')
            ->execute();
*/
    }

    private function getRecentPosts(){
        $recentPostsGrid = new BlogRecentPostsGrid($this->view);
        if ($recentPostsResponse = $recentPostsGrid->getResponse()) {
            return $recentPostsResponse;
        }
        $recentPostsGrid = $recentPostsGrid->render();
        return $recentPostsGrid;
    }

    private function getRecentComments(){
        $recentCommentsGrid = new BlogRecentCommentsGrid($this->view);
        if ($recentCommentsResponse = $recentCommentsGrid->getResponse()) {
            return $recentCommentsResponse;
        }
        $recentCommentsGrid = $recentCommentsGrid->render();
        return $recentCommentsGrid;
    }

    private function getCategories(){
        $categoriesGrid = new BlogCategoriesGrid($this->view);
        if ($categoriesResponse = $categoriesGrid->getResponse()) {
            return $categoriesResponse;
        }
        $categoriesGrid = $categoriesGrid->render();
        return $categoriesGrid;
    }

    private function getArchives(){
        $archivesGrid = new BlogArchivesGrid($this->view);
        if ($archivesResponse = $archivesGrid->getResponse()) {
            return $archivesResponse;
        }
        $archivesGrid = $archivesGrid->render();
        return $archivesGrid;
    }

    private function getTags(){
        $tagsGrid = new BlogTagsGrid($this->view);
        if ($tagsResponse = $tagsGrid->getResponse()) {
            return $tagsResponse;
        }
        $tagsGrid = $tagsGrid->render();
        return $tagsGrid;
    }
}
