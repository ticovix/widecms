<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('check_app')) {

    function check_app($app, $user_id = null)
    {
        $CI = &get_instance();

        if (empty($user_id)) {
            $user_id = $CI->user_data['id'];
            if ($CI->user_data['root'] == '1') {
                return true;
            }
        }

        $CI->load->model('permissions_model', null, null, true);
        return $CI->permissions_model->check_app($app, $user_id);
    }
}

if (!function_exists('check_url')) {

    function check_url($app, $url)
    {
        $CI = &get_instance();
        if ($CI->user_data['root'] == '1') {
            return true;
        }
        $CI->load->model('permissions_model', null, null, true);
        $pages = $CI->permissions_model->list_pages($app);
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

    function check_method($method, $app = APP, $user = null)
    {
        $CI = &get_instance();
        if ($CI->user_data['root'] == '1' && empty($user)) {
            return true;
        }
        if (empty($user)) {
            $user = $CI->user_data['id'];
        }
        $CI->load->model('permissions_model', null, null, true);
        return $CI->permissions_model->check_method($app, $method, $user);
    }
}