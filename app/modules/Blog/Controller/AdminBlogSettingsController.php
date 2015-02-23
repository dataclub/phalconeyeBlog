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

use Blog\Form\Admin\Setting\BlogSetting as BlogSettingForm;
use Blog\Model\BlogSettings;
use Core\Controller\AbstractAdminController;

/**
 * Admin settings.
 *
 * @category  PhalconEye
 * @package   Core\Controller
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2015 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @RoutePrefix("/admin/module/blog/bla", name="admin-module-blog-index2")
 */
class AdminBlogSettingsController extends AbstractAdminController
{
    public function init(){}

    /**
     * Index action.
     *
     * @return mixed
     *
     * @Route("/", methods={"GET", "POST"}, name="admin-module-blog-index2")
     */
    public function indexAction()
    {
        $form = new BlogSettingForm();
        $this->view->form = $form;

        if (!$this->request->isPost() || !$form->isValid()) {
            return;
        }

        $values = $form->getValues();
        BlogSettings::setSettings($values);

        $this->flash->success('Blog Settings saved!');
    }

}

