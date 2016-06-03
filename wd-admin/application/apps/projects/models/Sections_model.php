<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sections_model extends CI_Model {

    public function get_section($slug) {
        return $this->db->get_where('wd_sections', array('slug' => $slug))->row_array();
    }

    public function search_sections($page, $keyword = null, $total = null, $offset = null) {
        $this->db->like('name', $keyword);
        $this->db->limit($total, $offset);
        $this->db->order_by('order, name');
        $this->db->where('fk_page', $page);
        return $this->db->get('wd_sections')->result_array();
    }

    public function search_sections_total_rows($page, $keyword = null) {
        $this->db->select('count(id) total');
        $this->db->like('name', $keyword);
        $this->db->where('fk_page', $page);
        return $this->db->get('wd_sections')->row()->total;
    }

    public function list_sections_select($section = null) {
        $this->db->order_by('order, name');
        //$this->db->where('fk_page', $page);
        if ($section) {
            $this->db->where('id!=', $section);
        }
        return $this->db->get('wd_sections')->result_array();
    }

    public function create($data) {
        $set = [
            'fk_page' => $data['page'],
            'name' => $data['name'],
            'directory' => $data['directory'],
            'table' => $data['table'],
            'status' => $data['status'],
            'slug' => $data['slug']
        ];
        $this->db->insert('wd_sections', $set);
    }

    public function list_sections($page) {
        return $this->db->get_where('wd_sections', ['fk_page' => $page, 'status' => '1'])->result_array();
    }

    public function remove($table, $section) {
        $remove = $this->db->delete('wd_sections', array('id' => $section));
        if ($remove) {
            return $this->remove_table($table);
        }
    }

    public function remove_table($table) {
        $this->load->dbforge();
        $stmt = $this->dbforge->drop_table($table);
        return $stmt;
    }

    public function create_columns($table, $fields) {
        $this->load->dbforge();
        foreach ($fields as $field) {
            $column = $field['column'];
            $type = $field['type'];
            $limit = $field['limit'];
            $default = $field['default'];
            $comment = $field['comment'];
            $set = array();
            $set[$column] = array(
                'type' => $type,
                'constraint' => $limit,
                'default' => $default,
                'comment' => $comment
            );
            $this->dbforge->add_column($table, $set);
        }
    }

    public function create_table($table) {
        $this->load->dbforge();
        /* Columns default */
        $fields['id'] = array(
            'type' => 'INT',
            'auto_increment' => TRUE
        );
        $fields['order'] = array(
            'type' => 'INT'
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $stmt = $this->dbforge->create_table($table, TRUE, ['engine' => 'InnoDB']);
        return $stmt;
    }

    public function check_table_exists($table) {
        $check = $this->db->query('SELECT * FROM information_schema.tables WHERE table_name = ?', array($table))->row();
        return $check;
    }

    public function remove_column($data) {
        $table = $data['table'];
        $column = $data['old_column'];
        $this->load->dbforge();
        $remove = $this->dbforge->drop_column($table, $column);
        return $remove;
    }

    public function modify_column($data) {
        $table = $data['table'];
        $column = $data['column'];
        $old_column = $data['old_column'];
        $type = $data['type'];
        $limit = $data['limit'];
        $default = $data['default'];
        $comment = $data['comment'];
        $this->load->dbforge();
        $fields = array(
            $old_column => array(
                'name' => $column,
                'type' => $type,
                'constraint' => $limit,
                'default' => $default,
                'comment' => $comment
            ),
        );

        $modify = $this->dbforge->modify_column($table, $fields);
        return $modify;
    }

    public function edit($data) {
        $old_config = $data['old_config'];
        $old_section = $data['old_section'];
        $table = $data['table'];
        $old_table = $old_section['table'];
        $name = $data['name'];
        $directory = $data['directory'];
        $status = $data['status'];
        $slug = $data['slug'];
        $id = $old_section['id'];
        $rename = false;
        $update = false;
        if ($table != $old_table) {
            $this->load->dbforge();
            $rename = $this->dbforge->rename_table($old_table, $table);
        }
        if ($table != $old_table && $rename or $table == $old_table) {
            $set = array(
                'name' => $name,
                'slug' => $slug,
                'directory' => $directory,
                'table' => $table,
                'status' => $status
            );
            $where = array(
                'id' => $id
            );
            $update = $this->db->update('wd_sections', $set, $where);
        }
        return $update;
    }

    public function list_columns($table) {
        $result = $this->db->query('SHOW COLUMNS FROM ' . $table)->result_array();
        $col = array();
        if ($result) {
            foreach ($result as $column) {
                $col[] = $column['Field'];
            }
        }
        return $col;
    }
    
    public function list_sections_permissions($id_page){
        $this->db->select('id, name, slug, directory');
        $this->db->where('fk_page', $id_page);
        $this->db->where('status',1);
        return $this->db->get('wd_sections')->result_array();
    }

}
