<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->securitypanel->securityLogin();
        $this->dataUser = $this->dataUser();
        $this->listNav = $this->navigation->listNav();
        $default_values = [
            'profile'=>$this->dataUser,
            'navigation'=>$this->listNav
        ];
        $this->load->template(null, $default_values);
    }
    
    public function dataUser(){
        $this->load->model('users_model');
        $id_user = $this->session->userdata('id');
        return $this->users_model->getUser($id_user)->row_array();
    }
    
    
}
