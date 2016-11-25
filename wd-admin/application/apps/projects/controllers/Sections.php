<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sections extends MY_Controller
{
    private $path_view_project = '';
    /*
     * Variável pública com o limite de seções por página
     */
    public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        func_only_dev();
        $this->data = $this->apps->data_app();
        $this->load->model_app('sections_model');
        $this->path_view_project = 'application/' . APP_PATH . 'views/project/';
    }
    /*
     * Método para listar seções
     */

    public function index()
    {
        $this->lang->load_app('sections/sections');
        $project = get_project();
        $page = get_page();
        $search = $this->form_search($page);
        $sections = $search['sections'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);

        $vars = array(
            'title' => $page['name'],
            'name_app' => $this->data['name'],
            'sections' => $sections,
            'pagination' => $pagination,
            'total' => $total_rows,
            'project' => $project,
            'page' => $page
        );

        $this->load->template_app('dev-sections/index', $vars);
    }
    /*
     * Método de busca de seção
     */

    private function form_search($page)
    {
        $this->form_validation->set_rules('search', $this->lang->line(APP . '_field_search'), 'trim|required');
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

    private function pagination($total_rows)
    {
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
        $config['first_tag_close'] = '</li>';
        $config['first_url'] = '?per_page=0';

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();

        return $pagination;
    }
    /*
     * Método para chamar a view de edição da seção
     */

    public function edit($slug_section)
    {
        $this->lang->load_app('sections/form');
        $project = get_project();
        $page = get_page();
        $id_page = $page['id'];
        $fields = false;
        $section = $this->sections_model->get_section($slug_section, $id_page);
        $this->load->library_app('config_page');
        $this->load->library_app('config_xml');
        $this->load->library_app('form');
        $config = $this->config_xml->load_config($project['directory'], $page['directory'], $section['directory']);
        if ($config) {
            $fields = $this->treat_fields($config['fields']);
            $this->form_edit_section($project, $page, $section, $config);
        } else {
            setError($this->lang->line(APP . '_open_config_fail'));
        }

        $this->include_components
                ->vendor_js('components/jqueryui/jquery-ui.min.js')
                ->vendor_css('components/jqueryui/themes/ui-lightness/jquery-ui.min.css')
                ->main_js('plugins/embeddedjs/ejs.js')
                ->app_js(array('js/masks/js/jquery.meio.js', 'project/js/form-section.js'))
                ->app_css('project/css/form-section.css');
        $vars = array(
            'fields' => $fields,
            'title' => sprintf($this->lang->line(APP . '_title_edit_section'), $section['name']),
            'name_app' => $this->data['name'],
            'name' => $section['name'],
            'directory' => $section['directory'],
            'table' => str_replace($project['preffix'], '', $section['table']),
            'status' => $section['status'],
            'preffix' => $project['preffix'],
            'project' => $project,
            'page' => $page,
            'section' => $section,
            'sections' => $this->list_options($section['id']),
            'inputs' => $this->config_page->inputs(),
            'types' => $this->config_page->types(),
            'plugins_input' => $this->form->list_plugins()
        );

        $this->load->template_app('dev-sections/form', $vars);
    }

    private function list_options($id_section = null)
    {
        $options = $this->sections_model->list_sections_select($id_section);
        $options[] = array('table' => 'wd_users', 'name' => $this->lang->line(APP . '_option_users'));

        return $options;
    }
    /*
     * Método com o formulário de edição da seção
     */

    private function form_edit_section($project, $page, $section, $config)
    {
        $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'trim|required');
        $this->form_validation->set_rules('directory', $this->lang->line(APP . '_label_directory'), 'trim|required|callback_verify_dir_edit');
        $this->form_validation->set_rules('table', $this->lang->line(APP . '_label_table'), 'trim|required|callback_verify_table_edit');
        if ($this->form_validation->run()) {
            $dir_project = $project['directory'];
            $slug_project = $project['slug'];
            $dir_page = $page['directory'];
            $slug_page = $page['slug'];
            $dir_section = $section['directory'];
            /* Array com todos os os campos enviados pelo método post */
            $data = $this->get_post_data($project, $page, $section);
            $data['old_config'] = $config;
            $directory = $data['directory'];

            $dir = $this->path_view_project . $dir_project . '/' . $dir_page . '/';
            rename($dir . $dir_section, $dir . $directory);
            $this->sections_model->edit($data);
            $this->edit_fields($data);

            redirect_app('project/' . $slug_project . '/' . $slug_page);
        } else {
            setError(validation_errors());
        }
    }

    public function verify_dir_edit($directory)
    {
        $project = get_project();
        $page = get_page();
        $section = get_section($this->uri->segment(7));
        $dir_project = $project['directory'];
        $dir_page = $page['directory'];
        $dir_section = $section['directory'];
        $dir = $this->path_view_project . $dir_project . '/' . $dir_page . '/';
        if ($section['directory'] != $directory) {
            if (!is_writable($dir . $dir_section)) {
                $this->form_validation->set_message('verify_dir_edit', sprintf($this->lang->line(APP . '_not_allowed_folder_rename'), $directory));

                return false;
            }
        }

        return true;
    }
    /*
     * Método para verificar regras que devem ser seguidas para editar uma tabela
     */

    public function verify_table_edit($table)
    {
        $project = get_project();
        $preffix = $project['preffix'];
        $table = $preffix . $table;

        $section = get_section($this->uri->segment(7));

        // Verifica se o nome da tabela atual é diferente da enviada
        if ($table != $section['table']) {

            // Verifica se a tabela existe
            $check_table = $this->sections_model->check_table_exists($table);
            if ($check_table) {
                $this->form_validation->set_message('verify_table_edit', sprintf($this->lang->line(APP . '_table_exists'), $table));

                return false;
            }

            // Verifica se a tabela possui o prefixo wd_
            $preffix_wd = substr($table, 0, 3);
            if ($preffix_wd == 'wd_' && !$import) {
                $this->form_validation->set_message('verify_table_edit', $this->lang->line(APP . '_not_allowed_preffix_wd'));

                return false;
            }
        }

        return true;
    }
    /*
     * Método para armazenar em array todos os posts dos métodos de criação e edição da seção
     */

    private function get_post_data($project, $page, $section = null)
    {
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
        $plugins_field = $this->input->post('plugins_field');
        $observation_field = $this->input->post('observation_field');
        $attributes_field = $this->input->post('attributes_field');
        $required_field = $this->input->post('required_field');
        $unique_field = $this->input->post('unique_field');
        $default_field = $this->input->post('default_field');
        $comment_field = $this->input->post('comment_field');
        $remove_field = $this->input->post('remove_field');
        $options_field = $this->input->post('options_field');
        $label_options_field = $this->input->post('label_options_field');
        $trigger_select_field = $this->input->post('trigger_select_field');
        $position = $this->input->post('position');
        // Campos de upload
        $extensions_allowed = $this->input->post('extensions_allowed');
        $image_resize = $this->input->post('image_resize');
        $image_x = $this->input->post('image_x');
        $image_y = $this->input->post('image_y');
        $image_ratio = $this->input->post('image_ratio');
        $image_ratio_x = $this->input->post('image_ratio_x');
        $image_ratio_y = $this->input->post('image_ratio_y');
        $image_ratio_crop = $this->input->post('image_ratio_crop');
        $image_ratio_fill = $this->input->post('image_ratio_fill');
        $image_background_color = $this->input->post('image_background_color');
        $image_convert = $this->input->post('image_convert');
        $image_text = $this->input->post('image_text');
        $image_text_color = $this->input->post('image_text_color');
        $image_text_background = $this->input->post('image_text_background');
        $image_text_opacity = $this->input->post('image_text_opacity');
        $image_text_background_opacity = $this->input->post('image_text_background_opacity');
        $image_text_padding = $this->input->post('image_text_padding');
        $image_text_position = $this->input->post('image_text_position');
        $image_text_direction = $this->input->post('image_text_direction');
        $image_text_x = $this->input->post('image_text_x');
        $image_text_y = $this->input->post('image_text_y');
        $image_thumbnails = $this->input->post('image_thumbnails');

        $table = $project['preffix'] . $table;

        return array(
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
            'plugins_field' => $plugins_field,
            'required_field' => $required_field,
            'unique_field' => $unique_field,
            'default_field' => $default_field,
            'comment_field' => $comment_field,
            'observation_field' => $observation_field,
            'attributes_field' => $attributes_field,
            'remove_field' => $remove_field,
            'position' => $position,
            // Campos para configurar select
            'options_table' => $options_field,
            'options_label' => $label_options_field,
            'options_trigger_select' => $trigger_select_field,
            // Campos de upload
            'extensions_allowed' => preg_replace("/[^a-z0-9,]/", '', str_replace(array('|'), array(','), $extensions_allowed)),
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
    /*
     * Método para editar campos da seção
     */

    private function edit_fields($data)
    {
        // Recebe os campos com as edições atuais a ser atualizadas no xml e banco de dados
        $fields = $this->filter_fields($data);
        // Recebe os campos atuais
        $old_fields = $data['old_config']['fields'];
        if ($fields) {
            $x = 0;
            $new_config = array();
            foreach ($fields as $field) {
                // Lista os campos a ser atualizado
                $position = $field['position'];
                $column = $field['column'];
                $type = $field['type'];
                $limit = $field['limit'];
                $default = $field['default'];
                $comment = $field['comment'];
                $remove = (boolean) $field['remove'];

                $data_mod = array();
                $data_mod['table'] = $data['table'];
                $data_mod = array_merge($field, $data_mod);
                // Verifica se o campo existe no xml atual
                if (isset($old_fields[$x])) {

                    $old_column = $old_fields[$x]['column'];
                    $old_type = strtolower($old_fields[$x]['type_column']);
                    $old_limit = (isset($old_fields[$x]['limit'])) ? $old_fields[$x]['limit'] : '';
                    $old_default = (isset($old_fields[$x]['default'])) ? $old_fields[$x]['default'] : '';
                    $old_comment = (isset($old_fields[$x]['comment'])) ? $old_fields[$x]['comment'] : '';
                    $data_mod['old_column'] = $old_column;
                    $data_mod['old_limit'] = $old_limit;

                    if ($remove) {
                        // Se a opção para remover o campo for selecionada
                        $this->remove_field($data_mod);
                    } elseif ($old_column != $column or
                            $old_type != $type or
                            $old_limit != $limit or
                            $old_default != $default or
                            $old_comment != $comment) {

                        // Se houver atualizações no nome da coluna ou no tipo da coluna
                        // atualiza a coluna com os novos dados
                        $modify = $this->sections_model->modify_column($data_mod);
                        if (!$modify) {
                            // Se não atualizar
                            $data_mod['column'] = $old_column;
                            $data_mod['type'] = $old_type;
                            $data_mod['limit'] = $old_limit;
                            $data_mod['default'] = $old_default;
                            $data_mod['comment'] = $old_comment;
                            setError(sprintf($this->lang->line(APP . '_not_allowed_column_modify'), $old_column));
                        }
                    }
                } else {
                    // Se o campo não existir, insere no banco de dados
                    $this->insert_field($data_mod);
                }

                if (!$remove) {
                    // Se o campo de remoção não for selecionado, inclui no novo xml
                    $new_config[$position] = $data_mod;
                }

                $x++;
            }

            ksort($new_config);

            return $this->save_edit_config($data, $new_config);
        }
    }
    /*
     * Método para remover coluna do banco de dados
     */

    private function remove_field($data)
    {
        // Remove a coluna
        $remove = $this->sections_model->remove_column($data);
        if (!$remove) {
            // Se a coluna não for removida
            setError(sprintf($this->lang->line(APP . '_not_allowed_column_remove'), $data['old_column']));
        }
    }
    /*
     * Método para inserir campo no banco de dados
     */

    private function insert_field($data)
    {
        $field_insert = array();
        $field_insert[] = array(
            'column' => $data['column'],
            'type' => $data['type'],
            'limit' => $data['limit'],
            'default' => $data['default'],
            'comment' => $data['comment']
        );
        $this->sections_model->create_columns($data['table'], $field_insert);
    }
    /*
     * Método para salvar configuração no xml
     */

    private function save_edit_config($data, $new_config)
    {
        if (!$new_config) {
            return false;
        }

        $this->load->library_app('config_page');
        $this->load->library_app('config_xml');
        // Gera uma nova estrutura xml com os novos campos
        $config_xml = $this->config_xml->create_config_xml($new_config);
        if ($config_xml) {
            $path_config_xml = $this->path_view_project . $data['project_directory'] . '/' . $data['page_directory'] . '/' . $data['directory'] . '/config.xml';
            // Abre o arquivo config.xml e atualiza a nova estrutura
            $fp = fopen($path_config_xml, 'w');
            fwrite($fp, $config_xml);
            fclose($fp);
            if (!$fp) {
                // Se não for possível editar o arquivo, restaura todas as modificações
                $this->restore_columns($new_config);
                setError($this->lang->line(APP . '_save_config_fail'));
            } else {
                return true;
            }
        } else {
            // Se houver erros na nova estrutura xml gerada, restaura todas as modificações
            $this->restore_columns($new_config);
            setError($this->lang->line(APP . '_generate_config_fail'));
        }
    }
    /*
     * Método para restaurar as alterações feita nas colunas de uma determinada tabela
     */

    private function restore_columns($data)
    {
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

    public function remove($slug_section)
    {
        $this->lang->load_app('sections/remove');
        $project = get_project();
        $page = get_page();
        $id_page = $page['id'];
        $section = $this->sections_model->get_section($slug_section, $id_page);
        if (!$section or ! $project or ! $page) {
            redirect_app();
        }

        $this->form_remove($section, $project, $page);
        $vars = array(
            'title' => sprintf($this->lang->line(APP . '_title_remove_section'), $section['name']),
            'name_app' => $this->data['name'],
            'project' => $project,
            'section' => $section,
            'page' => $page
        );

        $this->load->template_app('dev-sections/remove', $vars);
    }
    /*
     * Método com configuração dos requisitos para remover projeto
     */

    private function form_remove($section, $project, $page)
    {
        $this->form_validation->set_rules('password', $this->lang->line(APP . '_label_password'), 'required|callback_verify_password');
        $this->form_validation->set_rules('section', $this->lang->line(APP . '_label_section'), 'trim|required|integer');
        if ($this->form_validation->run()) {
            if ($section['id'] != $this->input->post('section')) {
                redirect_app('project/' . $project['slug'] . '/' . $page['slug']);
            }

            $dir_section = $section['directory'];
            $table = $section['table'];
            $id_section = $section['id'];
            $remove = $this->sections_model->remove($table, $id_section);
            if ($remove) {
                // Se tudo relacionado a seção for removida do banco de dados, remove o diretório com as config xml dos formulárioss
                forceRemoveDir($this->path_view_project . $project['directory'] . '/' . $page['directory'] . '/' . $dir_section);
            }

            redirect_app('project/' . $project['slug'] . '/' . $page['slug']);
        }
    }
    /*
     * Método para verificar senha
     */

    public function verify_password($v_pass)
    {
        $pass_user = $this->data_user['password'];
        // Inicia helper PasswordHash
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        // Verifica se a senha está errada
        if (!$PasswordHash->CheckPassword($v_pass, $pass_user)) {
            $this->form_validation->set_message('verify_password', $this->lang->line(APP . '_incorrect_password'));

            return false;
        }

        return true;
    }
    /*
     * Método para criar seção
     */

    public function create()
    {
        $this->lang->load_app('sections/form');
        $this->load->library_app('config_page');
        $this->load->library_app('form');
        $project = get_project();
        $page = get_page();
        $this->form_create_section($project, $page);
        $this->include_components->main_js('plugins/embeddedjs/ejs.js')
                ->vendor_js('components/jqueryui/jquery-ui.min.js')
                ->vendor_css('components/jqueryui/themes/base/jquery-ui.min.css')
                ->app_js(array(
                    'js/masks/js/jquery.meio.js',
                    'project/js/form-section.js'
                ))
                ->app_css('project/css/form-section.css');

        $vars = array(
            'title' => sprintf($this->lang->line(APP . '_title_edit_section'), $page['name']),
            'name_app' => $this->data['name'],
            'name' => '',
            'directory' => '',
            'table' => '',
            'status' => '',
            'fields' => '',
            'preffix' => $project['preffix'],
            'project' => $project,
            'page' => $page,
            'sections' => $this->list_options(),
            'tables_import' => $this->sections_model->list_tables_import(),
            'inputs' => $this->config_page->inputs(),
            'types' => $this->config_page->types(),
            'plugins_input' => $this->form->list_plugins(),
            'label_options' => ''
        );

        $this->load->template_app('dev-sections/form', $vars);
    }
    /*
     * Método com configurações do formulário para criar seção
     */

    private function form_create_section($project, $page)
    {
        $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'trim|required');
        $this->form_validation->set_rules('directory', $this->lang->line(APP . '_label_directory'), 'trim|required|is_unique[wd_sections.directory]|callback_verify_dir_create');
        $this->form_validation->set_rules('table', $this->lang->line(APP . '_label_table'), 'trim|required|callback_verify_table_create');
        if ($this->form_validation->run()) {
            $import = ($this->input->post('import') == 'true');
            $dir_project = $project['directory'];
            $slug_project = $project['slug'];
            $dir_page = $page['directory'];
            $slug_page = $page['slug'];
            // Array com todos os os campos enviados pelo método post
            $data = $this->get_post_data($project, $page);
            $data['import'] = $import;
            $directory = $data['directory'];
            $table = $data['table'];

            if (!$import) {
                // Se não for uma importação de tabela, a tabela é criada no banco de dados
                $this->sections_model->create_table($table);
            }

            // Cria o diretório da seção
            mkdir($this->path_view_project . $dir_project . '/' . $dir_page . '/' . $directory, 0755);
            // Cria os campos no arquivo config.xml e no banco de dados (caso não seja importação de tabela)
            $this->create_fields($data);

            redirect_app('project/' . $slug_project . '/' . $slug_page);
        } else {
            setError(validation_errors());
        }
    }
    /*
     * Método para verificar se é possível criar o diretório onde ficará a seção
     */

    public function verify_dir_create($directory)
    {
        $project = get_project();
        $page = get_page();
        $is_dir_section = is_dir($this->path_view_project . $project['directory'] . '/' . $page['directory'] . '/' . $directory);
        if ($is_dir_section) {
            $this->form_validation->set_message('verify_dir_create', sprintf($this->lang->line(APP . '_folder_exists'), $directory));

            return false;
        }

        $is_writable_section = is_writable($this->path_view_project . $project['directory'] . '/' . $page['directory']);
        if (!$is_writable_section) {
            $this->form_validation->set_message('verify_dir_create', $this->lang->line(APP . '_not_allowed_folder_create'));

            return false;
        }

        return true;
    }
    /*
     * Método para verificar regras que devem ser seguidas para criar uma tabela
     */

    public function verify_table_create($table)
    {
        $project = get_project();
        $preffix = $project['preffix'];
        $table = $preffix . $table;


        // Verifica se a tabela existe
        $import = $this->input->post('import');
        $check_table = $this->sections_model->check_table_exists($table);
        if ($check_table && !$import) {
            $this->form_validation->set_message('verify_table_create', sprintf($this->lang->line(APP . '_table_exists'), $table));

            return false;
        }

        // Verifica se a tabela possui o prefixo wd_
        $preffix_wd = substr($table, 0, 3);
        if ($preffix_wd == 'wd_' && !$import) {
            $this->form_validation->set_message('verify_table_create', $this->lang->line(APP . '_not_allowed_preffix_wd'));

            return false;
        }

        return true;
    }
    /*
     * Método para criar campos
     */

    protected function create_fields($data)
    {
        // Filtra os campos do arquivo xml
        $fields = $this->filter_fields($data);
        if (!is_array($fields) or count($fields) <= 0) {
            return false;
        }

        $this->load->library_app('config_xml');
        // Cria estrutura xml do array
        $config_xml = $this->config_xml->create_config_xml($fields);
        $path_config_xml = $this->path_view_project . $data['project_directory'] . '/' . $data['page_directory'] . '/' . $data['directory'];
        // Cria o arquivo config.xml
        $fp = fopen($path_config_xml . '/config.xml', 'w');
        fwrite($fp, $config_xml);
        fclose($fp);
        chmod($path_config_xml . '/config.xml', 0640);

        if ($fp) {
            // Se o arquivo for criado e não for uma importação de tabelas, cria colunas dos campos no banco de dados
            if (!$data['import']) {
                $this->sections_model->create_columns($data['table'], $fields);
            }
        } else {
            setError(sprintf($this->lang->line(APP . '_create_config_fail'), $path_config_xml));

            return false;
        }
        $this->sections_model->create($data);

        return true;
    }
    /*
     * Método para filtrar campos do config xml
     */

    protected function filter_fields($data)
    {
        $total = count($data['name_field']);
        if (!$total) {
            return false;
        }

        $this->load->library_app('config_page');
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
            $unique_field = $data['unique_field'][$i];
            $default_field = $data['default_field'][$i];
            $comment_field = $data['comment_field'][$i];
            $plugins_field = $data['plugins_field'][$i];
            $observation_field = $data['observation_field'][$i];
            $attributes_field = $data['attributes_field'][$i];
            $position = $data['position'][$i];
            $remove = (is_array($remove_field) && array_key_exists($i, $remove_field));
            // Campos para configurar select
            $options_field = $data['options_table'][$i];
            $label_options_field = $data['options_label'][$i];
            $trigger_select_field = $data['options_trigger_select'][$i];
            // Campos de upload
            $extensions_allowed = $data['extensions_allowed'][$i];
            $image_resize = $data['image_resize'][$i];
            $image_x = $data['image_x'][$i];
            $image_y = $data['image_y'][$i];
            $image_ratio = $data['image_ratio'][$i];
            $image_ratio_x = $data['image_ratio_x'][$i];
            $image_ratio_y = $data['image_ratio_y'][$i];
            $image_ratio_crop = $data['image_ratio_crop'][$i];
            $image_ratio_fill = $data['image_ratio_fill'][$i];
            $image_background_color = $data['image_background_color'][$i];
            $image_convert = $data['image_convert'][$i];
            $image_text = $data['image_text'][$i];
            $image_text_color = $data['image_text_color'][$i];
            $image_text_background = $data['image_text_background'][$i];
            $image_text_opacity = $data['image_text_opacity'][$i];
            $image_text_background_opacity = $data['image_text_background_opacity'][$i];
            $image_text_padding = $data['image_text_padding'][$i];
            $image_text_position = $data['image_text_position'][$i];
            $image_text_direction = $data['image_text_direction'][$i];
            $image_text_x = $data['image_text_x'][$i];
            $image_text_y = $data['image_text_y'][$i];
            $image_thumbnails = $data['image_thumbnails'][$i];
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

                $fields[] = array(
                    'page' => $data['page'],
                    'name' => $name_field,
                    'input' => $input_field,
                    'list_reg' => $list_reg_field,
                    'column' => $column_field,
                    'type' => $type_field,
                    'limit' => $limit_field,
                    'plugins' => $plugins_field,
                    'observation' => $observation_field,
                    'attributes' => $attributes_field,
                    'required' => $required_field,
                    'unique' => $unique_field,
                    'default' => $default_field,
                    'comment' => $comment_field,
                    'remove' => $remove,
                    'position' => $position,
                    // Campos para configurar select
                    'options_table' => $options_field,
                    'options_label' => $label_options_field,
                    'options_trigger_select' => $trigger_select_field,
                    // Campos de upload
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
        }

        return $fields;
    }
    /*
     * Método para verificar se os campos seguem os requisitos do sistema
     */

    protected function verify_fields($data)
    {
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
            setError($this->lang->line(APP . '_create_column_id_not_allowed'));

            return false;
        } elseif ($column_field == $table) {
            // Se o nome da coluna for igual da tabela
            setError($this->lang->line(APP . '_column_equals_table'));

            return false;
        } elseif (count(search($fields, 'column', $column_field)) > 0) {
            // Se o nome da coluna estiver duplicado
            setError(sprintf($this->lang->line(APP . '_duplicate_field_not_allowed'), $column_field));

            return false;
        } elseif (!empty($name_field) && (empty($input_field) or empty($column_field) or empty($type_field))) {
            // Se o nome do campo for preenchido e as outras informações não forem preenchidas
            setError($this->lang->line(APP . '_fields_required'));

            return false;
        } elseif (!empty($options_field) && empty($label_options_field)) {
            // Se o campo options for preenchido, o campo Label se torna obrigatório
            setError(sprintf($this->lang->line(APP . '_options_select_required'), $name_field));
        } else {
            // Se não houver nenhum erro
            return true;
        }
    }
    /*
     * Método que retorna os dados da página
     */

    private function get_page()
    {
        $page = $this->uri->segment(3);
        if (empty($this->page)) {
            $this->load->model_app('pages_model');
            $this->page = $this->pages_model->get_page($page);
        }

        return $this->page;
    }
    /*
     * Método para retornar listagem de colunas em json
     */

    public function list_columns_json()
    {
        $table = $this->input->post('table');
        $cols = array();
        if (!empty($table)) {
            $verify_table = $this->sections_model->check_table_exists($table);
            if ($verify_table) {
                // Lista todas as colunas do banco de dados de uma determinada tabela
                $cols = $this->sections_model->list_columns($table);
            }
        }

        echo json_encode($cols);
    }
    /*
     * Método para listar colunas em array
     */

    private function list_columns($table)
    {
        $cols = array();
        if (!empty($table)) {
            $verify_table = $this->sections_model->check_table_exists($table);
            if ($verify_table) {
                // Lista todas as colunas do banco de dados de uma determinada tabela
                $cols = $this->sections_model->list_columns($table);
            }
        }

        return $cols;
    }
    /*
     * Método para tratar campos
     */

    private function treat_fields($fields)
    {
        $new_fields = array();
        foreach ($fields as $field) {
            if (isset($field['options_table']) && !empty($field['options_table'])) {
                $table = $field['options_table'];
                if ($table) {
                    $field['label_options_'] = $this->list_columns($table);
                }
            }

            $new_fields[] = $field;
        }

        return $new_fields;
    }

    public function list_columns_import()
    {
        func_only_dev();
        $this->form_validation->set_rules('table', 'Tabela', 'required');
        try {
            if ($this->form_validation->run()) {
                $table = $this->input->post('table');
                $columns = $this->treat_columns($this->sections_model->list_columns_import($table));
                $col_primary = search($columns, 'Key', 'PRI');
                if (!$col_primary) {
                    throw new Exception($this->lang->line(APP . '_pk_required'));
                }

                $col_primary = $col_primary[0];
                if ($col_primary['Field'] != 'id') {
                    throw new Exception(sprintf($this->lang->line(APP . '_pk_id_name_required'), $col_primary['Field']));
                }

                echo json_encode(array('error' => false, 'columns' => $columns));
            } else {
                $validation_errors = validation_errors();
                throw new Exception($validation_errors);
            }
        } catch (Exception $e) {
            echo json_encode(array('error' => true, 'message' => $e->getMessage()));
        }
    }

    public function image_example()
    {
        $image_y = $this->input->get("image_y");
        $image_x = $this->input->get("image_x");
        $image_resize = to_boolean($this->input->get("image_resize"));
        $image_ratio = to_boolean($this->input->get("image_ratio"));
        $image_ratio_x = $this->input->get("image_ratio_x");
        $image_ratio_y = $this->input->get("image_ratio_y");
        $image_ratio_crop = to_boolean($this->input->get("image_ratio_crop"));
        $image_ratio_fill = to_boolean($this->input->get("image_ratio_fill"));
        $image_background_color = $this->input->get("image_background_color");
        $image_convert = to_boolean($this->input->get("image_convert"));
        $image_text = $this->input->get("image_text");
        $image_text_color = $this->input->get("image_text_color");
        $image_text_background = $this->input->get("image_text_background");
        $image_text_opacity = $this->input->get("image_text_opacity");
        $image_text_background_opacity = $this->input->get("image_text_background_opacity");
        $image_text_padding = $this->input->get("image_text_padding");
        $image_text_position = $this->input->get("image_text_position");
        $image_text_direction = $this->input->get("image_text_direction");
        $image_text_x = $this->input->get("image_text_x");
        $image_text_y = $this->input->get("image_text_y");
        $this->load->library('upload_verot');
        $tmp = new Upload_verot(APP_ASSETS . 'images/test.png');
        if (!empty($image_resize)) {
            $tmp->image_resize = $image_resize;
        }

        if (!empty($image_y)) {
            $tmp->image_y = $image_y;
        }

        if (!empty($image_x)) {
            $tmp->image_x = $image_x;
        }

        if (!empty($image_ratio)) {
            $tmp->image_ratio = $image_ratio;
        }

        if (!empty($image_ratio_x)) {
            $tmp->image_ratio_x = $image_ratio_x;
        }

        if (!empty($image_ratio_y)) {
            $tmp->image_ratio_y = $image_ratio_y;
        }

        if (!empty($image_ratio_crop)) {
            $tmp->image_ratio_crop = $image_ratio_crop;
        }

        if (!empty($image_ratio_fill)) {
            $tmp->image_ratio_fill = $image_ratio_fill;
        }

        if (!empty($image_background_color)) {
            $tmp->image_background_color = $image_background_color;
        }

        if (!empty($image_convert)) {
            $tmp->image_convert = $image_convert;
        }

        if (!empty($image_text)) {
            $tmp->image_text = $image_text;
        }

        if (!empty($image_text_color)) {
            $tmp->image_text_color = $image_text_color;
        }

        if (!empty($image_text_background)) {
            $tmp->image_text_background = $image_text_background;
        }

        if (!empty($image_text_opacity)) {
            $tmp->image_text_opacity = $image_text_opacity;
        }

        if (!empty($image_text_background_opacity)) {
            $tmp->image_text_background_opacity = $image_text_background_opacity;
        }

        if (!empty($image_text_padding)) {
            $tmp->image_text_padding = $image_text_padding;
        }

        if (!empty($image_text_position)) {
            $tmp->image_text_position = $image_text_position;
        }

        if (!empty($image_text_direction)) {
            $tmp->image_text_direction = $image_text_direction;
        }

        if (!empty($image_text_x)) {
            $tmp->image_text_x = $image_text_x;
        }

        if (!empty($image_text_y)) {
            $tmp->image_text_y = $image_text_y;
        }

        header('Content-type: ' . $tmp->file_src_mime);

        echo $tmp->Process();
    }

    private function treat_columns($columns)
    {
        if ($columns) {
            $this->load->library_app('config_page');
            $types = $this->config_page->types();
            $cols = array();
            foreach ($columns as $col) {
                $type = $col['Type'];
                $type_current = $this->treat_type(preg_replace('/\(.+?\)/', '', $type));
                $limit_current = preg_replace('/.+?\((.+?)\)/', '$1', $type);
                $verify_type = search($types, 'type', $type_current);
                if (!$verify_type) {
                    throw new Exception(sprintf($this->lang->line(APP . '_type_column_not_exists'), $col['Field'], $type_current));
                }

                $col['Type'] = $type_current;
                $col['Limit'] = $limit_current;
                $cols[] = $col;
            }

            $columns = $cols;
        }

        return $columns;
    }

    private function treat_type($type)
    {
        if ($type == 'int') {
            $type = 'integer';
        }

        return $type;
    }
}