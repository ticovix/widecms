<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Documentation extends MY_Controller{
    public function index(){
        $this->load->template('documentation/index');
    }
}