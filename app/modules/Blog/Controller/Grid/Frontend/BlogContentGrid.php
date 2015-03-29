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

namespace Blog\Controller\Grid\Frontend;

use Blog\Model\Blog;
use Blog\Model\BlogCategories;
use Blog\Model\Categories;
use Blog\Model\CategoriesItem;
use Blog\Model\Tags;
use Engine\Form;
use Engine\Grid\GridItem;
use Phalcon\Db\Column;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\View;

/**
 * Content grid.
 *
 * @category  PhalconEye
 * @package   Blog\Controller\Grid\Frontend
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class BlogContentGrid extends FrontendBlogGrid
{
    private $layoutView = 'partials/grid/frontend/blog_content/layout';
    private $itemView = 'partials/grid/frontend/blog_content/item';
    private $bodyView = 'partials/grid/frontend/blog_content/body';

    public function getLayoutView()
    {
        return $this->_resolveView($this->layoutView);
    }

    public function getItemView()
    {
        return $this->_resolveView($this->itemView);
    }

    public function hasActions(){

    }

    public function getCategoriesForFrontend(GridItem $item){
        $blog = Blog::findFirst('id='.$item->getObject()->id);

        $categoriesArray = [];
        foreach ($blog->getBlogCategories() as $blogCategories) {
            $categories = $this->getAllParents($blogCategories);
            array_push($categoriesArray, array(
                'value' => $categories->value,
                'title' => 'View all posts in ' . $categories->name,
                'url' => '/blog/categories/' . $categories->url
            ));

        }

        return $categoriesArray;
    }

    /**
     * @param BlogCategories $blogCategories
     * @param array $categoriesParent
     * @return Categories
     */
    private function getAllParents($blogCategories, &$categoriesParent = [])
    {
        $categoriesObject = null;
        if($blogCategories instanceof BlogCategories){

            if (!empty($blogCategories->categories_id)) {
                $categoriesObject = Categories::findFirst('id=' . $blogCategories->categories_id);
                array_push($categoriesParent, $categoriesObject->name);
            }else{
                $categoriesObject = CategoriesItem::findFirst('id=' . $blogCategories->categorie_items_id);
                array_push($categoriesParent, $categoriesObject->title);
                if(!empty($categoriesObject->parent_id)){
                    $this->getAllParents(CategoriesItem::findFirst('id='.$categoriesObject->parent_id), $categoriesParent);
                }else if(!empty($categoriesObject->categorie_id)){
                    $this->getAllParents(Categories::findFirst('id='.$categoriesObject->categorie_id), $categoriesParent);
                }
            }
        }else if($blogCategories instanceof CategoriesItem){
            $categoriesObject = $blogCategories;
            array_push($categoriesParent, $categoriesObject->title);
            if(!empty($categoriesObject->parent_id)){
                $this->getAllParents(CategoriesItem::findFirst('id='.$categoriesObject->parent_id), $categoriesParent);
            }else{
                $this->getAllParents(Categories::findFirst('id='.$categoriesObject->categorie_id), $categoriesParent);
            }
        }else if($blogCategories instanceof Categories){
            $categoriesObject = $blogCategories;
            array_push($categoriesParent, $categoriesObject->name);
        }

        $categories = new Categories();
        $categories->name = !empty($categoriesObject->name) ? $categoriesObject->name : $categoriesObject->title;
        $categories->value = $categories->name;
        $categories->url = implode('/', array_reverse($categoriesParent));
        return $categories;
    }

    private function getCategoriesCount(){
        $categoriesBuilder = new Builder();
        $categoriesBuilder
            ->addFrom('Blog\Model\Categories', 'c')
            ->leftJoin('Blog\Model\BlogCategories', 'c.id = bc.categories_id', 'bc')
            ->columns(['c.name', 'count(c.name)'])
            ->groupBy('c.name');
        $bla = $categoriesBuilder->getQuery();
    }

    public function getTagsForFrontend(GridItem $item){
        $blog = Blog::findFirst('id='.$item->getObject()->id);

        $tagsArray = [];
        foreach ($blog->getBlogTags() as $blogTags) {
            $tags = Tags::findFirst('id='.$blogTags->tags_id);
            array_push($tagsArray, array(
                'value' => $tags->name,
                'title' => 'View all posts in ' . $tags->name,
                'url' => '/blog/tags/'.$tags->name
            ));
        }

        return $tagsArray;
    }

    public function getTableBodyView()
    {
        return $this->_resolveView($this->bodyView);
    }

    /**
     * Get main select builder.
     *
     * @return Builder
     */
    public function getSource()
    {
        $builder = new Builder();
        $builder
            ->addFrom('Blog\Model\Blog', 'b')
            ->leftJoin('User\Model\User', 'u.id = b.user_id', 'u')
            ->leftJoin('Blog\Model\Comments', 'c.blog_id = b.id', 'c')
            ->columns([
                'b.id',
                'b.title',
                'b.body',
                "CONCAT_WS('', DAY(b.creation_date), '.', MONTHNAME(b.creation_date), ' ', YEAR(b.creation_date)) as creation_date",
                "CONCAT_WS('', DAY(b.modified_date), '.', MONTHNAME(b.modified_date), ' ', YEAR(b.modified_date)) as modified_date",
                'u.username',
                'count(c.id) as counted_comments'
            ])
            ->groupBy('b.id')
            ->orderBy('b.id DESC');


        return $builder;
    }

    /**
     * Get item action (Edit, Delete, etc).
     *
     * @param GridItem $item One item object.
     *
     * @return array
     */
    public function getItemActions(GridItem $item)
    {
        return [
            'Edit' => ['href' => ['for' => 'admin-module-blog-edit', 'id' => $item['id']]],
            'Delete' => [
                'href' =>
                    [
                        'for' => 'admin-module-blog-delete', 'id' => $item['id']
                    ],
                'attr' => ['class' => 'grid-action-delete']
            ]
        ];
    }

    /**
     * Initialize grid columns.
     *
     * @return array
     */
    protected function _initColumns()
    {
        $this
            ->addTextColumn('id', 'ID', [self::COLUMN_PARAM_TYPE => Column::BIND_PARAM_STR])
            ->addTextColumn('title', 'Title', [self::COLUMN_PARAM_TYPE => Column::BIND_PARAM_STR])
            ->addTextColumn('user', 'Username', [self::COLUMN_PARAM_TYPE => Column::BIND_PARAM_STR])
            ->addTextColumn('creation_date', 'Creation Date', [self::COLUMN_PARAM_TYPE => Column::TYPE_DATE])
            ->addTextColumn('modified_date', 'Modified Date', [self::COLUMN_PARAM_TYPE => Column::TYPE_DATE])
            ->addTextColumn('body', 'Body', [self::COLUMN_PARAM_TYPE => Column::BIND_PARAM_STR])


        ;

    }
}