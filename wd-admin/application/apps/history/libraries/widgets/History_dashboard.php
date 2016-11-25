<?php

class History_dashboard
{

    public function __construct()
    {
        $CI = &get_instance();
        $CI->include_components->app_css('css/dashboard.css', 'history');
        $history = read_history(array(
            'limit' => 5,
            'order_by' => 'id DESC'
        ));
        $vars = array(
            'history' => $history
        );

        $CI->load->view_app('dashboard', 'history', $vars);
    }
}