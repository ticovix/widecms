<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pass_hash{
    public function input($value) {
        if (!empty($value)) {
            $CI = &get_instance();
            $CI->load->helper('passwordhash');
            $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            return $PasswordHash->HashPassword($value);
        }
        return $value;
    }

    public function output($value) {
        return '';
    }
}