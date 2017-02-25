<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sections_model extends CI_Model
{
    private $config_path = 'application/apps/projects/projects/';

    public function __construct()
    {
        parent::__construct();
    }

    public function list_sections($project_dir, $page_dir)
    {
        $page_path = $this->config_path . $project_dir . '/' . $page_dir;
        if (!is_dir($page_path)) {
            return false;
        }

        $files = array();
        $path = opendir($page_path);
        while (false !== ($filename = readdir($path))) {
            if (is_file($this->config_path . $project_dir . '/' . $page_dir . '/' . $filename . '/section.yml')) {
                $files[] = $this->get_section($project_dir, $page_dir, $filename);
            }
        }

        return $files;
    }

    public function total_sections($dir_project, $page_dir)
    {
        $sections = $this->list_sections($dir_project, $page_dir);
        return count($sections);
    }

    public function get_section($project_dir, $page_dir, $section_dir)
    {
        $section = $this->config_path . $project_dir . '/' . $page_dir . '/' . $section_dir . '/section.yml';
        $config = spyc_load_file($section);
        if (!$config) {
            return false;
        }

        return $config;
    }

    public function get_tmp_config($section_path)
    {
        $config = spyc_load_file($section_path);
        if (!$config) {
            return false;
        }

        return $config;
    }

    public function save($data, $project_dir, $page_dir, $section_dir)
    {
        $section = $this->config_path . $project_dir . '/' . $page_dir . '/' . $section_dir . '/section.yml';
        $data_section = spyc_dump($data);
        $fp = fopen($section, 'w');
        if (!$fp) {
            return false;
        }

        fwrite($fp, $data_section);
        fclose($fp);
        chmod($section, 0640);

        return true;
    }

    public function search($project_dir, $page_dir, $keyword = null)
    {
        $pages = $this->list_sections($project_dir, $page_dir);
        if (!empty($keyword)) {
            $pages = search($pages, 'name', '' . $keyword . '', true);
        }

        return $pages;
    }

    public function list_sections_select($project_dir, $page_dir, $section_dir = null)
    {
        $sections = $this->list_sections($project_dir, $page_dir);
        $aux = array();
        foreach ($sections as $section) {
            if ($section_dir != $section['directory']) {
                $aux[] = array('table' => $section['table'], 'name' => $section['name']);
            }
        }

        return $aux;
    }

    public function create($config, $project_dir, $page_dir, $section_dir)
    {
        $data = array(
            'name' => $config['name'],
            'directory' => $config['directory'],
            'table' => $config['table'],
            'status' => $config['status']
        );
        $fields = $config['fields'];
        foreach ($fields as $field) {
            $name = $field['name'];
            $input = $field['input'];
            $list_reg = $field['list_reg'];
            $column = $field['column'];
            $type = $field['type'];
            $limit = $field['limit'];
            $plugins = $field['plugins'];
            $observation = $field['observation'];
            $attributes = $field['attributes'];
            $required = $field['required'];
            $unique = $field['unique'];
            $default = $field['default'];
            $comment = $field['comment'];

            $new_field = array();
            $new_field['input']['type'] = $input;
            $new_field['input']['required'] = $required;
            $new_field['input']['observation'] = $observation;
            $new_field['input']["label"] = $name;
            $new_field['input']["list_registers"] = $list_reg;
            $new_field['input']["plugins"] = $plugins;
            $new_field['input']["attributes"] = str_replace('"', '\'', $attributes);

            $new_field["database"] = array(
                'column' => $column,
                'type_column' => $type,
                'unique' => $unique,
                'default' => $default,
                'limit' => $limit,
                'comment' => $comment,
            );
            if ($input == 'select' or $input == 'checkbox') {
                $options_table = $field['options_table'];
                $options_label = $field['options_label'];
                $options_trigger_select = $field['options_trigger_select'];
                $new_field['input']['options']["table"] = $options_table;
                $new_field['input']['options']["options_label"] = $options_label;
                $new_field['input']['options']["trigger_select"] = $options_trigger_select;
            } elseif ($input == 'file' or $input == 'multifile') {
                $extensions_allowed = $field['extensions_allowed'];
                $image_resize = $field['image_resize'];
                $image_x = $field['image_x'];
                $image_y = $field['image_y'];
                $image_ratio = $field['image_ratio'];
                $image_ratio_x = $field['image_ratio_x'];
                $image_ratio_y = $field['image_ratio_y'];
                $image_ratio_crop = $field['image_ratio_crop'];
                $image_ratio_fill = $field['image_ratio_fill'];
                $image_background_color = $field['image_background_color'];
                $image_convert = $field['image_convert'];
                $image_text = $field['image_text'];
                $image_text_color = $field['image_text_color'];
                $image_text_background = $field['image_text_background'];
                $image_text_opacity = $field['image_text_opacity'];
                $image_text_background_opacity = $field['image_text_background_opacity'];
                $image_text_padding = $field['image_text_padding'];
                $image_text_position = $field['image_text_position'];
                $image_text_direction = $field['image_text_direction'];
                $image_text_x = $field['image_text_x'];
                $image_text_y = $field['image_text_y'];
                $image_thumbnails = $field['image_thumbnails'];
                $new_field['input']['upload'] = array(
                    'extensions_allowed' => $extensions_allowed,
                    'image_resize' => $image_resize,
                    'image_x' => $image_x,
                    'image_y' => $image_y,
                    'image_ratio' => $image_ratio,
                    'image_ratio_x' => $image_ratio_x,
                    'image_ratio_y' => $image_ratio_y,
                    'image_ratio_crop' => $image_ratio_crop,
                    'image_ratio_fill' => $image_ratio_fill,
                    'image_background_color' => $image_background_color,
                    'image_convert' => $image_convert,
                    'image_text' => $image_text,
                    'image_text_color' => $image_text_color,
                    'image_text_background' => $image_text_background,
                    'image_text_opacity' => $image_text_opacity,
                    'image_text_background_opacity' => $image_text_background_opacity,
                    'image_text_padding' => $image_text_padding,
                    'image_text_position' => $image_text_position,
                    'image_text_direction' => $image_text_direction,
                    'image_text_x' => $image_text_x,
                    'image_text_y' => $image_text_y,
                    'image_thumbnails' => $image_thumbnails,
                );
            }

            $data['fields'][] = $new_field;
        }

        return $this->save($data, $project_dir, $page_dir, $section_dir);
    }

    public function remove($table, $section)
    {
        $remove = $this->db->delete('wd_sections', array('id' => $section));
        if ($remove) {
            return $this->remove_table($table);
        }
    }

    public function remove_table($table)
    {
        $this->load->dbforge();
        $stmt = $this->dbforge->drop_table($table);
        return $stmt;
    }

    public function create_columns($table, $fields)
    {
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
                'comment' => $comment
            );
            if (!empty($default)) {
                $set[$column]['default'] = $default;
            }
            $this->dbforge->add_column($table, $set);
        }
    }

    public function create_table($table)
    {
        $this->load->dbforge();
        /* Columns default */
        $fields['id'] = array(
            'type' => 'INT',
            'auto_increment' => TRUE
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $stmt = $this->dbforge->create_table($table, TRUE, ['engine' => 'InnoDB']);
        return $stmt;
    }

    public function check_table_exists($table)
    {
        $database = $this->db->database;
        $check = $this->db->query('SELECT * FROM information_schema.tables WHERE table_schema=? AND table_name = ?', array($database, $table))->row();
        return $check;
    }

    public function remove_column($data, $table)
    {
        $column = $data['column'];
        $this->load->dbforge();
        $remove = $this->dbforge->drop_column($table, $column);
        return $remove;
    }

    public function modify_column($data, $table)
    {
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
                'comment' => $comment
            ),
        );
        if (!empty($default)) {
            $fields[$old_column]['default'] = $default;
        }

        $modify = $this->dbforge->modify_column($table, $fields);
        return $modify;
    }

    public function rename_table($current_table, $new_table)
    {
        $this->load->dbforge();
        return $this->dbforge->rename_table($current_table, $new_table);
    }

    public function list_columns($table)
    {
        $result = $this->db->query('SHOW COLUMNS FROM ' . $table)->result_array();
        $col = array();
        if ($result) {
            foreach ($result as $column) {
                $col[] = $column['Field'];
            }
        }
        return $col;
    }

    public function list_sections_permissions($dir_project, $dir_page)
    {
        $list_sections = $this->list_sections($dir_project, $dir_page);
        return search($list_sections, 'status', '1');
    }

    public function list_tables_import()
    {
        $db = $this->db->database;
        $this->db->select('information_schema.tables.*');
        $this->db->not_like('TABLE_NAME', 'wd_');
        $tables = $this->db->get_where('information_schema.tables', array('table_schema' => $db))->result_array();
        $aux = array();
        if ($tables) {
            foreach ($tables as $table) {
                $exists = $this->config_exists($table['TABLE_NAME']);
                if (!$exists) {
                    $aux[] = $table;
                }
            }
        }
        return $aux;
    }

    private function config_exists($table)
    {
        $this->load->model_app('projects_model');
        $this->load->model_app('pages_model');
        $projects = $this->projects_model->list_projects();
        if ($projects) {
            foreach ($projects as $project) {
                $project_dir = $project['directory'];
                $pages = $this->pages_model->list_pages($project_dir);
                if ($pages) {
                    foreach ($pages as $page) {
                        $page_dir = $page['directory'];
                        $sections = $this->list_sections($project_dir, $page_dir);
                        if ($sections) {
                            foreach ($sections as $section) {
                                if ($section['table'] === $table) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function list_columns_import($table)
    {
        return $this->db->query('SHOW COLUMNS FROM ' . $table)->result_array();
    }
}