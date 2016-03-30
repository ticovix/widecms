<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Masks_input {

    public function mask_input_date($value) {
        return preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4}).*/', '$3-$2-$1', $value);
    }

    public function mask_output_date($value) {
        if ($value) {
            $format = 'd/m/Y';
            $date = new DateTime($value);
            $value = $date->format($format);
        }
        return $value;
    }

    public function mask_input_datetime($value) {
        return preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4}) (.*)/', '$3-$2-$1 $4', $value);
    }

    public function mask_output_datetime($value) {
        if ($value) {
            $format = 'd/m/Y H:i:s';
            $date = new DateTime($value);
            $value = $date->format($format);
        }
        return $value;
    }
    public function mask_input_real($real) {
        $float = str_replace(",", ".", str_replace(".", "", $real));
        return (float) $float;
    }
    public function mask_output_real($float) {
        $real = number_format($float, 2, ",", ".");
        return $real;
    }

}
