<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


if (!function_exists('add_history')) {

    function add_history($message)
    {
        $CI = &get_instance();
        $data = array(
            'message' => $message,
            'app' => APP,
            'fk_user' => $CI->user_data['id']
        );
        $CI->load->app('history')->model('history_model');
        return $CI->history_model->add($data);
    }
}

if (!function_exists('remove_history')) {

    function remove_history($id)
    {
        $CI = &get_instance();
        $CI->load->app('history')->model('history_model');
        return $CI->history_model->add($id);
    }
}

if (!function_exists('read_history')) {

    function read_history($data)
    {
        $CI = &get_instance();
        $CI->load->app('history')->model('history_model');
        return $CI->history_model->read($data);
    }
}
