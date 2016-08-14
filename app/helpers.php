<?php

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

    public function help() {
        global $FastForward;
        $methods = array();

        foreach ($FastForward as $obj) {
            $methods[get_class($obj)] = get_class_methods($obj);
        }

        echo '<pre>';
        print_r($methods);
        echo '</pre>';
    }
}
