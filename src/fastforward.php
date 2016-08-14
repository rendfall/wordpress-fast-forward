<?php

class FastForward {
    /**
     * String Version of FastForward.
     * 
     * @var string
     */
    public static $VERSION = '1.1';

    /**
     * Cache for parts of engine.
     * 
     * @var array
     */
    protected static $_cache = array();

    /**
     * Build classname from filename.
     * 
     * @param string $filename
     * @return string
     */
    protected static function fileToClassName($filename) {
        return implode('', array_map('ucfirst', explode('-', $filename)));
    }

    /**
     * Build the required object instance.
     * 
     * @param string $name
     * @param boolean $fresh Whether to get a fresh copy; will not be cached and won't override current copy in cache.
     */
    protected static function factory($name, $fresh = false) {
        if (isset(self::$_cache[$name]) && !$fresh) {
            return self::$_cache[$name];
        }

        $class = get_called_class() . '_' . self::fileToClassName($name);
        $class = str_replace('/', '_', $class);

        if (!class_exists($class)) {
            require_once(dirname(__FILE__) . "/{$name}.php");
        }

        if ($fresh) {
            return new $class; 
        }
        
        self::$_cache[$name] = new $class;

        return self::$_cache[$name];
    }

    public static function Options($fresh = false) {
        return self::factory('options', $fresh);
    }

    public static function Posts($fresh = false) {
        return self::factory('posts', $fresh);
    }

    public static function Post($fresh = false) {
        return self::factory('post', $fresh);
    }

    public static function Menus($fresh = false) {
        return self::factory('menus', $fresh);
    }

    public static function Helpers($fresh = false) {
        return self::factory('helpers', $fresh);
    }

    public static function PostTypes($fresh = false) {
        return self::factory('posttypes', $fresh);
    }

    public static function Plugins($fresh = false) {
        return self::factory('plugins', $fresh);
    }

    public static function Widgets($fresh = false) {
        return self::factory('widgets', $fresh);
    }

    public static function Shortcodes($fresh = false) {
        return self::factory('shortcodes', $fresh);
    }
}
