<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posts_model extends CI_Model {

    public function search($data, $section, $keyword = null, $total = null, $offset = null) {
        $get = array();
        $select = implode(',', $data['select_query']);
        $this->db->select('id,' . $select);
        $this->db->limit($total, $offset);
        $this->db->order_by('id DESC');
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
        return $get;
    }

    public function list_posts_select($table, $column, $data_trigger = null) {
        $this->db->select('t1.id value, t1.' . $column . ' label');
        if ($data_trigger) {
            $table_trigger = $data_trigger['table'];
            $column_trigger = $data_trigger['column'];
            $value = $data_trigger['value'];
            $this->db->join($table_trigger . ' t2', 't2.id=t1.' . $column_trigger);
            $this->db->where('t1.' . $column_trigger, $value);
        }
        return $this->db->get($table . ' t1')->result_array();
    }

    public function list_posts_checkbox($table, $column) {
        $this->db->select($table . '.id value, ' . $table . '.' . $column . ' label');
        return $this->db->get($table)->result_array();
    }

    public function list_options_checked($table, $column, $value) {
        $this->db->select($column . ' value');
        $this->db->where_in('id', $value);
        return $this->db->get($table)->result_array();
    }

    public function get_post_selected($table, $column, $id) {
        $this->db->select('id,' . $column);
        $this->db->where('id', $id);
        return $this->db->get($table)->row_array();
    }

    public function get_post($section, $id = null) {
        if ($id) {
            $this->db->where('id', $id);
        }
        $get = $this->db->get($section['table'])->row_array();
        return $get;
    }

    public function create($data, $section) {
        if ($data) {
            $set = $data;
        } else {
            $set = array('id' => 'auto_increment');
        }
        $insert = $this->db->insert($section['table'], $set);
        return $insert;
    }

    public function edit($set, $post, $section) {
        $where = array('id' => $post['id']);
        $update = $this->db->update($section['table'], $set, $where);
        return $update;
    }

    public function remove($section, $post) {
        $where = array('id' => $post['id']);
        $delete = $this->db->delete($section['table'], $where);
        return $delete;
    }

}
