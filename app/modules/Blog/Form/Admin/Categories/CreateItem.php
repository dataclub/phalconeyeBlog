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

namespace Blog\Form\Admin\Categories;


use Blog\Model\CategoriesItem;
use Core\Form\CoreForm;
use Core\Model\Language;
use Engine\Db\AbstractModel;
use Engine\Form\FieldSet;
use User\Model\Role;

/**
 * Create categories item.
 *
 * @category  PhalconEye
 * @package   Blog\Form\Admin\Categories
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class CreateItem extends CoreForm
{
    /**
     * Create form.
     *
     * @param AbstractModel $entity Entity object.
     */
    public function __construct(AbstractModel $entity = null)
    {
        parent::__construct();

        if (!$entity) {
            $entity = new CategoriesItem();
        }

        $this->addEntity($entity);
    }


    /**
     * Initialize form.
     *
     * @return void
     */
    public function initialize()
    {
        $this->setDescription('This categorie item will be available under categories or parent categorie item.');

        $content = $this->addContentFieldSet()
            ->addText('title')
            ->addCkEditor('tooltip')
            ->addSelect(
                'tooltip_position',
                'Tooltip position',
                null,
                [
                    CategoriesItem::ITEM_TOOLTIP_POSITION_TOP => 'Top',
                    CategoriesItem::ITEM_TOOLTIP_POSITION_BOTTOM => 'Bottom',
                    CategoriesItem::ITEM_TOOLTIP_POSITION_LEFT => 'Left',
                    CategoriesItem::ITEM_TOOLTIP_POSITION_RIGHT => 'Right'
                ]
            )
            ->addRemoteFile('icon', 'Select icon')
            ->addSelect(
                'icon_position',
                'Icon position',
                null,
                [
                    CategoriesItem::ITEM_ICON_POSITION_LEFT => 'Left',
                    CategoriesItem::ITEM_ICON_POSITION_RIGHT => 'Right'
                ]
            )
            ->addMultiSelect(
                'languages',
                'Languages',
                'Choose the language in which the categories item will be displayed.
                    If no one selected - will be displayed at all.',
                Language::find(),
                null,
                ['using' => ['language', 'name']]
            )
            ->addMultiSelect(
                'roles',
                'Roles',
                'If no value is selected, will be allowed to all (also as all selected).',
                Role::find(),
                null,
                ['using' => ['id', 'name']]
            )
            ->addCheckbox('is_enabled', 'Is enabled', null, 1, true, false)
            ->addHidden('page_id')
            ->addHidden('categorie_id')
            ->addHidden('parent_id');

        $this->_setValidation($content);
    }

    /**
     * Set form validation.
     *
     * @param FieldSet $content Content object.
     *
     * @return void
     */
    protected function _setValidation($content)
    {
        $content->setRequired('title');
    }
}