<?php
namespace Blog\Helper;

/**
 * BasicEnum class.
 *
 * @category  PhalconEye
 * @package   Blog\Helper
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */

abstract class BasicEnum {
    private static $constCacheArray = NULL;

    public static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }

        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict = true);
    }

    public static function getConstantKeys(){
        return array_keys(self::getConstants());
    }

    public static function getConstantValues(){
        return array_values(self::getConstants());
    }
}


/**
 * BlogPlaceholders class.
 *
 * @category  PhalconEye
 * @package   Blog\Helper
 * @author    Djavid Rustamov <nsxgdesigns@googlemail.com>
 * @copyright 2015-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
abstract class BlogPlaceholders extends BasicEnum
{
    const content = 'blog-content';
    const recentPosts = 'blog-recent-posts';
    const recentComments = 'blog-recent-comments';
    const categories = 'blog-categories';
    const archives = 'blog-archives';
    const tags = 'blog-tags';
}