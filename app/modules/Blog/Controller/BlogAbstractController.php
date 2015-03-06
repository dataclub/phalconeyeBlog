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

use Core\Controller\AbstractController;
use Core\Helper\Renderer;
use Core\Model\Page;
use Engine\Behaviour\DIBehaviour;
use Engine\Exception;
use Phalcon\Db\Column;
use Phalcon\DI;
use Phalcon\Mvc\View;
use Engine\Asset\Manager as AssetManager;

/**
 * Base controller.
 *
 * @category  PhalconEye
 * @package   Blog\Controller
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 *
 * @property \Phalcon\Db\Adapter\Pdo    $db
 * @property \Phalcon\Cache\Backend     $cacheData
 * @property \Engine\Application        $app
 * @property \Engine\View               $view
 * @property \Engine\Asset\Manager      $assets
 * @property \Engine\Config             $config
 * @property \Phalcon\Translate\Adapter $i18n
 * @property DIBehaviour|DI             $di
 *
 * @method \Engine\DIBehaviour|\Phalcon\DI getDI()
 */
abstract class BlogAbstractController extends AbstractController
{
    protected function _setupAssets(){
        parent::_setupAssets();

        $this->di->get('assets')
            ->collection(AssetManager::DEFAULT_COLLECTION_CSS)
            ->addCss('assets/css/blog/paginator.css');

        //replace grid.js from core
        $parentJsPath = [];
        foreach ($this->di->get('assets')->collection(AssetManager::DEFAULT_COLLECTION_JS) as $key => $resource) {
            $jsPath =  $resource->getPath();
            if(strpos($jsPath, 'grid.js') !== false){
                $jsPath = str_replace('core', 'blog', $jsPath);
            }
            array_push($parentJsPath, $jsPath);
        }

        $this->assets->set(
            AssetManager::DEFAULT_COLLECTION_JS,
            $this->assets->getEmptyJsCollection()
        );

        foreach($parentJsPath as $path){
            $this->assets->addJs($path);
        }
    }
    public function setView($url){
        $page = null;
        if ($url !== null) {
            $page = Page::find(
                [
                    'conditions' => 'url=:url1: OR url=:url2: OR id = :url3:',
                    'bind' => ["url1" => $url, "url2" => '/' . $url, "url3" => $url],
                    'bindTypes' => [
                        "url1" => Column::BIND_PARAM_STR,
                        "url2" => Column::BIND_PARAM_STR,
                        "url3" => Column::BIND_PARAM_INT
                    ]
                ]
            )->getFirst();

        }

        // Resort content by sides.
        $content = [];
        $renderer = Renderer::getInstance($this->getDI());
        foreach ($page->getWidgets() as $widget) {
            $content[$widget->layout][] = $renderer->renderWidgetId($widget->widget_id, $widget->getParams());
        }

        $this->view->content = $content;
        $this->view->page = $page;
    }

    public function renderView($view, $backend = true){
        $view = $backend ? 'backend' : 'frontend';
        $this->view->pick('layouts/'.$view.'/page');
    }

    /**
     * @param $folder
     * @param $file
     * @param array $params
     * @return mixed
     */
    public function viewRenderer($folder, $file, $params = array()){

        $view = new View();
        $view->setViewsDir($this->view->getViewsDir());

        $view->setDI($this->getDI());
        $view->registerEngines(array(
            ".volt" => "Phalcon\Mvc\View\Engine\Volt",
        ));

        foreach($params as $key => $value){
            $view->setVar($key, $value);
        }

        $view->start();
        $view->render($folder, $file);
        $view->finish();
        return $view->getContent();
    }
}