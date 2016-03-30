<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posts_model extends CI_Model {

    public function search($data, $section, $keyword = null, $total = null, $offset = null) {
        $get = array();
        //$this->openProjectDB($project['database']);
        $select = implode(',', $data['select_query']);
        $this->db->select('id,' . $select);
        $this->db->limit($total, $offset);
        $this->db->order_by('order, id');
        if ($keyword) {
            $x = 0;
            foreach ($data['fields'] as $arr) {
                $col = $arr['column'];
                if ($x == 0) {
                    $this->db->like($col, $keyword);
                } else {
                    $this->db->or_like($col, $keyword);
                }
                $x++;
            }
        }
        $get['rows'] = $this->db->get($section['table'])->result_array();
        if ($get) {
            $this->db->select('count(id) total');
            $get['total'] = $this->db->get($section['table'])->row()->total;
        }
        //$this->closeProjectDB();
        return $get;
    }

    public function listPostsSelect($table, $column, $data_trigger = null) {
        $this->db->select($table . '.id value, ' . $table . '.' . $column.' label');
        if ($data_trigger) {
            $table_trigger = $data_trigger['table'];
            $column_trigger = $data_trigger['column'];
            $value = $data_trigger['value'];
            $this->db->join($table_trigger, $table_trigger . '.id=' . $table . '.' . $column_trigger);
            $this->db->where($table . '.' . $column_trigger, $value);
        }
        return $this->db->get($table)->result_array();
    }

    public function getPostSelected($table, $column, $id) {
        $this->db->select('id,' . $column);
        $this->db->where('id', $id);
        return $this->db->get($table)->row_array();
    }

    public function getPost($section, $id = null) {
        //$this->openProjectDB($project['database']);
        if ($id) {
            $this->db->where('id', $id);
        }
        $get = $this->db->get($section['table'])->row_array();
        //$this->closeProjectDB();
        return $get;
    }

    public function createPost($data, $section) {
        //$this->openProjectDB($project['database']);
        if ($data) {
            $set = $data;
        } else {
            $set = array('order' => 1);
        }
        $insert = $this->db->insert($section['table'], $set);
        //$this->closeProjectDB();
        return $insert;
    }

    public function editPost($set, $post, $section) {
        //$this->openProjectDB($project['database']);
        $where = array('id' => $post['id']);
        $update = $this->db->update($section['table'], $set, $where);
        //$this->closeProjectDB();
        return $update;
    }

    public function removePost($section, $post) {
        //$this->openProjectDB($project['database']);
        $where = array('id' => $post['id']);
        $delete = $this->db->delete($section['table'], $where);
        //$this->closeProjectDB();
        return $delete;
    }

}
