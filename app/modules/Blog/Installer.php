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

namespace Blog;

use Engine\Installer as EngineInstaller;

/**
 * Installer for Blog.
 *
 * @category  PhalconEye\Module
 * @package   Module
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2014-2015 PhalconEye Team
 * @license   New BSD License
 */
class Installer extends EngineInstaller
{
    CONST
        /**
         * Current package version.
         */
        CURRENT_VERSION = '1.0.2';

    /**
     * Used to install specific database entities or other specific action.
     *
     * @return void
     */
    public function install()
    {
        $this->runSqlFile(__DIR__ . '/Assets/sql/installation.sql');
    }

    /**
     * Used before package will be removed from the system.
     *
     * @return void
     */
    public function remove()
    {

    }

    /**
     * Used to apply some updates.
     *
     * @param string $currentVersion Current version name.
     *
     * @return mixed 'string' (new version) if migration is not finished, 'null' if all updates were applied
     */
    public function update($currentVersion)
    {
        return $currentVersion = null;
    }
}