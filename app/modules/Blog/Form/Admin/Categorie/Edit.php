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

namespace Blog\Form\Admin\Categorie;

/**
 * Edit categorie
 *
 * @category  PhalconEye
 * @package   Blog\Form\Admin\Categorie
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class Edit extends Create
{
    /**
     * Initialize form.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this
            ->setTitle('Edit Menu')
            ->setDescription('Edit this menu.');


        $this->getFieldSet(self::FIELDSET_FOOTER)
            ->clearElements()
            ->addButton('save')
            ->addButtonLink('cancel', 'Cancel', ['for' => 'admin/module/blog/categorie']);
    }
}