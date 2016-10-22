<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class CI_Helper
{
    public $table = null;
    public $limit = null;
    public $offset = null;
    public $where = null;
    private $query = null;
    private $stmt = null;
    private $config = null;
    private $final_result = array();
    private $filter_options = array();

    public function get($stmt)
    {
        try {
            $this->stmt = $stmt;
            $this->prepare_query();
            $this->list_config_section();

            return $this;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    private function prepare_query()
    {
        $this->query = $this->stmt->get_where($this->table, $this->where, $this->limit, $this->offset);
    }

    private function get_options_selected($type_return, $column, $table, $id)
    {
        $filter_options = $this->filter_options;
        if (!empty($filter_options[$column])) {
            $this->stmt->select($filter_options[$column]);
        }
        $result = $this->stmt->get_where($table, array('id' => $id))->row();
        $this->list_config_section($table);
        return $this->prepare_result($result, $type_return, true);
    }

    public function get_options($data)
    {
        if (!is_array($data)) {
            $data = array($data);
        }
        $this->filter_options = $data;

        return $this;
    }

    public function result()
    {
        $result = $this->query->result();
        $get_result = $this->prepare_result($result, 'object');
        $this->final_result = array();

        return $get_result;
    }

    public function row()
    {
        $result = $this->query->row();
        $get_result = $this->prepare_result($result, 'object', true);
        $this->final_result = array();
        return $get_result;
    }

    public function row_array()
    {
        $result = $this->query->row();
        $get_result = $this->prepare_result($result, 'array', true);
        $this->final_result = array();
        return $get_result;
    }

    public function result_array()
    {
        $result = $this->query->result();
        $get_result = $this->prepare_result($result);
        $this->final_result = array();
        return $get_result;
    }

    private function prepare_result($result, $type, $row = false)
    {
        if (!$result) {

            return $result;
        }
        $config = $this->config;
        if (is_array($result) && isset($result[0])) {
            foreach ($result as $arr) {
                $this->prepare_result($arr, $type, $row);
            }
        } else {
            $result = (array) $result;
            foreach ($result as $col => $value) {

                $search = $this->search($config, 'column', $col);
                if ($search && isset($search[0]['type'])) {
                    $field = $search[0];
                    switch ($field['type']) {
                        case 'checkbox':
                            if (in_array($col, $this->filter_options) or isset($this->filter_options[$col])) {
                                $options_checked = json_decode($value);
                                $options = null;
                                if ($options_checked) {
                                    foreach ($options_checked as $id_option) {
                                        $options[] = $this->get_options_selected($type, $col, $field['options_table'], $id_option);
                                    }
                                }
                                $result[$col] = $options;
                            }
                            break;
                        case 'select':
                            if ((in_array($col, $this->filter_options) or isset($this->filter_options[$col])) && !empty($value)) {
                                $result[$col] = $this->get_options_selected($type, $col, $field['options_table'], $value);
                            }
                            break;
                        case 'file':
                            $result[$col] = $this->list_files($value, 1);
                            break;
                        case 'multifile':
                            $result[$col] = $this->list_files($value);
                            break;
                        default:
                            break;
                    }
                }
            }

            if ($type == 'object') {
                $result = (object) $result;
            }

            if ($row) {
                $this->final_result = $result;
            } else {
                $this->final_result[] = $result;
            }
        }
        return $this->final_result;
    }

    private function treat_config($xml)
    {
        if ($xml) {
            $fields = $xml->form->input;
            $list = false;
            $this->config = array();
            if ($fields) {
                foreach ($fields as $field) {
                    $attr = $field->attributes();
                    $label = (string) $field->label;
                    $type = (string) $attr->type;
                    $required = (string) $attr->required;
                    $observation = (string) $attr->observation;
                    $column = (string) $field->database->column;
                    $type_column = (string) $field->database->type_column;
                    $list_registers = (string) $field->list_registers;
                    $unique = (string) $field->database->unique;
                    $default = (string) $field->database->default;
                    $comment = (string) $field->database->comment;
                    $limit = (string) $field->database->limit;
                    $plugins = (string) $field->plugins;
                    $attributes = (string) $field->attributes;
                    if (isset($field->options)) {
                        $options = (string) $field->options->table;
                        $label_options = (string) $field->options->label_options;
                        $trigger_select = (string) $field->options->trigger_select;
                    } else {
                        $options = '';
                        $label_options = '';
                        $trigger_select = '';
                    }
                    $this->config['fields'][] = array(
                        'label' => $label,
                        'type' => $type,
                        'column' => $column,
                        'type_column' => $type_column,
                        'list_registers' => $list_registers,
                        'required' => $required,
                        'unique' => $unique,
                        'plugins' => $plugins,
                        'observation' => $observation,
                        'attributes' => $attributes,
                        'limit' => $limit,
                        'default' => $default,
                        'comment' => $comment,
                        'options_table' => $options,
                        'options_label' => $label_options,
                        'options_trigger_select' => $trigger_select
                    );
                }
            } else {

                return false;
            }
            return $this->config;
        } else {

            return false;
        }
    }

    public function get_config()
    {
        return $this->config;
    }

    private function list_files($json, $limit = null, $offset = 0)
    {
        if (empty($json)) {

            return false;
        }
        // Decodifica o json para object
        $files = \json_decode($json);
        // Verifica se foi decodificado
        if (is_object($files)) {
            $files = (array) $files;
        } else {

            return false;
        }
        // Se o limit for setado como 1, busca o arquivo principal ou exibe o primeiro arquivo que encontrar.
        if ($limit == '1') {
            $file = $this->search($files, 'checked', 1);
            if ($file) {
                $file = $file[0];
            } else {
                $file = (array) array_shift($files);
            }

            if (is_file(APPPATH . '../../wd-content/upload/' . $file['file'])) {
                return $file;
            } else {

                return false;
            }
        } else {
            // Se for necessário listar mais de um arquivo
            $arr_file = array();
            // Busca o arquivo principal
            $search_checked = $this->search($files, 'checked', 1);
            // Caso encontre, seta no array de arquivos
            if (isset($search_checked[0])) {
                $arr_file[] = array('file' => $search_checked[0]['file'], 'title' => $search_checked[0]['title'], 'checked' => 1);
            }
            // Lista os arquivos do objeto
            foreach ($files as $file) {
                $checked = (isset($file->checked)) ? $file->checked : 0;
                $title = (isset($file->title)) ? $file->title : '';
                $name_file = (isset($file->file)) ? $file->file : '';
                // Seta imagens que o caminho exista e que não seja o arquivo principal
                if (!empty($name_file) && is_file(APPPATH . '../../wd-content/upload/' . $name_file) && $checked == '0') {
                    $arr_file[] = array('file' => $name_file, 'title' => $title, 'checked' => $checked);
                }
            }
            /* Se o parametro offset ou limit for preenchido
             * limita a exibição
             */
            if ($offset or $limit) {
                $total_files = count($arr_file);
                for ($i = $offset; $i < $total_files; $i++) {
                    $final_files[] = $arr_file[$i];
                    if ($limit && $limit == ($i + 1)) {
                        break;
                    }
                }
            } else {
                $final_files = $arr_file;
            }

            return $final_files;
        }
    }

    private function search($array, $key, $value)
    {
        $results = array();
        if (is_array($value) && is_array($array)) {
            foreach ($value as $val) {
                $results = array_merge($results, $this->search($array, $key, $val));
            }
        } elseif (is_array($array) && !is_array($value)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                if (is_object($subarray)) {
                    $subarray = (array) $subarray;
                }
                $results = array_merge($results, $this->search($subarray, $key, $value));
            }
        }
        return $results;
    }

    private function list_config_section($table = null)
    {
        if (empty($table)) {
            $table = $this->table;
        }
        $dir = $this->list_dirs($table);
        if (!$dir) {
            throw new Exception('A tabela ' . $table . ' não foi localizada na tabela wd_sections.');
        }
        $path = APPPATH . '../../wd-admin/application/apps/projects/views/project/' . $dir['dir_project'] . '/' . $dir['dir_page'] . '/' . $dir['dir_section'] . '/config.xml';
        if (!is_file($path)) {
            throw new Exception('Não foi possível localizar o arquivo config.xml dessa tabela');
        }
        $this->config = $this->treat_config(simplexml_load_file($path, 'SimpleXMLElement'));

        return $this->config;
    }

    private function list_dirs($table)
    {
        $model = load_class('Model', 'core');
        $model->db->select('wd_sections.directory dir_section');
        $model->db->select('wd_pages.directory dir_page');
        $model->db->select('wd_projects.directory dir_project');
        $model->db->join('wd_pages', 'wd_pages.id=wd_sections.fk_page');
        $model->db->join('wd_projects', 'wd_projects.id=wd_pages.fk_project');
        $model->db->where('table', $table);

        return $model->db->get('wd_sections')->row_array();
    }
}