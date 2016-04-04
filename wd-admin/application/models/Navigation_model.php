<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Navigation_model extends CI_Model{
    
    public function list_nav(){
        $this->db->group_by('name');
        $this->db->order_by('order, name');
        return $this->db->get_where('wd_nav',['status'=>1])->result_array();
    }
}