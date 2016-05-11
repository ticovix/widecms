<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Users_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function update($data) {
        $set = [
            'name' => $data['name'],
            'last_name' => $data['lastname'],
            'password' => $data['password'],
            'status' => $data['status'],
            'allow_dev' => $data['allow_dev'],
            'root' => $data['root']
        ];
        $where = ['login' => $data['login']];
        return $this->db->update('wd_users', $set, $where);
    }

}
