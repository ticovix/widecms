<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Home extends MY_Controller {

    public function index() {
        $this->load->template('home/index');
    }

    public function logout() {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect('login');
    }

}
