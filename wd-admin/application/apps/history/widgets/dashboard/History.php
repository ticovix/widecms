<?php

class History {

    public $name = 'Atividades recentes';

    public function history() {
        add_css(array(
            'css/dashboard.css'
                ), 'history');
        $history = read_history(array(
            'limit' => 5,
            'order_by' => 'id DESC'
        ));
        $vars = array(
            'history' => $history
        );
        $CI = &get_instance();
        $CI->load->view_app('dashboard', 'history', $vars);
    }

}
