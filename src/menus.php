<?php 

/**
 * @example
 * FastForward::Menus()
 *     ->registerMenus(array(
 *         'top_menu' => 'Main menu',
 *         'sidebar_menu' => 'Side menu',
 *         'footer_menu_1' => 'Footer menu',
 *         'footer_menu_2' => 'Second footer menu',
 *         'footer_menu_3' => 'Third footer menu'
 *     ));
 */
class FastForward_Menus {
    /**
     * Register new menus.
     **/
    public function registerMenus($menus) {
        register_nav_menus($menus);

        return $this;
    }

    /**
     * Choose menu by name (theme_location).
     **/
    public function getMenu($name, $args = array(), $echo = true) {
        $args = wp_parse_args($args, array(
            'theme_location' => $name,
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
        ));
        $menu = wp_nav_menu($args);

        if ($echo) {
            echo $menu;
            return true;
        } else {
            return $menu;
        }
    }
}
