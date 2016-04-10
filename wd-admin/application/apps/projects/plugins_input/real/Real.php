<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Real{
    public function input($value) {
        if (!empty($value)) {
            $value = str_replace(",", ".", str_replace(".", "", $value));
            return (float) $value;
        }
        return $value;
    }

    public function output($value) {
        if (!empty($value)) {
            $value = number_format($value, 2, ",", ".");
            return $value;
        }
        return $value;
    }
}