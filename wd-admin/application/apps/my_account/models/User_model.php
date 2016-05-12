<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function update($data) {
        $set = [
            'name' => $data['name'],
            'last_name' => $data['lastname'],
            'password' => $data['password'],
            'image' => $data['image'],
            'about' => $data['about'],
            'email' => $data['email'],
            'login' => $data['login']
        ];
        $where = ['id' => $data['id']];
        return $this->db->update('wd_users', $set, $where);
    }

}
