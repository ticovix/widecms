<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Datetime_{
    public function input($value) {
        if (!empty($value)) {
            return preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4}) (.*)/', '$3-$2-$1 $4', $value);
        }
        return $value;
    }

    public function output($value) {
        if (!empty($value)) {
            return preg_replace('/([0-9]{4})\-([0-9]{2})\-([0-9]{2}) (.*)/', '$3/$2/$1 $4', $value);
        }
        return $value;
    }
}