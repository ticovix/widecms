<?php

class History_dashboard
{

    public function __construct()
    {
        $CI = &get_instance();
        $CI->lang->load_app('history', 'history');
        $CI->include_components->app_css('css/dashboard.css', 'history');
        $history = read_history(array(
            'limit' => 5,
            'order_by' => 'id DESC'
        ));
        $vars = array(
            'history' => $history,
            'lang' => $CI->lang
        );

        echo $CI->load->app('history')->render('dashboard.twig', $vars);
    }
}