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

use Blog\Helper\BlogHelper;
use Blog\Model\Blog;
use Engine\Form;
use Engine\Grid\GridItem;
use Phalcon\Db\Column;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\View;


/**
 * Categories grid.
 *
 * @category  PhalconEye
 * @package   Blog\Controller\Grid\Frontend
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class BlogCategoriesGrid extends FrontendBlogGrid
{
    private $layoutView = 'partials/grid/frontend/blog_categories/layout';
    private $itemView = 'partials/grid/frontend/blog_categories/item';
    private $bodyView = 'partials/grid/frontend/blog_categories/body';

    public function getLayoutView()
    {
        return $this->_resolveView($this->layoutView);
    }

    public function getItemView()
    {
        return $this->_resolveView($this->itemView);
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
        /** Erklärung */
        /*
         * Das SQL-Statement besteht aus zwei Teilen. Einer holt lediglich die Anzahl der Kategorien.
         * Das andere holt die Anzahl der Unterkategorien.
         *  select c.name, count(bc.categories_id) as categories_counted
         *  from categories c
         *  left join blog_categories bc on bc.categories_id = c.id
         *  where bc.categorie_items_id is null
         *  group by c.name
         *
         *  select c2.name, count(bc2.categorie_items_id) as subcategories_counted
         *  from categories c2
         *  left join categorie_items ci2 on ci2.categorie_id = c2.id
         *  left join blog_categories bc2 on bc2.categorie_items_id = ci2.id
         *  where bc2.categories_id is null
         *  group by c2.name

        Zusammengemergt kommt diese Abfrage raus, die Anzahl der Kategorien und Unterkategorien ausgibt.
            select c.name, count(bc.categories_id) +
            (
                select count(bc2.categorie_items_id)
                from categories c2
                left join categorie_items ci2 on ci2.categorie_id = c2.id
                left join blog_categories bc2 on bc2.categorie_items_id = ci2.id
                where c2.name = c.name
            ) as allCategries_counted
            from categories c
            left join blog_categories bc on bc.categories_id = c.id
            group by c.name

        Im Subselect-Where ist "c2.name = c.name" entscheidend, weil er nach zu suchenden Kategoriename eingrenzt

        Abfrage Subcategorien-Anzahl muss aufgrund mangelnder Funktionalität von Builder-Klasse
        (no support of subcategories) erweitert werden,
        um je blog die Anzahl der Subkategorien an die View zurückzugeben
        */
        /** Erklärung-ENDE*/

        /*
         * Query for categories and categorie_items
         */
        $builder = new Builder();
        $builder
            ->addFrom('Blog\Model\Categories', 'c')
            ->columns(['c.id', 'c.name'])
            ->orderBy('c.name desc');

        return $builder;
    }

    /**
     * Get count of categories for given blog-item
     *
     * @param GridItem $item
     * @return int
     */
    public function getCategoriesCount(GridItem $item){
        /**
         * Subquery for categorie_items
         */
        $builder = new Builder();
        $builder
            ->addFrom('Blog\Model\Categories', 'c')
            ->leftJoin('Blog\Model\BlogCategories', 'bc.categories_id = c.id', 'bc')
            ->columns(['count(bc.categories_id) as categories_counted'])
            ->where("c.name = '{$item->getObject()['name']}'");
        return $builder->getQuery()->execute()->getFirst()['categories_counted'];
    }

    /**
     * Get count of subcategories for given blog-item
     *
     * @param GridItem $item
     * @return int
     */
    public function getSubCategoriesCount(GridItem $item){
        /**
         * Subquery for categorie_items
         */
        $builder = new Builder();
        $builder
            ->addFrom('Blog\Model\Categories', 'c')
            ->leftJoin('Blog\Model\CategoriesItem', 'ci.categorie_id = c.id', 'ci')
            ->leftJoin('Blog\Model\BlogCategories', 'bc.categorie_items_id = ci.id', 'bc')
            ->columns(['count(bc.categorie_items_id) as subcategories_counted'])
            ->where("c.name = '{$item->getObject()['name']}'");
        return $builder->getQuery()->execute()->getFirst()['subcategories_counted'];
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
            0 => [
                'href' => 'module/blog/categories/'.$item['id'],
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
            ->addTextColumn('name', 'Name', [self::COLUMN_PARAM_TYPE => Column::TYPE_VARCHAR])
            ->addTextColumn('categories_counted', 'Counted', [self::COLUMN_PARAM_TYPE => Column::BIND_PARAM_INT])
        ;


    }
}