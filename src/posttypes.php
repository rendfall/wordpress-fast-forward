<?php

/**
 * @example 
 * FastForward::PostTypes()
 *    ->registerPostType('product')
 *    ->registerPostType('slider');
 */
class FastForward_PostTypes {
    /**
     * @return $this
     */
    public function registerPostType($name, $base = 'posttypes') {
        $path = get_stylesheet_directory() . "/{$base}/{$name}.php";

        if (file_exists($path)) {
            require_once($path);
        }

        return $this;
    }
}
