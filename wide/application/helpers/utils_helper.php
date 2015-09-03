<?php

if (!function_exists('only_number')) {

    function only_numbers($str) {
        return preg_replace('/[^0-9]/', '', $str);
    }

}
if (!function_exists('is_nav_active')) {

    function is_nav_active($page_current = null, $keys = null) {
        $result = 'active';
        $return = null;
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        if (in_array($page_current, $keys)) {
            $return = $result;
        }
        return $return;
    }

}

if(!function_exists('verify_permission')){
    function verify_permission($nav){
        $CI =& get_instance();
        $CI->securitypanel->verifyPermission($nav);
    }
}
