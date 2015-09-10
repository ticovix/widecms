<?php

class Users_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    public function userExists($user){
        $this->db->select('password, id');
        $stmt = $this->db->get_where('users', ['login'=>$user]);
        if($stmt->num_rows()>0){
            return $stmt->row_array();
        }else{
            return false;
        }
    }
    public function getUser($id){
        return $this->db->get_where('users',['id'=>$id]);
    }
    public function search($keyword=null, $total=null,$offset=null){
        $this->db->like('nome',$keyword);
        $this->db->limit($total,$offset);
        return $this->db->get('users')->result_array();
    }
    public function searchTotalRows($keyword=null){
        $this->db->select('count(id) total');
        $this->db->like('nome',$keyword);
        return $this->db->get('users')->row()->total;
    }
    public function create(){
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $data = [
            'nome'=>$this->input->post('nome'),
            'email'=>$this->input->post('email'),
            'senha'=>$PasswordHash->HashPassword($this->input->post('senha')),
            'root'=>$this->input->post('root'),
            'status'=>$this->input->post('status')
            ];
        return $this->db->insert('users', $data);
    }
    public function listEdit($id){
        $where = ['id'=>$id];
        return $this->db->get_where('users', $where)->row();
    }
    public function update($id){
        $set = [
            'nome'=>$this->input->post('nome'),
            'email'=>$this->input->post('email'),
            'root'=>$this->input->post('root'),
            'status'=>$this->input->post('status')
            ];
        $where = ['id'=>$id];
        return $this->db->update('users', $set, $where);
    }
    public function updatePass($id){
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $set = [
            'senha'=>$PasswordHash->HashPassword($this->input->post('nova_senha'))
        ];
        $where = ['id'=>$id];
        return $this->db->update('users', $set, $where);
    }
    public function delete($id){
        $where = ['id'=>$id];
        return $this->db->delete('users', $where);
    }
    public function verifyPermission($id, $nav){
        $this->db->select('*, menu.id, menu_access.status');
        $this->db->join('menu_access','menu.id=menu_access.id_menu');
        return $this->db->get_where('menu',['menu_access.id_user'=>$id,'menu.slug'=>$nav, 'menu.status'=>1])->num_rows();
    }
}
