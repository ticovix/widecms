<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sections extends MY_Controller {

    private $path_view_project = '';
    /*
     * Variável pública com o limite de seções por página
     */
    public $limit = 10;

    public function __construct() {
        parent::__construct();
        func_only_dev();
        $this->load->model('sections_model');
        $this->path_view_project = 'application/' . APP_PATH . 'views/project/';
    }

    /*
     * Método para listar seções
     */

    public function index() {
        $project = get_project();
        $page = get_page();
        $search = $this->form_search($page);
        $sections = $search['sections'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);

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

    /*
     * Método de busca de seção
     */

    private function form_search($page) {
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $this->form_validation->run();
        $id_page = $page['id'];
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = $this->limit;
        $sections = $this->sections_model->search_sections($id_page, $keyword, $limit, $perPage);
        $total_rows = $this->sections_model->search_sections_total_rows($id_page, $keyword);
        return array(
            'sections' => $sections,
            'total_rows' => $total_rows
        );
    }

    /*
     * Método para paginação da listagem de seções
     */

    private function pagination($total_rows) {
        $this->load->library('pagination');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->limit;
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

    /*
     * Método para chamar a view de edição da seção
     */

    public function edit($slug_project, $slug_page, $slug_section) {
        $project = get_project();
        $page = get_page();
        $section = $this->sections_model->get_section($slug_section);
        $this->load->library('../' . APP_PATH . 'libraries/config_page');
        // Carrega os campos da seção
        $config = $this->config_page->load_config($project['directory'], $page['directory'], $section['directory']);
        if ($config) {
            // Se carregado corretamente, carrega o formulário de edição
            $fields = $this->treat_fields($config['fields']);
            $this->form_edit_section($project, $page, $section, $config);
            // Busca todos os campos do tipo select
            $selects = search($fields, 'type', 'select');
        } else {
            $fields = false;
            setError('config_error', 'Não foi possível abrir o config.xml dessa seção, deseja <a href="javascript:window.history.go(-1)">voltar</a>?');
        }
        
        add_js(array(
            'plugins/masks/js/jquery.meio.js',
            APP_PATH . 'project/js/form-section.js'
        ));
        add_css(array(
            APP_PATH . 'project/css/form-section.css'
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
            'sections' => $this->sections_model->list_sections_select($section['id']),
            'inputs' => $this->config_page->inputs(),
            'types' => $this->config_page->types(),
            'masks' => $this->config_page->masks_input()
        );
        $this->load->template('dev-project/form-section', $vars);
    }

    /*
     * Método com o formulário de edição da seção
     */

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
            $dir = $this->path_view_project . $project['directory'] . '/' . $page['directory'] . '/';
            if ($section['directory'] != $directory && \rename($dir . $section['directory'], $dir . $directory) == false) {
                // Se houver erro para renomear o diretório
                setError('rename_dir', 'Não foi possível renomear o diretório para "' . $directory . '", já existe ou você não possui permissões suficiente.');
            } elseif ($table != $section['table'] && $this->sections_model->check_table_exists($table)) {
                // Se o nome da tabela já existir no banco de dados
                setError('rename_dir', 'O nome dessa tabela já existe, tente outro nome.');
            } elseif ($this->sections_model->edit($data)) {
                // Se a seção for atualizada no banco de dados
                if ($this->edit_fields($data)) {
                    // Se o xml dos campos for atualizado corretamente
                    redirect_app('project/' . $project['directory'] . '/' . $page['directory']);
                }
            }
        } else {
            setError(null, validation_errors());
        }
    }

    /*
     * Método para editar campos da seção
     */

    private function edit_fields($data) {
        // Recebe os campos com as edições atuais a ser atualizadas no xml e banco de dados
        $fields = $this->filter_fields($data);
        // Recebe os campos atuais
        $old_fields = $data['old_config']['fields'];
        if ($fields) {
            $x = 0;
            $new_config = array();
            foreach ($fields as $field) {
                // Lista os campos a ser atualizado
                $column = $field['column'];
                $type = $field['type'];
                $limit = $field['limit'];
                $remove = (boolean) $field['remove'];
                $data_mod = array();
                $data_mod['table'] = $data['table'];
                $data_mod = array_merge($field, $data_mod);
                if (isset($old_fields[$x])) {
                    // Verifica se o campo existe no xml atual
                    $old_column = $old_fields[$x]['column'];
                    $old_type = strtolower($old_fields[$x]['type_column']);
                    $old_limit = $old_fields[$x]['limit'];
                    $data_mod['old_column'] = $old_column;
                    $data_mod['old_limit'] = $old_limit;
                    if ($remove) {
                        // Se a opção para remover o campo for selecionada
                        // Remove a coluna
                        $remove = $this->sections_model->remove_column($data_mod);
                        if (!$remove) {
                            // Se a coluna não for removida
                            setError('remove_col', 'Não foi possível remover a coluna ' . $old_column . ', você não tem permissões suficiente.');
                        }
                    } elseif ($old_column != $column or $old_type != $type) {
                        // Se houver atualizações no nome da coluna ou no tipo da coluna
                        // atualiza a coluna com os novos dados
                        $modify = $this->sections_model->modify_column($data_mod);
                        if (!$modify) {
                            // Se não atualizar
                            $data_mod['column'] = $old_column;
                            $data_mod['type'] = $old_type;
                            $data_mod['limit'] = $old_limit;
                            setError('rename_col', 'Não foi possível modificar a coluna ' . $old_column . ', já existe ou você não possui permissões suficiente.');
                        }
                    }
                } else {
                    // Se o campo não existir
                    $field_insert = array();
                    $field_insert[] = array(
                        'column' => $column,
                        'type' => $type,
                        'limit' => $limit
                    );
                    // Cria a coluna no banco de dados
                    $insert = $this->sections_model->create_columns($data['table'], $field_insert);
                }
                if (!$remove) {
                    // Se o campo de remoção não for selecionado, inclui no novo xml
                    $new_config[] = $data_mod;
                }
                $x++;
            }
            return $this->save_edit_config($data, $new_config);
        }
    }

    /*
     * Método para salvar configuração no xml
     */

    private function save_edit_config($data, $new_config) {
        if ($new_config) {
            $this->load->library('../'.APP_PATH.'libraries/config_page');
            // Gera uma nova estrutura xml com os novos campos
            $config_xml = $this->config_page->create_config_xml($new_config);
            if ($config_xml) {
                $path_config_xml = $this->path_view_project . $data['project_directory'] . '/' . $data['page_directory'] . '/' . $data['directory'] . '/config.xml';
                // Abre o arquivo config.xml e atualiza a nova estrutura
                $fp = fopen($path_config_xml, 'w');
                fwrite($fp, $config_xml);
                fclose($fp);
                if (!$fp) {
                    // Se não for possível editar o arquivo, restaura todas as modificações
                    $this->restore_columns($new_config);
                    setError('change_config', 'Não foi possível salvar o novo config.xml com as alterações');
                } else {
                    return true;
                }
            } else {
                // Se houver errosna nova estrutura xml gerada, restaura todas as modificações
                $this->restore_columns($new_config);
                setError('gen_config', 'Não foi possível gerar um novo config.xml');
            }
        }
    }

    /*
     * Método para restaurar as alterações feita nas colunas de uma determinada tabela
     */

    private function restore_columns($data) {
        if ($data) {
            foreach ($data as $arr) {
                $arr['column'] = $arr['old_column'];
                $arr['type'] = $arr['old_type'];
                $arr['limit'] = $arr['old_limit'];
                $this->sections_model->modify_column($arr);
            }
        }
    }

    /*
     * Método para remover seção
     */

    public function remove($slug_project, $slug_page, $slug_section) {
        $section = $this->sections_model->get_section($slug_section);
        $project = get_project();
        $page = get_page();
        if ($section && $project && $page) {
            $dir_section = $section['directory'];
            $table = $section['table'];
            $id_section = $section['id'];
            if ($this->sections_model->remove($table, $id_section)) {
                // Se tudo relacionado a seção for removida do banco de dados, remove o diretório com as config xml dos formulárioss
                forceRemoveDir($this->path_view_project . $project['directory'] . '/' . $page['directory'] . '/' . $dir_section);
            }
            redirect_app('project/' . $slug_project . '/' . $slug_page);
        } else {
            redirect_app('project/' . $slug_project . '/' . $slug_page);
        }
    }

    /*
     * Método para criar seção
     */

    public function create($slug_project, $slug_page) {
        $this->load->library('../'.APP_PATH.'libraries/config_page');
        $project = get_project();
        $page = get_page();
        $this->form_create_section($project, $page);
        add_js(array(
            'plugins/masks/js/jquery.meio.js',
            APP_PATH . 'project/js/form-section.js'
        ));
        add_css(array(
            APP_PATH . 'project/css/form-section.css'
        ));
        $vars = [
            'title' => 'Criar nova seção em ' . $page['name'],
            'name' => '',
            'directory' => '',
            'table' => '',
            'status' => '',
            'fields' => '',
            'suffix' => $project['suffix'],
            'project' => $project,
            'page' => $page,
            'sections' => $this->sections_model->list_sections_select(),
            'inputs' => $this->config_page->inputs(),
            'types' => $this->config_page->types(),
            'masks' => $this->config_page->masks_input()
        ];
        $this->load->template('dev-project/form-section', $vars);
    }

    /*
     * Método com configurações do formulário para criar seção
     */

    private function form_create_section($project, $page) {
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
            if (!$this->sections_model->create_table($table)) {
                // Se a tabela não for inserida no banco de dados
                setError('verify_table', 'Não foi possível criar a tabela ' . $table . ', a tabela existe ou você não tem permissões suficientes.');
            } elseif (!\mkdir($this->path_view_project . $project['directory'] . '/' . $page['directory'] . '/' . $directory, 0755)) {
                // Se o diretório não existir
                setError('verify_dir', 'Não foi possível criar o diretório, já existe ou voce não tem permissões suficientes.');
                // Remove tabela criada
                $this->sections_model->remove_table($table);
                return false;
            } elseif ($this->create_fields($data)) {
                // Se os campos xml forem criados com sucesso
                redirect_app('project/' . $project['directory'] . '/' . $page['directory']);
            } else {
                // Se os campos não forem criados, remove a tabela e o diretório criado
                $this->sections_model->remove_table($table);
                forceRemoveDir($this->path_view_project . $project['directory'] . '/' . $page['directory'] . '/' . $directory);
            }
        } else {
            setError(null, validation_errors());
        }
    }

    /*
     * Método para verificar se a tabela possui o sufixo wd_, protegida pelo sistema
     */

    public function verify_table($table) {
        $project = get_project();
        $page = get_page();
        $directory = slug($this->input->post('directory'));
        if (substr($project['suffix'] . $table, 0, 3) == 'wd_') {
            $this->form_validation->set_message('verify_table', 'Nomes iniciados por "wd_" são reservados pelo sistema. ');
            return false;
        } else {
            return true;
        }
    }

    /*
     * Método para criar campos
     */

    protected function create_fields($data) {
        // Filtra os campos do arquivo xml
        $fields = $this->filter_fields($data);
        if ($fields) {
            if (count($fields)) {
                $this->load->library('../'.APP_PATH.'libraries/config_page');
                // Cria estrutura xml do array
                $config_xml = $this->config_page->create_config_xml($fields);
                $path_config_xml = $this->path_view_project . $data['project_directory'] . '/' . $data['page_directory'] . '/' . $data['directory'] . '/config.xml';
                // Cria o arquivo config.xml
                $fp = \fopen($path_config_xml, 'w');
                \fwrite($fp, $config_xml);
                \fclose($fp);
                \chmod($path_config_xml, 0640);
                if ($fp) {
                    // Se o arquivo for criado, cria colunas dos campos no banco de dados
                    $this->sections_model->create_columns($data['table'], $fields);
                } else {
                    return false;
                }
            }
            // Se tudo der certo, cria nova seção
            $this->sections_model->create($data);
            return true;
        } else {
            // Se houver algum erro na filtragem de campos
            return false;
        }
    }

    /*
     * Método para filtrar campos do config xml
     */

    protected function filter_fields($data) {
        $total = count($data['name_field']);
        if ($total) {
            $this->load->library('../'.APP_PATH.'libraries/config_page');
            $fields = array();
            // Recebe os campos que foram selecionados para ser removido
            $remove_field = (isset($data['remove_field'])) ? $data['remove_field'] : array();
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
                // Verifica se os campos seguem os requisitos do sistema
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
                    // Se houver algum erro na verificação dos campos
                    return false;
                } elseif (!empty($name_field) && !empty($input_field) && !empty($column_field) && !empty($type_field)) {
                    // Se todos os campos obrigatórios forem preenchidos corretamente
                    if (empty($limit_field)) {
                        // Se o limite do campo não for setado, faz uma busca para verificar um limite padrão
                        $search = search($this->config_page->types(), 'type', $type_field);
                        if (!empty($search[0]['constraint'])) {
                            // Caso o limite seja encontrado, seta um limite para o campo
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

    /*
     * Método para verificar se os campos seguem os requisitos do sistema
     */

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
            // Se o nome da coluna for id
            setError('error_column', 'O campo "id" é criado automaticamente pelo sistema, informe outro nome.');
            return false;
        } elseif ($column_field == 'order') {
            // Se o nome da coluna for order
            setError('error_column', 'O campo "order" é criado automaticamente pelo sistema, informe outro nome.');
            return false;
        } elseif ($column_field == $table) {
            // Se o nome da coluna for igual da tabela
            setError('error_column', 'O nome da coluna não pode ter o mesmo nome da tabela.');
            return false;
        } elseif (count(search($fields, 'column', $column_field)) > 0) {
            // Se o nome da coluna estiver duplicado
            setError('column_duplicate', 'O campo "' . $column_field . '" foi duplicado.');
            return false;
        } elseif (!empty($name_field) && (empty($input_field) or empty($column_field) or empty($type_field))) {
            // Se o nome do campo for preenchido e as outras informações não forem preenchidas
            setError('fields_required', 'Ao preencher o nome do campo, os campos Input, Coluna, Tipo se tornam obrigatórios.');
            return false;
        } elseif (!empty($options_field) && empty($label_options_field)) {
            // Se o campo options for preenchido, o campo Label se torna obrigatório
            setError('error_label', 'Ao preencher o campo "Options", o campo "Label Options" é obrigatório, selecione uma opção no campo "' . $name_field . '".');
        } else {
            // Se não houver nenhum erro
            return true;
        }
    }

    /*
     * Método que retorna os dados da página
     */

    private function get_page() {
        $page = $this->uri->segment(3);
        if (empty($this->page)) {
            $this->load->model('pages_model');
            return $this->page = $this->pages_model->get_page($page);
        } else {
            return $this->page;
        }
    }

    /*
     * Método que retorna listagem de colunas em json
     */

    public function list_columns_json() {
        $table = $this->input->post('table');
        $cols = array();
        if (!empty($table)) {
            // Lista todas as colunas do banco de dados de uma determinada tabela
            $cols = $this->sections_model->list_columns($table);
        }
        echo json_encode($cols);
    }

    /*
     * Método para listar colunas em array
     */

    private function list_columns($table) {
        $cols = array();
        if (!empty($table)) {
            // Lista todas as colunas do banco de dados de uma determinada tabela
            $cols = $this->sections_model->list_columns($table);
        }
        return $cols;
    }

    /*
     * Método para tratar campos
     */

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
