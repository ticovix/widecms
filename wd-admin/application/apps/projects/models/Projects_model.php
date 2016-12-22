<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Projects_model extends CI_Model
{
    private $config_path = 'application/apps/projects/projects/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('spyc');
    }

    public function list_projects()
    {
        $files = array();
        $path = opendir($this->config_path);
        while (false !== ($filename = readdir($path))) {
            if (is_file($this->config_path . $filename . '/project.yml')) {
                $files[] = $this->get_project($filename);
            }
        }

        return $files;
    }

    public function total_projects($dev_mode)
    {
        $projects = $this->list_projects();
        if ($dev_mode) {
            return count($projects);
        }

        return count(search($projects, 'status', '1'));
    }

    public function get_project($project_dir)
    {
        $project = $this->config_path . $project_dir . '/project.yml';
        $config = spyc_load_file($project);
        if (!$config) {
            return false;
        }

        return $config;
    }

    public function save($data, $project_dir)
    {
        $project = $this->config_path . $project_dir . '/project.yml';
        $data_project = spyc_dump($data);
        $fp = fopen($project, 'w');
        if (!$fp) {
            return false;
        }

        fwrite($fp, $data_project);
        fclose($fp);
        chmod($project, 0640);

        return true;
    }

    public function create($data)
    {
        $set = array(
            'name' => $data['name'],
            'directory' => $data['dir'],
            'main_project' => $data['main'],
            'status' => $data['status'],
            'preffix' => $data['preffix'],
            'user_id' => $data['id_user'],
        );

        $this->save($set, $data['dir']);
    }

    public function search($dev_mode, $keyword = null)
    {
        $projects = $this->list_projects();
        if (!empty($keyword)) {
            $projects = search($projects, 'name', '' . $keyword . '', true);
        }

        if ($dev_mode == '0') {
            $projects = search('projects', 'status', '1');
        }

        return $projects;
    }

    public function main_exists()
    {
        $projects = $this->list_projects();
        if ($projects) {
            foreach ($projects as $project) {
                if ($project['main_project'] == '1') {
                    return true;
                }
            }
        }

        return false;
    }

    public function delete($id)
    {
        $this->db->select('wd_sections.table');
        $this->db->join('wd_pages', 'wd_pages.id=wd_sections.fk_page');
        $this->db->join('wd_projects', 'wd_projects.id=wd_pages.fk_project');
        $this->db->where('wd_projects.id', $id);
        $sections = $this->db->get('wd_sections')->result_array();
        if ($sections) {
            $this->load->dbforge();
            foreach ($sections as $section) {
                $table = $section['table'];
                $this->dbforge->drop_table($table, TRUE);
            }
        }
        return $this->db->delete('wd_projects', ['id' => $id]);
    }

    public function list_projects_permissions()
    {
        $this->db->select('id, name, slug, directory');
        $this->db->where('status', 1);
        return $this->db->get('wd_projects')->result_array();
    }
}