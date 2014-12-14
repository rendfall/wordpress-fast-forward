<?php

class FastForward_Shortcodes {

    /**
     * @return $this
     */
    public function registerShortcode($name, $base = 'shortcodes'){
        $path = get_stylesheet_directory() . "/$base/$name.php";
        if(file_exists($path)){
            include($path);
        } 

        return $this;
    }


}