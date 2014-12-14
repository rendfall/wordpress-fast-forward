<?php

class FastForward {

    /**
     * @return string the version of FastForward
     */
    public static function getVersion() {
        return '1.0';
    }

    protected static $_cache = array();


    protected static function fileToClassName($filename){
        return implode('', array_map('ucfirst', explode('-', $filename)));
    }


    /**
     * Build the required object instance
     * 
     * @param string  $object
     * @param boolean $fresh - whether to get a fresh copy; will not be cached and won't override current copy in cache.
     */
    protected static function factory($object, $fresh = false){
        if (isset(self::$_cache[$object]) && !$fresh) {
            return self::$_cache[$object];
        }

        $class = get_called_class().'_'.self::fileToClassName($object);
        $class = str_replace('/', '_', $class);

        if (!class_exists($class)) {
            require_once locate_template('app/' . $object . '.php');
        }

        if ($fresh) {
            return new $class; 
        }
        
        self::$_cache[$object] = new $class;
        return self::$_cache[$object];
    }


    public static function Options($fresh = false){
        return self::factory('options', $fresh);    
    }

    public static function Posts($fresh = false){
        return self::factory('posts', $fresh);    
    }

    public static function Post($fresh = false){
        return self::factory('post', $fresh);    
    }

    public static function Menus($fresh = false){
        return self::factory('menus', $fresh);    
    }

    public static function Helpers($fresh = false){
        return self::factory('helpers', $fresh);    
    }

    public static function PostTypes($fresh = false){
        return self::factory('posttypes', $fresh);    
    }

    public static function Widgets($fresh = false){
        return self::factory('widgets', $fresh);    
    }

    public static function Shortcodes($fresh = false){
        return self::factory('shortcodes', $fresh);    
    }
}

