<?php 

class FastForward_Menus {

    /**
     * Function dynamically create new menus for enabled languages based on qTranslate settings.
     * Warning: if qTranslate will be disabled you must set menu location again.
     **/
    public function registerMenus($menus){
        $newMenus = array();

        if(FastForward::Helpers()->isQTranslateEnabled()){
            $langs = qtrans_getSortedLanguages();
            foreach($menus as $name => $label){
                foreach($langs as $lang){
                    $newMenus[$name.'_'.$lang] = $label.' ('.$lang.')';
                }
            }

        } else {
            $newMenus = $menus;
        }
 
        register_nav_menus($newMenus);

        return $this;
    }

    /**
     * Function dynamically choose menu by current language
     **/
    public function getMenu($name, $echo = true){

        $currentLang = FastForward::Helpers()->isQTranslateEnabled() ? '_'.qtrans_getLanguage() : '';

        $args = array(
            'theme_location' => $name.$currentLang,
            'menu' => '',
            'container' => false,
            'container_id' => '',
            'menu_class' => 'menu',
            'menu_id' => '',
            'echo' => false,
            'fallback_cb' => '', // Muy Importante! By default, this fallback returns list of ALL pages if menu wasn't found... WordPress, WHY U DO THAT!?
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>', // Remove UL, so you have to add this when echoing 
            'depth' => 0,
            'walker' => '' // Interesting one, but in most common cases useless. Read: http://codex.wordpress.org/Class_Reference/Walker
        ); $menu = wp_nav_menu($args);

        if($echo){
            echo $menu;
            return true;
        } else {
            return $menu;
        }
    }



}