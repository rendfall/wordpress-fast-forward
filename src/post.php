<?php

/**
 * @example
 * $FF['post'] = FastForward::Post();
 * $title = $FF['post']->getTitle();
 * $excerpt = $FF['post']->getExcerpt();
 * $content = $FF['post']->getContent();
 * $link = get_permalink($post->ID);
 * $date = $FF['post']->getDate();
 * $thumb = $FF['post']->getThumbnail();
 */
class FastForward_Post {
    /**
     * Get thumbnail or first attached image.
     */
    public function getThumbnail($firstImage = false) {
        global $post;
        $thumbID = get_post_thumbnail_id($post->ID);

        if ($thumbID) {
            $result = get_post($thumbID);
        }
        return $result;
    }

    /**
     * Get post title with filter applied.
     */
    public function getTitle() {
        global $post;
        return apply_filters('the_title', $post->post_title);
    }

    /**
     * Get post autor.
     */
    public function getAuthor() {
        global $post;
        return get_userdata($post->post_author);
    }

    /**
     * Get post author name or login.
     */
    public function getAuthorName() {
        $userdata = $this->getAuthor()->data;
        return $userdata->user_nicename ? $userdata->user_nicename : $userdata->user_login;
    }

    /**
     * Get post content with filter applied.
     */
    public function getContent() {
        global $post;
        return apply_filters('the_content', $post->post_content);
    }

    /**
     * Get post excerpt with filter applied.
     */
    public function getExcerpt() {
        global $post;
        return apply_filters('the_content', $post->post_excerpt);
    }

    /**
     * Get post parent data.
     */
    public function getParent() {
        global $post;
        return $post->post_parent;
    }

    /**
     * Get parsed and formatted date.
     */
    public function getDate($format = 'd.m.Y H:i') {
        global $post;
        return date_i18n($format, strtotime($post->postdate));
    }

    /**
     * Get post attachments.
     */
    public function getAttachments($mimes = '') {
        global $post;

        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => $mimes,
            'post_parent' => $post->ID,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_status' => array('publish', 'inherit') // NOTE: inherit for images
        );
        $attachments = get_posts($args);

        return $attachments;
    }

    /**
     * Get post images (attachments and embeded in post_content).
     * TODO(rendfall): Not always works :(
     */
    public function getImages($withThumb = true) {
        global $post;
        $thumbID = '';

        if (has_post_thumbnail($post->ID) && $withThumb === false) {
            $thumbID = get_post_thumbnail_id($post->ID);
        }

        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_parent' => $post->ID,
            'exclude' => $thumbID,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $attachedImages = get_posts($args);

        $gallery = get_post_gallery($post->ID, false);
        $images = array();

        if ($gallery) {
            $ids = explode(',', $gallery['ids']);

            $query = new WP_Query(array(
                'posts_per_page' => -1,
                'post__in' => $ids,
                'post_type' => 'attachment',
                'post_status' => array('publish', 'inherit'),
            ));
            $images = $query->posts;
        }

        // Merge
        $imgs = array_merge($attachedImages, $images);
        
        // INFO(rendfall): forced adding thumbnail in case when featured image has SET but NOT attached.
        if ($withThumb) {
            $thumbID = get_post_thumbnail_id($post->ID);
            $thumb = get_post($thumbID);
            array_unshift($imgs, $thumb);
        }
        
        return $imgs;
    }

    /**
     * Get all post metadata or specyfic by name.
     * @param string $name
     * @param boolean $single
     */
    public function getMeta($name = '', $single = true) {
        global $post;

        // If name is not passed or empty - return all post metadata.
        if (!$name) {
            return get_post_meta($post->ID);
        }

        return get_post_meta($post->ID, $name, $single);
    }

    /**
     * Build breadcrumbs from post ancestors.
     * TODO(rendfall): repair for taxonomies
     */
    public function getBreadcrumbs($separator = '&nbsp;/&nbsp;') {
        if (is_home()) {
            return false;
        }

        if (is_category()) {
            return false;
        }

        global $post;
        $ancestors = get_post_ancestors($post->ID);

        if(!$ancestors) {
            return false;
        }

        $breadcrumbs = array();

        foreach ($ancestors as $id) {
            $breadcrumbs[] = get_the_title($id);
        }

        $breadcrumbs = array_reverse($breadcrumbs);
        $breadcrumbs = implode($separator, $breadcrumbs);

        return $breadcrumbs;
    }
}
