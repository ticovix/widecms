<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->verifyAccess();
        $this->dataUser = $this->dataUser();
    }
    
    protected function verifyAccess(){
        if(!$this->session->logged_in or !$this->session->id){ 
            redirect('login');
        }
    }
    
    protected function dataUser(){
        $this->load->model('usuarios_model');
        $id_user = $this->session->userdata('id');
        return $this->usuarios_model->getUser($id_user)->row_array();
    }

}
