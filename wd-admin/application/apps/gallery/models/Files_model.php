<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Files_model extends CI_Model {

    public function insert_file($file, $thumbs) {
        return $this->db->insert('wd_files', array('file' => $file, 'thumbnails'=> json_encode($thumbs)));
    }

    public function search($keyword = null, $filter_extensions = null, $filter_thumbs = null, $total = null, $offset = null) {
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('name', $keyword);
            $this->db->or_like('file', $keyword);
            $this->db->group_end();
        }
        if(is_array($filter_extensions)){
            $this->db->group_start();
            foreach($filter_extensions as $extension){
                $this->db->or_like('file', '.'.$extension);
            }
            $this->db->group_end();
        }
        if(is_array($filter_thumbs)){
            $this->db->group_start();
            foreach($filter_thumbs as $thumb){
                $this->db->or_like('thumbnails', '"'.$thumb);
            }
            $this->db->group_end();
        }
        
        $this->db->limit($total, $offset);
        $this->db->order_by('id DESC');
        return $this->db->get('wd_files')->result_array();
    }

    public function search_total_rows($keyword = null, $filter_extensions = null, $filter_thumbs = null) {
        $this->db->select('count(id) total');
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('name', $keyword);
            $this->db->or_like('file', $keyword);
            $this->db->group_end();
        }
        if(is_array($filter_extensions)){
            $this->db->group_start();
            foreach($filter_extensions as $extension){
                $this->db->or_like('file', '.'.$extension);
            }
            $this->db->group_end();
        }
        if(is_array($filter_thumbs)){
            $this->db->group_start();
            foreach($filter_thumbs as $thumb){
                $this->db->or_like('thumbnails', '"'.$thumb);
            }
            $this->db->group_end();
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
