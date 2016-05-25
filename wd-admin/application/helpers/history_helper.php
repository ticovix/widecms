<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


if (!function_exists('add_history')) {
    function add_history($message){
        $CI = &get_instance();
        $data = array(
            'message' => $message,
            'app' => APP,
            'fk_user' => $CI->data_user['id']
        );
        $CI->load->model_app('history_model', 'history');
        return $CI->history_model->add($data);
    }
}

if (!function_exists('remove_history')) {
    function remove_history($id){
        $CI = &get_instance();
        $CI->load->model_app('history_model', 'history');
        return $CI->history_model->add($id);
    }
}

if (!function_exists('read_history')) {
    function read_history($data){
        $CI = &get_instance();
        $CI->load->model_app('history_model', 'history');
        return $CI->history_model->read($data);
    }
}
