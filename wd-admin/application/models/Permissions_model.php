<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Permissions_model extends CI_Model
{

    public function check_app($app, $user_id)
    {
        $allow = false;
        $this->db->select('status');
        $perm = $this->db->get_where('wd_users_perm', array('app' => $app, 'fk_user' => $user_id, 'page' => '', 'method' => ''))->row();
        if (!empty($perm)) {
            $allow = $perm->status;
        }

        return $allow;
    }

    public function list_pages($app)
    {
        $this->db->select('status, page');
        $user_id = $this->user_data['id'];

        return $this->db->get_where('wd_users_perm', array('app' => $app, 'fk_user' => $user_id, 'page!=' => ''))->result_array();
    }

    public function check_method($app, $method, $user_id)
    {
        $allow = false;
        $this->db->select('status');
        $perm = $this->db->get_where('wd_users_perm', array('app' => $app, 'fk_user' => $user_id, 'method' => $method))->row();
        if (!empty($perm)) {
            $allow = $perm->status;
        }

        return $allow;
    }
}