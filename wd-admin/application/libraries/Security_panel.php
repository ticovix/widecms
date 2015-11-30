<?php

class Security_panel{
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    public function securityLogin(){
        if(!$this->CI->session->logged_in or !$this->CI->session->id){ 
            redirect('login');
        }
    }
    
    function verifyPermission($nav) {
        if (!empty($nav)) {
            $this->CI->load->model('users_model');
            $id_user = $this->CI->session->userdata('id');
            return $this->CI->users_model->verifyPermission($id_user, $nav);
        }
    }
}
