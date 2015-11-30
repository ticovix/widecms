<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Projects_model extends CI_Model {

    public function create($data) {
        $set = [
            'name' => $data['name'],
            'directory' => $data['dir'],
            'fk_user' => $data['id_user'],
            'slug' => $data['slug'],
            'main' => $data['main'],
            'database' => $data['database']
        ];
        return $this->db->insert('wd_projects', $set);
    }
    
    public function edit($data){
        $set = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status']
        ];
        return $this->db->update('wd_projects', $set, ['id'=>$data['project']]);
    }

    public function verifySlug($slug, $id = NULL) {
        $this->db->select('id');
        $this->db->where('slug', $slug);
        $this->db->where('id!=', $id);
        return $this->db->get('wd_projects')->num_rows();
    }

    public function search($dev_mode, $keyword = null, $total = null, $offset = null) {
        $this->db->group_start();
        $this->db->like('name', $keyword);
        $this->db->or_like('directory', $keyword);
        $this->db->group_end();
        $this->db->limit($total, $offset);
        $this->db->order_by('main DESC, name');
        if($dev_mode=='0'){
            $this->db->where('status','1');
        }
        return $this->db->get('wd_projects')->result_array();
    }

    public function searchTotalRows($dev_mode, $keyword = null, $turma = null, $evento = null) {
        $this->db->select('count(id) total');
        $this->db->group_start();
        $this->db->like('name', $keyword);
        $this->db->or_like('directory', $keyword);
        $this->db->group_end();
        if($dev_mode=='0'){
            $this->db->where('status','1');
        }
        return $this->db->get('wd_projects')->row()->total;
    }

    public function mainExists() {
        return $this->db->get_where('wd_projects', ['main' => '1'])->row_array();
    }

    public function getProject($slug) {
        return $this->db->get_where('wd_projects', ['slug' => $slug])->row_array();
    }

    public function delete($id) {
        return $this->db->delete('wd_projects', ['id' => $id]);
    }

    public function deleteDB($db) {
        $this->load->dbforge();
        return $this->dbforge->drop_database($db);
    }

    public function createDB($db) {
        $this->load->dbforge();
        return $this->dbforge->create_database($db);
    }

}
