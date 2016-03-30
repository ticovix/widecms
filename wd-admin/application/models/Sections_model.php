<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sections_model extends CI_Model {

    public function getSection($slug) {
        return $this->db->get_where('wd_sections', array('slug' => $slug))->row_array();
    }

    public function searchSections($page, $keyword = null, $total = null, $offset = null) {
        $this->db->like('name', $keyword);
        $this->db->limit($total, $offset);
        $this->db->order_by('order, name');
        $this->db->where('fk_page', $page);
        return $this->db->get('wd_sections')->result_array();
    }

    public function searchSectionsTotalRows($page, $keyword = null) {
        $this->db->select('count(id) total');
        $this->db->like('name', $keyword);
        $this->db->where('fk_page', $page);
        return $this->db->get('wd_sections')->row()->total;
    }

    public function listSectionsSelect($page, $section) {
        $this->db->order_by('order, name');
        $this->db->where('fk_page', $page);
        //$this->db->where('id!=', $section);
        return $this->db->get('wd_sections')->result_array();
    }

    public function createSection($data) {
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

    public function listSections($page) {
        return $this->db->get_where('wd_sections', ['fk_page' => $page, 'status' => '1'])->result_array();
    }

    public function removeSection($table, $section) {
        $remove = $this->db->delete('wd_sections', array('id' => $section));
        if ($remove) {
            return $this->removeTable($table);
        }
    }

    public function removeTable($table) {
        //$this->openProjectDB($db);
        $this->load->dbforge();
        $stmt = $this->dbforge->drop_table($table);
        //$this->closeProjectDB();
        return $stmt;
    }

    public function createColumns($table, $fields) {
        //$this->openProjectDB($db);
        $this->load->dbforge();
        foreach ($fields as $field) {
            $column = $field['column'];
            $type = $field['type'];
            $limit = $field['limit'];
            $set = array();
            $set[$column] = [
                'type' => $type,
                'constraint' => $limit
            ];
            $this->dbforge->add_column($table, $set);
        }
        //$this->closeProjectDB();
    }

    public function createTable($table) {
        //$this->openProjectDB($db);
        $this->load->dbforge();
        /* Columns default */
        $fields['id'] = [
            'type' => 'INT',
            'auto_increment' => TRUE
        ];
        $fields['order'] = [
            'type' => 'INT'
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $stmt = $this->dbforge->create_table($table, TRUE, ['engine' => 'InnoDB']);
        //$this->closeProjectDB();
        return $stmt;
    }

    public function checkTableExists($table) {
        //$this->openProjectDB($db);
        $check = $this->db->query('SELECT * FROM information_schema.tables WHERE table_name = ?', array($table))->row();
        //$this->closeProjectDB();
        return $check;
    }

    public function removeColumn($data) {
        $table = $data['table'];
        $column = $data['old_column'];
        //$this->openProjectDB($database);
        $this->load->dbforge();
        $remove = $this->dbforge->drop_column($table, $column);
        //$this->closeProjectDB();
        return $remove;
    }

    public function modifyColumn($data) {
        $table = $data['table'];
        $column = $data['column'];
        $old_column = $data['old_column'];
        $type = $data['type'];
        $limit = $data['limit'];
        //$this->openProjectDB($database);
        $this->load->dbforge();
        $fields = array(
            $old_column => array(
                'name' => $column,
                'type' => $type,
                'constraint' => $limit
            ),
        );

        $modify = $this->dbforge->modify_column($table, $fields);
        //$this->closeProjectDB();
        return $modify;
    }

    public function editSection($data) {
        $old_config = $data['old_config'];
        $old_section = $data['old_section'];
        $table = $data['table'];
        $old_table = $old_section['table'];
        $name = $data['name'];
        $directory = $data['directory'];
        $status = $data['status'];
        $id = $old_section['id'];
        $rename = false;
        $update = false;
        if ($table != $old_table) {
            //$this->openProjectDB($db);
            $this->load->dbforge();
            $rename = $this->dbforge->rename_table($old_table, $table);
            //$this->closeProjectDB();
        }
        if ($table != $old_table && $rename or $table == $old_table) {
            $set = array(
                'name' => $name,
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

    public function listColumns($table) {
        $result = $this->db->query('SHOW COLUMNS FROM ' . $table)->result_array();
        $col = array();
        if ($result) {
            foreach($result as $column){
                $col[] = $column['Field'];
            }
        }
        return $col;
    }

}
