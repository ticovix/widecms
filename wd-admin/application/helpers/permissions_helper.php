<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('check_app')) {

    function check_app($app) {
        $CI = &get_instance();
        if ($CI->data_user['root'] == '1') {
            return true;
        }
        $CI->load->model('permissions_model', null, null, true);
        return $CI->permissions_model->check_app($app);
    }

}

if (!function_exists('check_url')) {

    function check_url($app, $url) {
        $CI = &get_instance();
        if ($CI->data_user['root'] == '1') {
            return true;
        }
        $CI->load->model('permissions_model', null, null, true);
        $pages = $CI->permissions_model->list_sections($app);
        if ($pages) {
            foreach ($pages as $page) {
                $url_page = str_replace('/', '\/', $page['page']);
                $status = $page['status'];
                if (preg_match('/^' . $url_page . '$/i', $url)) {
                    return $status;
                }
            }
        }
        return true;
    }

}

if (!function_exists('check_method')) {

    function check_method($method, $app = APP, $user = null) {
        $CI = &get_instance();
        if ($CI->data_user['root'] == '1' && empty($user)) {
            return true;
        }
        if(empty($user)){
            $user = $CI->data_user['id'];
        }
        $CI->load->model('permissions_model', null, null, true);
        return $CI->permissions_model->check_method($app, $method, $user);
    }

}