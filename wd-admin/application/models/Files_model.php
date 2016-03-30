<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Files_model extends CI_Model {

    public function insert_file($file) {
        return $this->db->insert('wd_files', array('file' => $file));
    }

    public function search($keyword = null, $total = null, $offset = null) {
        if ($keyword) {
            $this->db->like('name', $keyword);
            $this->db->or_like('file', $keyword);
        }
        $this->db->limit($total, $offset);
        $this->db->order_by('id DESC');
        return $this->db->get('wd_files')->result_array();
    }

    public function searchTotalRows($keyword = null) {
        $this->db->select('count(id) total');
        if ($keyword) {
            $this->db->like('name', $keyword);
            $this->db->or_like('file', $keyword);
        }
        return $this->db->get('wd_files')->row()->total;
    }

    public function delete($file) {
        return $this->db->delete('wd_files', array('file' => $file));
    }

    public function file($file) {
        return $this->db->get_where('wd_files', array('file' => $file))->row_array();
    }

    public function edit_file($data) {
        $where = array('file' => $data['file']);
        $set = array(
            'file' => $data['new_file'],
            'name' => $data['name']
        );
        return $this->db->update('wd_files', $set, $where);
    }

}
