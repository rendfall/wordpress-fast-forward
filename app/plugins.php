<?php

class FastForward_Plugins {
    /**
     * @return $this
     */
    public function registerPlugin($name, $base = 'plugins') {
        $path = get_stylesheet_directory() . "/$base/$name/init.php";

        if (file_exists($path)) {
            require_once($path);
        }

        return $this;
    }
}
