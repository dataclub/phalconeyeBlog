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

    public function getCategories(GridItem $item){
        /*
        $resultset = $this->getDI()->getModelsManager()->createBuilder()
            ->addFrom()
            ->join('RobotsParts');
        $bla = $item;
        */
        return 'blöa';
    }

    private function getCategoriesSource(){

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
                "CONCAT_WS(' ', DAY(b.creation_date), MONTHNAME(b.creation_date), ',', YEAR(b.creation_date)) as creation_date",
                "CONCAT_WS(' ', DAY(b.modified_date), MONTHNAME(b.modified_date), ',', YEAR(b.modified_date)) as modified_date",
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