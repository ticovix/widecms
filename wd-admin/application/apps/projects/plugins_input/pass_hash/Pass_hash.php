<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pass_hash {

    public function input($value, $field) {
        $CI = &get_instance();
        $CI->load->model('posts_model');
        $type = $CI->uri->segment(7);
        if (!empty($value)) {
            $CI->load->helper('passwordhash');
            $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            return $PasswordHash->HashPassword($value);
        }elseif($type == 'edit'){
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
