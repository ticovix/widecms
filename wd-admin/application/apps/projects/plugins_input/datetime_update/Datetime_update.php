<?php

class Datetime_update{
    public function input($value, $field, $fields){
        $value = date('Y-m-d H:i:s');
        return $value;
    }
    
    public function output($value) {
        if (!empty($value)) {
            return preg_replace('/([0-9]{4})\-([0-9]{2})\-([0-9]{2}) (.*)/', '$3/$2/$1 $4', $value);
        }
        return $value;
    }
}