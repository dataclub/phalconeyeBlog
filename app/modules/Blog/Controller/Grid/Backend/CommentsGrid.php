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

use Blog\Model\Comments;
use Engine\Form;
use Engine\Grid\GridItem;
use Phalcon\Db\Column;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\View;
use User\Model\User;
use Blog\Model\Categories;

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
            ->addFrom('Blog\Model\Comments', 'c')
            ->leftJoin('Blog\Model\Blog', 'c.blog_id = b.id', 'b')
            ->columns([
                'c.id',
                'c.name',
                'c.email',
                'c.is_published',
                'DATE_FORMAT(c.creation_date, "%d. %M, %Y") as creation_date',
                'DATE_FORMAT(c.modified_date, "%d. %M, %Y") as modified_date',
            ])
            ->orderBy('c.creation_date DESC');

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
            'Edit' => ['href' => ['for' => 'admin-module-blog-comments-edit', 'id' => $item['id']]],
            'Delete' => [
                'href' =>
                    [
                        'for' => 'admin-module-blog-comments-delete', 'id' => $item['id']
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
            ->addTextColumn('name', 'Author', [self::COLUMN_PARAM_TYPE => Column::TYPE_VARCHAR])
            ->addTextColumn('email', 'E-Mail', [self::COLUMN_PARAM_TYPE => Column::TYPE_VARCHAR])
            ->addSelectColumn(
                'c.is_published',
                'Published?',
                [
                    'hasEmptyValue' => true,
                    'using' => ['is_published', 'is_published'],
                    'elementOptions' => ['No', 'Yes']
                ],
                [
                    self::COLUMN_PARAM_USE_HAVING => false,
                    self::COLUMN_PARAM_USE_LIKE => false,
                    self::COLUMN_PARAM_OUTPUT_LOGIC =>
                        function (GridItem $item) {
                            $translation = $this->getDI()->getI18n();
                            return $item['is_published'] == 1
                                ? $translation->query('Yes')
                                : $translation->query('No');
                        }
                ]
            )
            ->addTextColumn('creation_date', 'Creation Date', [self::COLUMN_PARAM_TYPE => Column::TYPE_VARCHAR]);

    }
}