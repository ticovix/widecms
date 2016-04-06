<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Users_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function user_exists($user) {
        $this->db->select('password, id');
        $stmt = $this->db->get_where('wd_users', ['login' => $user, 'status' => '1']);
        if ($stmt->num_rows() > 0) {
            return $stmt->row_array();
        } else {
            return false;
        }
    }

    public function get_user($id) {
        return $this->db->get_where('wd_users', ['id' => $id, 'status' => '1'])->row_array();
    }
    
    public function get_user_edit($login) {
        return $this->db->get_where('wd_users', ['login' => $login])->row_array();
    }

    public function search($keyword = null, $total = null, $offset = null) {
        $this->db->like('name', $keyword);
        $this->db->or_like('login', $keyword);
        $this->db->or_like('email', $keyword);
        $this->db->limit($total, $offset);
        return $this->db->get('wd_users')->result_array();
    }

    public function search_total_rows($keyword = null, $turma = null, $evento = null) {
        $this->db->select('count(id) total');
        $this->db->like('name', $keyword);
        $this->db->or_like('login', $keyword);
        $this->db->or_like('email', $keyword);
        return $this->db->get('wd_users')->row()->total;
    }

    public function create($data) {
        $data = [
            'name' => $data['name'],
            'last_name' => $data['lastname'],
            'login' => $data['login'],
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => $data['status'],
            'allow_dev' => $data['allow_dev'],
            'root' => $data['root']
        ];
        return $this->db->insert('wd_users', $data);
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

    public function delete($users) {
        $this->db->where_in('id', $users);
        return $this->db->delete('wd_users');
    }
    
    public function change_mode($data){
        return $this->db->update('wd_users', ['dev_mode'=>$data['dev']], ['id'=>$data['id_user']]);
    }

}
