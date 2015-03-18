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

namespace Blog\Form;

use Blog\Form\Behaviour\BlogElementsBehaviour;
use Core\Form\CoreForm;
use Engine\Form\FieldSet;

/**
 * Main blog form.
 *
 * @category  PhalconEye
 * @package   Blog\Form
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class BlogForm extends CoreForm
{
    use BlogElementsBehaviour;
    /**
     * Resolve view.
     *
     * @param string $view   View path.
     * @param string $module Module name (capitalized).
     *
     * @return string
     */
    protected function _resolveView($view, $module = 'Blog')
    {
        return parent::_resolveView($view, $module);
    }

    /**
     * Add footer fieldset.
     *
     * @return FieldSet
     */
    public function addContentFieldSet()
    {

        $fieldSet = new FieldSet(self::FIELDSET_CONTENT);
        $this->addFieldSet($fieldSet);

        return $fieldSet;
    }

    /**
     * Add footer fieldset.
     *
     * @param bool $combined Combine elements?
     *
     * @return FieldSet
     */
    public function addFooterFieldSet($combined = true)
    {
        $fieldSet = new FieldSet(self::FIELDSET_FOOTER, null, ['class' => self::FIELDSET_FOOTER]);
        $fieldSet->combineElements($combined);
        $this->addFieldSet($fieldSet);

        return $fieldSet;
    }
}