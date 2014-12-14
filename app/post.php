<?php

class FastForward_Post {

    public function getThumbnail($firstImage = false){
        global $post;

        $thumbID = get_post_thumbnail_id($post->ID);

        return get_post($thumbID);
    }


    public function getTitle(){
        global $post;

        return apply_filters('the_title', $post->post_title);
    }


    public function getAuthor(){
        global $post;

        return get_userdata($post->post_author);
    }


    public function getAuthorName(){
        $userdata = $this->getAuthor()->data;

        return $userdata->user_nicename ? $userdata->user_nicename : $userdata->user_login;
    }


    public function getContent(){
        global $post;

        return apply_filters('the_content', $post->post_content);
    }

    public function getExcerpt(){
        global $post;

        return apply_filters('the_content', $post->post_excerpt);
    }


    public function getParent(){
        global $post;

        return $post->post_parent;
    }


    public function getDate($format = 'd.m.Y H:i'){
        global $post;

        return date_i18n($format, strtotime($post->postdate));
    }


    public function getAttachments($mimes = ''){
        global $post;

        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => $mimes,
            'post_parent' => $post->ID,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_status' => array('publish', 'inherit') // NOTE: inherit for images
        ); $attachments = get_posts($args);

        return $attachments;
    }


    // TODO: Not always works :(
    public function getImages($withThumb = true) {
        global $post;

        if (has_post_thumbnail($post->ID) && $withThumb == false) {
            $ThumbID = get_post_thumbnail_id($post->ID);
        } else {
            $ThumbID = '';
        }
        
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_parent' => $post->ID,
            'exclude' => $ThumbID,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ); $attached_imgs = get_posts($args);

        // Check if gallery exists
        if ($gallery = get_post_gallery($post->ID, false)) {
            $ids = explode(',', $gallery['ids']);
            $query = new WP_Query(array(
                'posts_per_page' => -1,
                'post__in' => $ids,
                'post_type' => 'attachment',
                'post_status' => array('publish', 'inherit'),
            ));
            $gallery_imgs = $query->posts;
        } else {
            $gallery_imgs = array();
        }

        // Merge    
        $imgs = array_merge($attached_imgs, $gallery_imgs);
        
        // NOTE: forced adding thumbnail in case when featured image has SET but NOT attached
        if($withThumb){
            $thumbID = get_post_thumbnail_id($post->ID);
            $thumb = get_post($thumbID);
            array_unshift($imgs, $thumb);
        }
        
        return $imgs;
    }


    public function getMeta($name = '', $single = true){
        global $post;

        if($name){
            return get_post_meta($post->ID, $name, $single);
        }

        return get_post_meta($post->ID);
    }


    // TODO: repair for taxonomies
    public function getBreadcrumbs($separator = '&nbsp;/&nbsp;'){
        if(is_home()) return false;
        if(is_category()) return false;

        global $post;
        if($ancestors = get_post_ancestors($post->ID)){

            $breadcrumbs = array();
            foreach($ancestors as $id){
                $breadcrumbs[] = get_the_title($id);    
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            $breadcrumbs = implode($separator, $breadcrumbs);

            return $breadcrumbs;
        }
        return false;
    }


}