<?php

/**
 * @example
 * FastForward::Helpers()
 *     ->renderImage(142, 'full');
 */
class FastForward_Helpers {
    public function renderImage($id, $size = 'thumbnail') {
        echo wp_get_attachment_image($id, $size);
    }

    public function getImageAllSizes($id, $custom = array()) {
        $thumbnail = wp_get_attachment_image_src($id, 'thumbnail');
        $medium = wp_get_attachment_image_src($id, 'medium');
        $large = wp_get_attachment_image_src($id, 'large');
        $full = wp_get_attachment_image_src($id, 'full');

        if ($custom) {
            $customSize = wp_get_attachment_image_src($id, array($custom[0], $custom[1]));
        }

        $set = array(
            'thumbnail' => $thumbnail,
            'medium' => $medium,
            'large' => $large,
            'full' => $full,
            'custom' => $customSize
        );

        return $set;
    }

    public function getSinglePost($args) {
        $args = wp_parse_args($args, array(
            'posts_per_page' => 1
        ));

        $results = get_pages($args);

        return $results[0];
    }

    /**
     * Render video from provider or raw video file.
     * 
     * @param string
     * @param  boolean $echo
     */
    public function renderVideo($url, $echo = true) {
        $embedCode = wp_oembed_get($url);

        if (!$url) {
            return false;
        }

        // There is no provider on whitelist or it is raw file.
        if (!$embedCode) {
            $embedCode = "<video src=\"{$url}\" controls></video>";
        }

        if ($echo) {
            echo $embedCode;
            return true;
        } else {
            return $embedCode;
        }
    }

    public function getImageByUrl($url) {
        global $wpdb;

        $attachment = $wpdb->get_col($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url 
        ));

        return $attachment[0]; 
    }

    public function isStringUrl($str) {
        return (filter_var($str, FILTER_VALIDATE_URL));
    }
}
