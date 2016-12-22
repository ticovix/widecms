<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages_model extends CI_Model
{
    private $config_path = 'application/apps/projects/projects/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('spyc');
    }

    public function list_pages($project_dir)
    {
        $files = array();
        $path = opendir($this->config_path . $project_dir);
        while (false !== ($filename = readdir($path))) {
            if (is_file($this->config_path . $project_dir . '/' . $filename . '/page.yml')) {
                $files[] = $this->get_page($project_dir, $filename);
            }
        }

        return $files;
    }

    public function total_pages($dir_project, $dev_mode)
    {
        $pages = $this->list_pages($dir_project);
        if ($dev_mode) {
            return count($pages);
        }

        return count(search($pages, 'status', '1'));
    }

    public function get_page($project_dir, $page_dir)
    {
        $page = $this->config_path . $project_dir . '/' . $page_dir . '/page.yml';
        $config = spyc_load_file($page);
        if (!$config) {
            return false;
        }

        return $config;
    }

    public function save($data, $project_dir, $page_dir)
    {
        $page = $this->config_path . $project_dir . '/' . $page_dir . '/page.yml';
        $data_page = spyc_dump($data);
        $fp = fopen($page, 'w');
        if (!$fp) {
            return false;
        }

        fwrite($fp, $data_page);
        fclose($fp);
        chmod($page, 0640);

        return true;
    }

    public function search($project_dir, $dev_mode, $keyword = null)
    {
        $pages = $this->list_pages($project_dir);
        if (!empty($keyword)) {
            $pages = search($pages, 'name', '' . $keyword . '', true);
        }

        if ($dev_mode == '0') {
            $pages = search('projects', 'status', '1');
        }

        return $pages;
    }

    public function verify_slug_page($slug, $id = NULL)
    {
        $this->db->select('id');
        $this->db->where('slug', $slug);
        $this->db->where('id!=', $id);
        return $this->db->get('wd_pages')->num_rows();
    }

    public function create($data)
    {
        $set = array(
            'name' => $data['name'],
            'status' => $data['status'],
            'directory' => $data['directory'],
            'user_id' => $data['user_id']
        );
        $this->save($set, $data['project_dir'], $data['directory']);
    }

    public function edit($data)
    {
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

    public function remove($page)
    {
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

    public function list_pages_permissions($id_project)
    {
        $this->db->select('id, name, slug, directory');
        $this->db->where('status', 1);
        $this->db->where('fk_project', $id_project);
        return $this->db->get('wd_pages')->result_array();
    }
}