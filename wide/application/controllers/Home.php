<?php

class Home extends MY_Controller{
    public function index(){
        $this->load->template('home/index');
    }
    
}