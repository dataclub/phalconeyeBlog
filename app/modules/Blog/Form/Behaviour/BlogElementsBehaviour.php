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

namespace Blog\Form\Behaviour;

use Blog\Form;
use Phalcon\DI;
use Phalcon\Mvc\View;
use Engine\Form\Behaviour;

/**
 * Elements behaviour.
 * Method for simple element creation.
 *
 * @category  PhalconEye
 * @package   Blog\Form\BlogElementsBehaviour
 * @author    Ivan Vorontsov <ivan.vorontsov@phalconeye.com>
 * @copyright 2013-2014 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 */
trait BlogElementsBehaviour
{
    use Behaviour\ElementsBehaviour;
    /**
     * MultiCheckbox element.
     *
     * @param string      $name           Element name.
     * @param string|null $label          Element label.
     * @param string|null $description    Element description.
     * @param array       $elementOptions Element value options.
     * @param mixed|null  $value          Element value.
     * @param array       $options        Element options.
     * @param array       $attributes     Element attributes.
     *
     * @return $this
     */
    public function addBlogMultiCheckbox(
        $name,
        $label = null,
        $description = null,
        $elementOptions = [],
        $value = null,
        array $options = [],
        array $attributes = []
    )
    {

        // Eine spezielle Klassenzuordnung, um die Div-Höhe einzuschränken
        $formElementClass = '';
        if(isset($attributes['form_element_class'])){
            $formElementClass = $attributes['form_element_class'];
            unset($attributes['form_element_class']);
        }

        $element = new Form\Element\MultiCheckbox($name, $options, $attributes);

        if (!$label) {
            $label = ucfirst($name);
        }

        $element
            ->setOption('label', $label)
            ->setOption('description', $description)
            ->setOption('elementOptions', $elementOptions)
            ->setOption('form_element_class', $formElementClass)
            ->setValue($value);
        $this->add($element);

        return $this;
    }
}