<?php

class Navigation{
    public function __construct() {
        $this->CI =& get_instance();
    }
    public function listNav(){
        $user = $this->CI->session->id;
    }
}