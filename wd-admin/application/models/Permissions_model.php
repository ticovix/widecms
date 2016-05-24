<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Permissions_model extends CI_Model {

    public function check_app($app) {
        $this->db->select('status');
        $id_user = $this->data_user['id'];
        $perm = $this->db->get_where('wd_users_perm', array('app' => $app, 'fk_user' => $id_user,  'page' => '', 'method' => ''))->row();
        if ($perm) {
            return $perm->status;
        } else {
            return false;
        }
    }
    
    public function list_pages($app){
        $this->db->select('status, page');
        $id_user = $this->data_user['id'];
        return $this->db->get_where('wd_users_perm', array('app' => $app, 'fk_user' => $id_user,  'page!=' => ''))->result_array();
    }
    
    public function check_method($app, $method, $id_user) {
        $this->db->select('status');
        $perm = $this->db->get_where('wd_users_perm', array('app' => $app, 'fk_user' => $id_user, 'method' => $method))->row();
        if ($perm) {
            return $perm->status;
        } else {
            return false;
        }
    }

}
