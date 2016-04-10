<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pass_md5{
    public function input($value) {
        if (!empty($value)) {
            return md5($value);
        }
        return $value;
    }

    public function output($value) {
        return '';
    }
}