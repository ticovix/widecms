<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sections extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->func_only_dev();
        $this->load->model('sections_model');
    }

    public function index($project, $page) {
        $project = $this->get_project();
        $page = $this->get_page();
        /* Form search */
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $this->form_validation->run();
        $limit = 10;
        $sections = $this->sections_model->searchSections($page['id'], $keyword, $limit, $perPage);
        $total_rows = $this->sections_model->searchSectionsTotalRows($page['id'], $keyword);
        /* End form search */
        $pagination = $this->pagination($total_rows, $limit);

        $vars = [
            'title' => $page['name'],
            'sections' => $sections,
            'pagination' => $pagination,
            'total' => $total_rows,
            'project' => $project,
            'page' => $page
        ];

        $this->load->template('dev-project/sections', $vars);
    }

    private function pagination($total_rows, $limit) {
        $this->load->library('pagination');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $limit;
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_open'] = '</li>';
        $config['first_url'] = '?per_page=0';

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        return $pagination;
    }

    public function edit_section($slug_project, $slug_page, $slug_section) {
        $section = $this->sections_model->getSection($slug_section);
        $project = $this->get_project();
        $page = $this->get_page();
        $this->load->library('config_page');
        /*
         * Fields
         */
        $config = $this->config_page->load_config($project['directory'], $page['directory'], $section['directory']);
        if ($config) {
            $fields = $this->treat_fields($config['fields']);
            $this->form_edit_section($project, $page, $section, $config);
        } else {
            $fields = false;
            setError('config_error', 'Não foi possível abrir o config.xml dessa seção, deseja <a href="javascript:window.history.go(-1)">voltar</a>?');
        }
        /*
         * Select Trigger
         */
        $selects = search($config, 'type', 'select');

        add_js(array(
            'plugins/masks/js/jquery.meio.js',
            'view/project/js/form-section.js'
        ));
        add_css(array(
            'view/project/css/form-section.css'
        ));
        $vars = array(
            'fields' => $fields,
            'title' => 'Editar - ' . $section['name'],
            'name' => $section['name'],
            'directory' => $section['directory'],
            'table' => str_replace($project['suffix'], '', $section['table']),
            'status' => $section['status'],
            'suffix' => $project['suffix'],
            'project' => $project,
            'page' => $page,
            'section' => $section,
            'selects' => $selects,
            'sections' => $this->sections_model->listSectionsSelect($page['id'], $section['id']),
            'inputs' => $this->config_page->inputs(),
            'types' => $this->config_page->types(),
            'masks' => $this->config_page->masks_input()
        );
        $this->load->template('dev-project/form-section', $vars);
    }

    private function form_edit_section($project, $page, $section, $config) {
        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        $this->form_validation->set_rules('directory', 'Diretório', 'trim|required');
        $this->form_validation->set_rules('table', 'Tabela', 'trim|required|callback_verify_table');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $directory = slug($this->input->post('directory'));
            $table = $this->input->post('table');
            $status = $this->input->post('status');
            $name_field = $this->input->post('name_field');
            $input_field = $this->input->post('input_field');
            $list_reg_field = $this->input->post('list_registers_field');
            $column_field = $this->input->post('column_field');
            $type_field = $this->input->post('type_field');
            $limit_field = $this->input->post('limit_field');
            $mask_field = $this->input->post('mask_field');
            $required_field = $this->input->post('required_field');
            $remove_field = $this->input->post('remove_field');
            $options_field = $this->input->post('options_field');
            $label_options_field = $this->input->post('label_options_field');
            $trigger_select_field = $this->input->post('trigger_select_field');
            $table = $project['suffix'] . $table;
            $data = [
                'old_config' => $config,
                'old_section' => $section,
                'project_directory' => $project['directory'],
                'page_directory' => $page['directory'],
                'page' => $page['id'],
                'name' => $name,
                'slug' => slug($directory),
                'directory' => $directory,
                'table' => $table,
                'status' => $status,
                'name_field' => $name_field,
                'input_field' => $input_field,
                'list_reg_field' => $list_reg_field,
                'column_field' => $column_field,
                'type_field' => $type_field,
                'limit_field' => $limit_field,
                'mask_field' => $mask_field,
                'required_field' => $required_field,
                'remove_field' => $remove_field,
                'options_field' => $options_field,
                'label_options_field' => $label_options_field,
                'trigger_select_field' => $trigger_select_field
            ];
            $dir = getcwd() . '/application/views/project/' . $project['directory'] . '/' . $page['directory'] . '/';
            if ($section['directory'] != $directory && \rename($dir . $section['directory'], $dir . $directory) == false) {
                setError('rename_dir', 'Não foi possível renomear o diretório para "' . $directory . '", já existe ou você não possui permissões suficiente.');
            } elseif ($table != $section['table'] && $this->sections_model->checkTableExists($table)) {
                setError('rename_dir', 'O nome dessa tabela já existe, tente outro nome.');
            } elseif ($this->sections_model->editSection($data)) {
                if ($this->edit_fields($data)) {
                    redirect('project/' . $project['directory'] . '/' . $page['directory']);
                }
            }
        } else {
            setError(null, validation_errors());
        }
    }

    private function edit_fields($data) {
        $fields = $this->filter_fields($data);
        $old_fields = $data['old_config']['fields'];
        if ($fields) {
            $x = 0;
            $new_config = array();
            foreach ($fields as $field) {
                $column = $field['column'];
                $type = $field['type'];
                $limit = $field['limit'];
                $remove = (boolean) $field['remove'];
                $data_mod = array();
                $data_mod['table'] = $data['table'];
                $data_mod = array_merge($field, $data_mod);
                if (isset($old_fields[$x])) {
                    // update new field
                    $old_column = $old_fields[$x]['column'];
                    $old_type = strtolower($old_fields[$x]['type_column']);
                    $old_limit = $old_fields[$x]['limit'];
                    $data_mod['old_column'] = $old_column;
                    $data_mod['old_limit'] = $old_limit;
                    if ($remove) {
                        $remove = $this->sections_model->removeColumn($data_mod);
                        if (!$remove) {
                            setError('remove_col', 'Não foi possível remover a coluna ' . $old_column . ', você não tem permissões suficiente.');
                        }
                    } elseif ($old_column != $column or $old_type != $type) {
                        $modify = $this->sections_model->modifyColumn($data_mod);
                        if (!$modify) {
                            $data_mod['column'] = $old_column;
                            $data_mod['type'] = $old_type;
                            $data_mod['limit'] = $old_limit;
                            setError('rename_col', 'Não foi possível modificar a coluna ' . $old_column . ', já existe ou você não possui permissões suficiente.');
                        }
                    }
                } else {
                    // insert new field
                    $field_insert = array();
                    $field_insert[] = array(
                        'column' => $column,
                        'type' => $type,
                        'limit' => $limit
                    );
                    $insert = $this->sections_model->createColumns($data['table'], $field_insert);
                }
                if (!$remove) {
                    $new_config[] = $data_mod;
                }
                $x++;
            }
            if ($new_config) {
                $this->load->library('config_page');
                $config_xml = $this->config_page->create_config_xml($new_config);
                if ($config_xml) {
                    $path_config_xml = 'application/views/project/' . $data['project_directory'] . '/' . $data['page_directory'] . '/' . $data['directory'] . '/config.xml';
                    $fp = fopen($path_config_xml, 'w');
                    fwrite($fp, $config_xml);
                    fclose($fp);
                    if (!$fp) {
                        $this->restore_columns($new_config);
                        setError('change_config', 'Não foi possível salvar o novo config.xml com as alterações');
                    } else {
                        return true;
                    }
                } else {
                    $this->restore_columns($new_config);
                    setError('gen_config', 'Não foi possível gerar um novo config.xml');
                }
            }
        }
    }

    private function restore_columns($data) {
        if ($data) {
            foreach ($data as $arr) {
                $arr['column'] = $arr['old_column'];
                $arr['type'] = $arr['old_type'];
                $arr['limit'] = $arr['old_limit'];
                $this->sections_model->modifyColumn($arr);
            }
        }
    }

    public function delete_section($slug_project, $slug_page, $slug_section) {
        $section = $this->sections_model->getSection($slug_section);
        $project = $this->get_project();
        $page = $this->get_page();
        if ($section && $project && $page) {
            $dir_section = $section['directory'];
            $table = $section['table'];
            $id_section = $section['id'];
            if ($this->sections_model->removeSection($table, $id_section)) {
                forceRemoveDir(getcwd() . '/application/views/project/' . $project['directory'] . '/' . $page['directory'] . '/' . $dir_section);
            }
            redirect('project/' . $slug_project . '/' . $slug_page);
        } else {
            redirect('project/' . $slug_project . '/' . $slug_page);
        }
    }

    public function create_section($slug_project, $slug_page) {
        $this->load->library('config_page');
        $project = $this->get_project();
        $page = $this->get_page();
        $this->form_create_section($project, $page);
        add_js(array(
            'plugins/masks/js/jquery.meio.js',
            'view/project/js/form-section.js'
        ));
        add_css(array(
            'view/project/css/form-section.css'
        ));
        $vars = [
            'title' => 'Nova seção',
            'name' => '',
            'directory' => '',
            'table' => '',
            'status' => '',
            'fields' => '',
            'suffix' => $project['suffix'],
            'project' => $project,
            'page' => $page,
            'inputs' => $this->config_page->inputs(),
            'types' => $this->config_page->types(),
            'masks' => $this->config_page->masks_input()
        ];
        $this->load->template('dev-project/form-section', $vars);
    }

    private function form_create_section($project, $page) {
        /* Form create section */
        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        $this->form_validation->set_rules('directory', 'Diretório', 'trim|required|is_unique[wd_sections.directory]');
        $this->form_validation->set_rules('table', 'Tabela', 'trim|required|is_unique[wd_sections.table]|callback_verify_table');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $directory = slug($this->input->post('directory'));
            $table = $this->input->post('table');
            $status = $this->input->post('status');
            $name_field = $this->input->post('name_field');
            $input_field = $this->input->post('input_field');
            $list_reg_field = $this->input->post('list_registers_field');
            $column_field = $this->input->post('column_field');
            $type_field = $this->input->post('type_field');
            $limit_field = $this->input->post('limit_field');
            $mask_field = $this->input->post('mask_field');
            $required_field = $this->input->post('required_field');
            $options_field = $this->input->post('options_field');
            $label_options_field = $this->input->post('label_options_field');
            $trigger_select_field = $this->input->post('trigger_select_field');
            $table = $project['suffix'] . $table;
            $data = [
                'project_directory' => $project['directory'],
                'page_directory' => $page['directory'],
                'page' => $page['id'],
                'name' => $name,
                'slug' => slug($directory),
                'directory' => $directory,
                'table' => $table,
                'status' => $status,
                'name_field' => $name_field,
                'input_field' => $input_field,
                'list_reg_field' => $list_reg_field,
                'column_field' => $column_field,
                'type_field' => $type_field,
                'limit_field' => $limit_field,
                'mask_field' => $mask_field,
                'required_field' => $required_field,
                'options_field' => $options_field,
                'label_options_field' => $label_options_field,
                'trigger_select_field' => $trigger_select_field
            ];
            if (!$this->sections_model->createTable($table)) {
                setError('verify_table', 'Não foi possível criar a tabela ' . $table . ', a tabela existe ou você não tem permissões suficientes.');
            } elseif (!\mkdir(getcwd() . '/application/views/project/' . $project['directory'] . '/' . $page['directory'] . '/' . $directory, 0755)) {
                setError('verify_dir', 'Não foi possível criar o diretório, já existe ou voce não tem permissões suficientes.');
                $this->sections_model->removeTable($table);
                return false;
            } elseif ($this->create_fields($data)) {
                redirect('project/' . $project['directory'] . '/' . $page['directory']);
            } else {
                $this->sections_model->removeTable($table);
                forceRemoveDir(getcwd() . '/application/views/project/' . $project['directory'] . '/' . $page['directory'] . '/' . $directory);
            }
        } else {
            setError(null, validation_errors());
        }
        /* End form create section */
    }

    public function verify_table($table) {
        $project = $this->get_project();
        $page = $this->get_page();
        $directory = slug($this->input->post('directory'));
        if (substr($project['suffix'] . $table, 0, 3) == 'wd_') {
            $this->form_validation->set_message('verify_table', 'Nomes iniciados por "wd_" são reservados pelo sistema. ');
            return false;
        } else {
            return true;
        }
    }

    protected function create_fields($data) {
        $fields = $this->filter_fields($data);
        if ($fields) {
            if (count($fields)) {
                $this->load->library('config_page');
                $config_xml = $this->config_page->create_config_xml($fields);
                $path_config_xml = 'application/views/project/' . $data['project_directory'] . '/' . $data['page_directory'] . '/' . $data['directory'] . '/config.xml';
                $fp = fopen($path_config_xml, 'w');
                fwrite($fp, $config_xml);
                fclose($fp);
                chmod($path_config_xml, 0640);

                $this->sections_model->createColumns($data['table'], $fields);
            }
            $this->sections_model->createSection($data);
            return true;
        } else {
            return false;
        }
    }

    protected function filter_fields($data) {
        $total = count($data['name_field']);
        if ($total) {
            $this->load->library('config_page');
            $fields = array();
            $remove_field = (isset($data['remove_field']))?$data['remove_field']:array();
            for ($i = 0; $i < $total; $i++) {
                $name_field = $data['name_field'][$i];
                $input_field = $data['input_field'][$i];
                $list_reg_field = $data['list_reg_field'][$i];
                $column_field = $data['column_field'][$i];
                $type_field = $data['type_field'][$i];
                $limit_field = $data['limit_field'][$i];
                $required_field = $data['required_field'][$i];
                $mask_field = $data['mask_field'][$i];
                $options_field = $data['options_field'][$i];
                $label_options_field = $data['label_options_field'][$i];
                $trigger_select_field = $data['trigger_select_field'][$i];
                $remove = (is_array($remove_field) && in_array($i, $remove_field));
                $verify = $this->verify_fields([
                    'table' => $data['table'],
                    'column_field' => $column_field,
                    'name_field' => $name_field,
                    'type_field' => $type_field,
                    'options_field' => $options_field,
                    'label_options_field' => $label_options_field,
                    'input_field' => $input_field,
                    'fields' => $fields,
                ]);

                if (!$verify) {
                    return false;
                } elseif (!empty($name_field) && !empty($input_field) && !empty($column_field) && !empty($type_field)) {
                    if (empty($limit_field)) {
                        $search = search($this->config_page->types(), 'type', $type_field);
                        if (!empty($search[0]['constraint'])) {
                            $limit_field = $search[0]['constraint'];
                        }
                    }

                    $fields[] = [
                        'page' => $data['page'],
                        'name' => $name_field,
                        'input' => $input_field,
                        'list_reg' => $list_reg_field,
                        'column' => $column_field,
                        'type' => $type_field,
                        'limit' => $limit_field,
                        'mask' => $mask_field,
                        'required' => $required_field,
                        'options' => $options_field,
                        'remove' => $remove,
                        'label_options' => $label_options_field,
                        'trigger_select' => $trigger_select_field
                    ];
                }
            }
            return $fields;
        }
    }

    protected function verify_fields($data) {
        $table = $data['table'];
        $column_field = $data['column_field'];
        $name_field = $data['name_field'];
        $type_field = $data['type_field'];
        $input_field = $data['input_field'];
        $fields = $data['fields'];
        $options_field = $data['options_field'];
        $label_options_field = $data['label_options_field'];
        if ($column_field == 'id') {
            setError('error_column', 'O campo "id" é criado automaticamente pelo sistema, informe outro nome.');
            return false;
        } elseif ($column_field == 'order') {
            setError('error_column', 'O campo "order" é criado automaticamente pelo sistema, informe outro nome.');
            return false;
        } elseif ($column_field == $table) {
            setError('error_column', 'O nome da coluna não pode ter o mesmo nome da tabela.');
            return false;
        } elseif (count(search($fields, 'column', $column_field)) > 0) {
            setError('column_duplicate', 'O campo "' . $column_field . '" foi duplicado.');
            return false;
        } elseif (!empty($name_field) && (empty($input_field) or empty($column_field) or empty($type_field))) {
            setError('fields_required', 'Ao preencher o nome do campo, os campos Input, Coluna, Tipo se tornam obrigatórios.');
            return false;
        } elseif (!empty($options_field) && empty($label_options_field)) {
            setError('error_label', 'Ao preencher o campo "Options", o campo "Label Options" é obrigatório, selecione uma opção no campo "' . $name_field . '".');
        } else {
            return true;
        }
    }

    private function get_page() {
        $page = $this->uri->segment(3);
        if (empty($this->page)) {
            $this->load->model('pages_model');
            return $this->page = $this->pages_model->getPage($page);
        } else {
            return $this->page;
        }
    }

    public function list_columns_json() {
        $table = $this->input->post('table');
        $cols = array();
        if (!empty($table)) {
            $cols = $this->sections_model->listColumns($table);
        }
        echo json_encode($cols);
    }
    private function list_columns($table) {
        $cols = array();
        if (!empty($table)) {
            $cols = $this->sections_model->listColumns($table);
        }
        return $cols;
    }

    private function treat_fields($fields) {
        $new_fields = array();
        foreach ($fields as $field) {
            if (isset($field['options']) && !empty($field['options'])) {
                $table = $field['options'];
                if ($table) {
                    $field['label_options_'] = $this->list_columns($table);
                }
            }
            $new_fields[] = $field;
        }
        return $new_fields;
    }

}
