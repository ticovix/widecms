<?php

class Project_model extends CI_Model {

    public function getProject($slug) {
        return $this->db->get_where('wd_projects', ['slug' => $slug])->row_array();
    }

    public function getPage($slug) {
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

    public function searchTotalRows($project, $dev_mode, $keyword = null) {
        $this->db->select('count(id) total');
        $this->db->like('name', $keyword);
        $this->db->where('fk_project', $project);
        if (!$dev_mode) {
            $this->db->where('status', '1');
        }
        return $this->db->get('wd_pages')->row()->total;
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

    public function verifySlugPage($slug, $id = NULL) {
        $this->db->select('id');
        $this->db->where('slug', $slug);
        $this->db->where('id!=', $id);
        return $this->db->get('wd_pages')->num_rows();
    }

    public function createPage($data) {
        $set = [
            'name' => $data['name'],
            'status' => $data['status'],
            'slug' => $data['slug'],
            'directory' => $data['directory'],
            'fk_project' => $data['id_project'],
            'fk_user' => $data['id_user']
        ];
        $this->db->insert('wd_pages', $set);
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
    
    public function listSections($page){
        return $this->db->get_where('wd_sections', ['fk_page'=>$page, 'status'=>'1'])->result_array();
    }

    public function createTable($db, $table) {
        $this->openProjectDB($db);
        $this->load->dbforge();
        $fields['id'] = [
            'type' => 'INT',
            'auto_increment' => TRUE
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $stmt = $this->dbforge->create_table($table, TRUE, ['engine' => 'InnoDB']);
        $this->closeProjectDB();
        return $stmt;
    }

    public function removeTable($db, $table) {
        $this->openProjectDB($db);
        $this->db->query('use ' . $db . ';');
        $config = $this->config;
        $config->database = $db;
        $this->load->database($config);

        $this->load->dbforge();
        $stmt = $this->dbforge->drop_table($table);
        $this->closeProjectDB();
        return $stmt;
    }

    public function createColumns($db, $table, $fields) {
        $this->openProjectDB($db);
        $this->load->dbforge();
        $set = array();
        foreach ($fields as $field) {
            $column = $field['column'];
            $type = $field['type'];
            $limit = $field['limit'];
            $set[$column] = [
                'type' => $type,
                'constraint' => $limit
            ];
            $this->dbforge->add_column($table, $set);
        }
        var_dump($set);
        $this->closeProjectDB();
    }

    public function openProjectDB($db) {
        return $this->db->query('use ' . $db . ';');
    }

    public function closeProjectDB() {
        return $this->db->query('use ' . $this->db->database . ';');
    }

}
