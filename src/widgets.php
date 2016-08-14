<?php

/**
 * @example
 * FastForward::Widgets()
 *    ->registerSidebar('footer-3-cols')
 *    ->registerWidget('foot-column');
 */
class FastForward_Widgets {
    /**
     * @return $this
     */
    public function registerSidebar($name, $base = 'sidebars') {
        $path = get_stylesheet_directory() . "/$base/$name.php";

        if (file_exists($path)) {
            require_once($path);
        } 
        
        return $this;
    }

    /**
     * @return $this
     */
    public function registerWidget($name, $base = 'widgets') {
        $path = get_stylesheet_directory() . "/$base/$name.php";

        if (file_exists($path)) {
            require_once($path);
        } 
        
        return $this;
    }
}
