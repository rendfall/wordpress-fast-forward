<?php

class FastForward_PostTypes {

    /**
     * @return $this
     */
    public function registerPostType($name, $base = 'posttypes'){
        $path = get_stylesheet_directory() . "/$base/$name.php";
        if(file_exists($path)){
            include($path);
        }

        return $this;
    }


}