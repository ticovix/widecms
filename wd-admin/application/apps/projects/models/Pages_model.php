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
            $pages = search($pages, 'status', '1');
        }

        asort($pages);

        return $pages;
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

    public function list_pages_permissions($project_dir)
    {
        $list_pages = $this->list_pages($project_dir);
        return search($list_pages, 'status', '1');
    }
}