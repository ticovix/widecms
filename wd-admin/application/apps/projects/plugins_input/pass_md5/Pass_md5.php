<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pass_md5 {

    public function input($value, $field) {
        $CI = &get_instance();
        $type = $CI->uri->segment(7);
        if (!empty($value)) {
            return md5($value);
        } elseif ($type == 'edit') {
            $section = get_section();
            $id_post = $CI->uri->segment(8);
            $post = $CI->posts_model->get_post($section, $id_post);
            $value = $post[$field['column']];
        }
        return $value;
    }

    public function output($value) {
        return $value;
    }

}
