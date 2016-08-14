<?php

/**
 * @example
 * FastForward::Options()
 *    ->setTimeZone('Europe/Warsaw')
 *    ->setTextDomain('FastForwardTextDomain')
 *    ->addThemeSupport('post-thumbnails')
 *    ->addThemeSupport('html5')
 *    ->registerCss('assets/stylesheets/app')
 *    ->registerJs('assets/javascripts/app')
 *    ->addTagsToPages()
 *    ->addCategoriesToPages()
 *    ->allowUnattach()
 *    ->removeAction('wp_head', 'wp_generator')
 *    ->removeAction('wp_head', 'feed_links', 2)
 *    ->removeAction('wp_head', 'feed_links_extra', 3);
 */
class FastForward_Options {
    private $scripts;
    private $styles;

    /**
     * Sets the time zone used by this theme.
     * This is a simple wrapper of PHP function date_default_timezone_set().
     * 
     * @param string $value the time zone used by this theme.
     * @see http://php.net/manual/en/function.date-default-timezone-set.php
     */
    public function setTimeZone($value) {
        date_default_timezone_set($value);

        return $this;
    }

    /**
     * @return $this
     */
    public function setTextDomain($name) {
        load_theme_textdomain($name, get_template_directory() . '/languages');

        return $this;
    }

    /**
     * @return $this
     */
    public function addThemeSupport($feature) {
        add_theme_support($feature);

        return $this;
    }

    public function _registerCss() {
        if ($this->styles) {
            foreach ($this->styles as $style) {
                wp_register_style(
                    $style['label'], 
                    $style['path'], 
                    $style['deps'], 
                    $style['version'], 
                    $style['media']
                );
                wp_enqueue_style($style['label']);
            }
        }
    }

    public function registerCss($styles) {
        if (is_admin()) {
            return $this;
        }

        $styles = is_array($styles) ? $styles : array($styles);

        foreach ($styles as $file) {
            $label = str_replace(".", "-", basename($file));
            $path = get_template_directory_uri().'/'.ltrim($file, '/') . '.css';

            $this->styles[] = array(
                'label' => $label, 
                'path' => $path, 
                'deps' => array(), 
                'version' => FastForward::$VERSION,
                'media' => 'all'
            );
        }

        add_action('wp_enqueue_scripts', array($this, '_registerCss'));
        return $this;
    }

    public function _registerJs() {
        if ($this->scripts) {
            foreach ($this->scripts as $script) {
                wp_register_script(
                    $script['label'],
                    $script['path'],
                    $script['deps'],
                    $script['version'],
                    $script['footer']
                );
                wp_enqueue_script($script['label']);
            }
        }
    }

    public function registerJs($scripts, $inFooter = true) {
        if (is_admin()) {
            return $this;
        }

        $scripts = is_array($scripts) ? $scripts : array($scripts);

        foreach ($scripts as $file) {
            $label = str_replace(".", "-", basename($file));
            $path = get_template_directory_uri() . '/' . ltrim($file, '/') . '.js';
    
            $this->scripts[] = array(
                'label' => $label, 
                'path' => $path, 
                'deps' => array(), 
                'version' => FastForward::$VERSION, 
                'footer' => $inFooter
            );
        }

        add_action('wp_enqueue_scripts', array($this, '_registerJs'));
        return $this;
    }


    public function addGoogleFont($args) {
        $label = str_replace('+', '-', strtok($args['family'], ':'));
        $basePath = (is_ssl() ? 'https' : 'http') . '://fonts.googleapis.com/css';

        wp_register_style($label, add_query_arg($args, $basePath, array(), null));
        wp_enqueue_style($label);

        return $this;
    }


    // TODO(rendfall): clean that shit below...
    public function addTagsToPages() {
        add_action('init', array($this, 'tags_for_pages'));
        add_action('pre_get_posts', array($this, 'tags_archives'));

        return $this;
    }

    public function addCategoriesToPages() {
        add_action('init', array($this, 'categories_for_pages'));
        add_action('pre_get_posts', array($this, 'categories_archives'));

        return $this;
    }

    function categories_for_pages() { 
        // TODO(rendfall): add custom taxonomy (dedicated categories to actual posttype)
        register_taxonomy_for_object_type('category','page');
    }

    function tags_for_pages() { 
        // TODO(rendfall): add custom taxonomy (dedicated tags to actual posttype)
        register_taxonomy_for_object_type('post_tag', 'page');
    }
    
    function categories_archives($wp_query) {
        if ($wp_query->get('category')) {
            $wp_query->set('post_type', 'any');
        }
    }

    function tags_archives($wp_query) {
        if ($wp_query->get('tag')) {
            $wp_query->set('post_type', 'any');
        }
    }

    public function allowUnattach() {
        add_action('admin_menu', array($this, 'unattach_init'));

        return $this;
    }

    function unattach_media_row_action($actions, $post) {
        if ($post->post_parent) {
            $url = admin_url('tools.php?page=unattach&noheader=true&&id=' . $post->ID);
            $actions['unattach'] = '<a href="' . esc_url( $url ) . '" title="' . __( "Unattach this media item.") . '">' . __( 'Unattach', 'FastForwardTextDomain') . '</a>';
        }

        return $actions;
    }

    /**
     * Action to set post_parent to 0 on attachment
     */
    function unattach_do_it() {
        global $wpdb;
        
        if (!empty($_REQUEST['id'])) {
            $wpdb->update($wpdb->posts, array('post_parent'=>0), array('id'=>$_REQUEST['id'], 'post_type'=>'attachment'));
        }
        
        wp_redirect(admin_url('upload.php'));
        exit;
    }

    /**
     * Set it up.
     */
    function unattach_init() {
        if (current_user_can('upload_files')) {
            add_filter('media_row_actions', array($this, 'unattach_media_row_action'), 10, 2);
            // This is hacky but couldn't find the right hook
            add_submenu_page('tools.php', 'Unattach Media', 'Unattach', 'upload_files', 'unattach', array($this, 'unattach_do_it'));
            remove_submenu_page('tools.php', 'unattach');
        }
    }

    function removeAction($tag, $name, $priority = 10) {
        remove_action($tag, $name, $priority);
        return $this;
    }

    function removeFilter($tag, $name, $priority = 10) {
        remove_filter($tag, $name, $priority);
        return $this;
    }

    // example: add_image_size('custom_name', 355, 240, true);
    function addImageSizes($sizeArr) {
        foreach ($sizeArr as $size) {
            $crop = isset($size[3]) ? $size[3] : true;
            add_image_size($size[0], $size[1], $size[2], $crop);
        }

        return $this;
    }
}
