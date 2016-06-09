<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages_model extends CI_Model {
    
    public function get_page($slug) {
        return $this->db->get_where('wd_pages', ['slug' => $slug])->row_array();
    }
    
    public function search($project, $dev_mode, $keyword = null, $total = null, $offset = null) {
        $this->db->like('name', $keyword);
        $this->db->limit($total, $offset);
        $this->db->order_by('order, name');
        $this->db->where('fk_project', $project);
        if (!$dev_mode) {
            $this->db->where('status', '1');
        }
        return $this->db->get('wd_pages')->result_array();
    }

    public function search_total_rows($project, $dev_mode, $keyword = null) {
        $this->db->select('count(id) total');
        $this->db->like('name', $keyword);
        $this->db->where('fk_project', $project);
        if (!$dev_mode) {
            $this->db->where('status', '1');
        }
        return $this->db->get('wd_pages')->row()->total;
    }
    
    public function verify_slug_page($slug, $id = NULL) {
        $this->db->select('id');
        $this->db->where('slug', $slug);
        $this->db->where('id!=', $id);
        return $this->db->get('wd_pages')->num_rows();
    }

    public function create($data) {
        $set = array(
            'name' => $data['name'],
            'status' => $data['status'],
            'slug' => $data['slug'],
            'directory' => $data['directory'],
            'fk_project' => $data['id_project'],
            'fk_user' => $data['id_user']
        );
        $this->db->insert('wd_pages', $set);
    }
    
    public function edit($data) {
        $set = array(
            'name' => $data['name'],
            'status' => $data['status'],
            'slug' => $data['slug'],
            'directory' => $data['slug']
        );
        $where = array(
            'id' => $data['id_page']
        );
        $this->db->update('wd_pages', $set, $where);
    }

    public function remove($page) {
        $sections = $this->db->get_where('wd_sections', array('fk_page' => $page))->result_array();
        if ($sections) {
            $this->load->model_app('sections_model');
            foreach ($sections as $section) {
                $table = $section['table'];
                $id = $section['id'];
                $this->sections_model->remove($table, $id);
            }
        }
        return $this->db->delete('wd_pages', array('id' => $page));
    }
    
    public function list_pages_permissions($id_project){
        $this->db->select('id, name, slug, directory');
        $this->db->where('status',1);
        $this->db->where('fk_project', $id_project);
        return $this->db->get('wd_pages')->result_array();
    }
    
}