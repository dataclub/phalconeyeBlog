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

namespace Blog\Controller\Grid\Backend;

use Engine\Form;
use Engine\Grid\GridItem;
use Phalcon\Db\Column;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\View;
use User\Model\User;

/**
 * Comments grid.
 *
 * @category  PhalconEye
 * @package   Blog\Controller\Grid\Backend
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class CommentsGrid extends BackendBlogGrid
{
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
            ->leftJoin('User\Model\User', 'b.user_id = u.id', 'u')
            ->columns(['b.id', 'b.title', 'u.username'])
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
            ->addTextColumn('id', 'ID', [self::COLUMN_PARAM_TYPE => Column::BIND_PARAM_INT])
            ->addTextColumn('title', 'Title', [self::COLUMN_PARAM_TYPE => Column::TYPE_VARCHAR])
            ->addSelectColumn(
                'u.username',
                'User',
                ['hasEmptyValue' => true, 'using' => ['username', 'username'], 'elementOptions' => User::find()],
                [
                    self::COLUMN_PARAM_USE_HAVING => false,
                    self::COLUMN_PARAM_USE_LIKE => false,
                    self::COLUMN_PARAM_OUTPUT_LOGIC =>
                        function (GridItem $item) {
                            return $item['username'];
                        }
                ]
            );

    }
}