<?php

class Datetime_insert{
    public function input($value, $field, $fields){
        if(empty($value)){
            $value = date('Y-m-d H:i:s');
        }else{
            $value = preg_replace('/([0-9]{2})\/([0-9]{2})\/([0-9]{4}) (.*)/', '$3-$2-$1 $4', $value);
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