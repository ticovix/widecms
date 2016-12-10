<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posts extends MY_Controller
{
    /*
     * Variável pública com o limite de seções por página
     */
    public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->data = $this->apps->data_app();
        $this->load->model_app('posts_model');
    }
    /*
     * Método para listar os registros de uma tabela
     */

    public function index($slug_project, $slug_page, $slug_section)
    {
        $section = get_section();
        $project = get_project();
        $page = get_page();
        if (!$section or ! $page) {
            redirect_app('project/' . $slug_project . '/' . $slug_page);
        }
        $this->lang->load_app('posts/posts');
        $this->load->library_app('config_xml');
        $this->load->library_app('form');
        $data = $this->config_xml->load_config($project['directory'], $page['directory'], $section['directory']);
        if (!$data) {
            redirect_app('project/' . $slug_project . '/' . $slug_page);
        }

        $this->load->setVars(array(
            'dev_mode' => $this->data_user['dev_mode']
        ));
        if (isset($data['list'])) {
            // Se algum campo estiver para ser listado, significa que o administrador pode inserir mais de um registro
            $this->mount_list($data, $section, $project, $page);
        } else {
            // Se não, o administrador pode editar um registro, levando direto para formulário
            $this->mount_form($data, $section, $project, $page);
        }
    }
    /*
     * Método para montar o formulário de edição
     */

    private function mount_form($data, $section, $project, $page)
    {
        $this->include_components->app_js(array(
            'js/masks/js/jquery.meio.js',
            'posts/js/form.js'
        ))->app_css('posts/css/post-form.css');

        $post = $this->posts_model->get_post($section);
        if (!$post) {
            // Verifica se já existe um registro na tabela, caso não exista, cria um novo e seta
            $this->posts_model->create(null, $section);
            $post = $this->posts_model->get_post($section);
        }

        // Recebe todos os campos do formulário
        $data_fields = $data['fields'];
        $this->form_edit_post($data_fields, $section, $post);

        // Gera o template baseado nos campos do config xml
        $fields = $this->form->fields_template($data_fields, $post);
        $vars = array(
            'title' => $section['name'],
            'name_app' => $this->data['name'],
            'breadcrumb_section' => false,
            'fields' => $fields,
            'slug_section' => $section['slug'],
            'slug_project' => $project['slug'],
            'slug_page' => $page['slug'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'method' => $project['slug'] . '-' . $page['slug'] . '-' . $section['slug']
        );

        $this->load->template_app('posts/form-post', $vars);
    }
    /*
     * Método para listar os registros com possibilidade de inserir, editar e deletar registros
     */

    private function mount_list($data, $section, $project, $page)
    {
        $this->include_components->app_js(array(
            'js/masks/js/jquery.meio.js',
            'posts/js/posts.js',
        ))->app_css('posts/css/posts-list.css');

        $form_search = $this->mount_form_search($data, $section);
        $search = $this->form_search($form_search, $data, $section);
        $posts = $search['posts'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);

        $vars = array(
            'title' => $section['name'],
            'name_app' => $this->data['name'],
            'list' => $data['list'],
            'total_list' => (count($data['list']) + 1),
            'posts' => $this->form->treat_list($posts['rows'], $data),
            'slug_section' => $section['slug'],
            'slug_project' => $project['slug'],
            'slug_page' => $page['slug'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'form_search' => $form_search,
            'pagination' => $pagination,
            'total' => $total_rows,
            'method' => $project['slug'] . '-' . $page['slug'] . '-' . $section['slug']
        );
        $this->load->template_app('posts/index', $vars);
    }
    /*
     * Montagem de formulário para pesquisa avançada na index
     */

    private function mount_form_search($data, $section)
    {
        $this->include_components->main_js('plugins/chosen/js/chosen.jquery.min.js')
                ->app_js('posts/js/events-select.js')
                ->main_css('plugins/chosen/css/chosen.css');

        $fields = $data['fields'];
        $fields_search = array();
        if (!$fields) {
            return false;
        }

        foreach ($fields as $field) {
            $type = $field['type'];
            $type_column = $field['type_column'];
            $column = $field['column'];
            $plugins = $field['plugins'];
            $label = $field['label'];
            $input = '';
            if ($type != 'file' && $type != 'multifile' && $type != 'hidden') {
                $value = $this->input->get($column);
                $attr = array(
                    'name' => $column,
                    'id' => $column . '_field',
                    'class' => 'form-control input-search'
                );
                if ($type === 'select' or $type === 'checkbox') {
                    $list_posts_select = array();
                    $field['value'] = $value;
                    $column_trigger = $field['options_trigger_select'];
                    $column_label = $field['options_label'];
                    $table = $field['options_table'];
                    $options = array(
                        '' => $this->lang->line(APP . '_select_default')
                    );
                    $attr['class'] .= ' chosen-select trigger-select ';
                    if (!empty($column_trigger)) {
                        $field_trigger = search($fields, 'column', $column_trigger);
                        if ($field_trigger) {
                            $label_trigger = $field_trigger[0]['label'];
                            $table_trigger = $field_trigger[0]['options_table'];
                            $value_trigger = $this->input->get($column_trigger);
                            $attr['class'] .= 'trigger-' . $column_trigger;
                            if (!empty($value_trigger)) {
                                $data_trigger = array(
                                    'table' => $table_trigger,
                                    'column' => $column_trigger,
                                    'value' => $value_trigger,
                                );
                                $list_posts_select = $this->posts_model->list_posts_select($table, $column_label, $data_trigger);
                            } else {
                                $options = array(
                                    '' => $this->lang->line(APP . '_subselect_default') . $label_trigger
                                );
                            }
                        }
                    } else {
                        $list_posts_select = $this->posts_model->list_posts_select($table, $column_label);
                    }

                    if ($list_posts_select) {
                        foreach ($list_posts_select as $posts) {
                            $value_ = $posts['value'];
                            $label_ = $posts['label'];
                            $options[$value_] = $label_;
                        }
                    }

                    $input = form_dropdown($column, $options, $value, $attr);
                } else {
                    $attr['type'] = 'text';
                    $attr['placeholder'] = $this->lang->line(APP . '_field_placeholder_default');
                    if ($type_column == 'date' || $type_column == 'datetime') {
                        // Cria dois campos para filtrar por datas
                        $attr['placeholder'] = 'YYYY-MM-DD';
                        $attr['name'] = 'field_' . $column . '[]';
                        $attr['alt'] = '9999-19-39'; // Mask
                        $field['label'] = $label . ' - ' . $this->lang->line(APP . '_label_date_of');
                        $field['input'] = form_input($attr, $value[0]);
                        $field['value'] = $value[0];
                        $field['type_date'] = 'of';
                        $fields_search[] = $field;
                        // Prepara o segundo campo de data
                        $value = $value[1];
                        $field['label'] = $label . ' - ' . $this->lang->line(APP . '_label_date_until');
                        $field['type_date'] = 'until';
                    } else {
                        // Adiciona input hidden para identificar o tipo de pesquisa
                        // (Exceto para date, datetime, select, checkbox que não tem opção de tipo de pesquisa)
                        $value_type = $this->input->get('type_search_' . $column);
                        $input .= form_input(array(
                            'id' => 'type_search_' . $column,
                            'name' => 'type_search_' . $column,
                            'type' => 'hidden'
                                ), $value_type);
                        $field['value_type'] = $value_type;
                    }

                    $input .= form_input($attr, $value);
                    $field['value'] = $value;
                }

                $field['input'] = $input;
                $fields_search[] = $field;
            }
        }
        return $fields_search;
    }
    /*
     * Método com formulário de pesquisa de registros
     */

    private function form_search($form_search, $data, $section)
    {
        $this->form_validation->set_rules('wd_search', $this->lang->line(APP . '_field_search'), 'trim|required');
        $this->form_validation->run();
        $keyword = $this->input->get('wd_search');
        $perPage = $this->input->get('wd_per_page');

        $limit = $this->input->get('wd_limit');
        if (!empty($limit) && $limit <= 100) {
            $this->limit = $limit;
        } else {
            $limit = $this->limit;
        }

        $posts = $this->posts_model->search($form_search, $data, $section, $keyword, $limit, $perPage);
        $total_rows = $posts['total'];

        return array(
            'posts' => $posts,
            'total_rows' => $total_rows
        );
    }
    /*
     * Método para criar template da paginação da listagem de registros
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
        $config['first_url'] = '?wd_per_page=0';
        $config['query_string_segment'] = 'wd_per_page';
        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }
    /*
     * Método para listar os options do select no json acionado por um js
     */

    public function options_json()
    {
        $this->form_validation->set_rules('project', 'Projeto', 'required');
        $this->form_validation->set_rules('page', 'Página', 'required');
        $this->form_validation->set_rules('section', 'Seção', 'required');
        $this->form_validation->set_rules('id_post', 'ID', 'required');
        $this->form_validation->set_rules('name_trigger', 'Select gatilho', 'required');
        $this->form_validation->set_rules('name_destination', 'Select de destino', 'required');
        $posts = array();
        if ($this->form_validation->run()) {
            $project = $this->input->post('project');
            $page = $this->input->post('page');
            $section = $this->input->post('section');
            $value = (int) $this->input->post('id_post');
            $name_trigger = $this->input->post('name_trigger');
            $name_destination = $this->input->post('name_destination');
            $this->load->library_app('config_xml');
            $data = $this->config_xml->load_config($project, $page, $section);
            if ($data) {
                $field_trigger = search($data, 'column', $name_trigger);
                $field_destination = search($data, 'column', $name_destination);
                if ($field_destination && $field_trigger) {
                    $field_trigger = $field_trigger[0];
                    $field_destination = $field_destination[0];
                    $data_trigger = array(
                        'table' => $field_trigger['options'],
                        'column' => $field_trigger['column'],
                        'value' => $value,
                        'label' => $field_trigger['label']
                    );
                    $posts = $this->posts_model->list_posts_select($field_destination['options'], $field_destination['label_options'], $data_trigger);
                }
            }
        }

        echo json_encode($posts);
    }
    /*
     * Método para setar um valor que possui um método de entrada
     */

    private function set_value($value, $field, $fields)
    {
        $type = strtolower($field['type']);
        $plugins = $this->form->get_plugins($field['plugins']);
        if ($plugins) {
            foreach ($plugins as $plugin) {
                $class = ucfirst($plugin['plugin']);
                $class_plugin = getcwd() . '/application/' . APP_PATH . 'plugins_input/' . $plugin['plugin'] . '/' . $class . '.php';
                if (is_file($class_plugin)) {
                    // Se o campo possui método de entrada
                    $this->load->library_app('../plugins_input/' . $plugin['plugin'] . '/' . $class);
                    if (method_exists($class, 'input')) {
                        $class = strtolower($class);
                        // Se o método existir, aciona e modifica o valor
                        $value = $this->$class->input($value, $field, $fields);
                    }
                }
            }
        }

        switch ($type) {
            case 'checkbox':
                $value = json_encode($value);
                break;
        }

        return $value;
    }
    /*
     * Método para criar registro
     */

    public function create($slug_project, $slug_page, $slug_section)
    {
        $section = get_section();
        $project = get_project();
        $page = get_page();

        if (!$section or ! $project or ! $page) {
            redirect_app();
        }

        $this->include_components->app_css('posts/css/post-form.css');
        $this->load->library_app('config_xml');
        $this->load->library_app('form');

        $data = $this->config_xml->load_config($project['directory'], $page['directory'], $section['directory']);
        $data_fields = $data['fields'];
        $this->form_create_post($data_fields, $project, $page, $section);
        // Seta template para criação do formulário
        $fields = $this->form->fields_template($data_fields);
        $vars = array(
            'title' => $this->lang->line(APP . '_title_add_post'),
            'name_app' => $this->data['name'],
            'breadcrumb_section' => true,
            'fields' => $fields,
            'slug_section' => $section['slug'],
            'slug_project' => $project['slug'],
            'slug_page' => $page['slug'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'dev_mode' => $this->data_user['dev_mode'],
            'method' => $project['slug'] . '-' . $page['slug'] . '-' . $section['slug']
        );

        $this->load->template_app('posts/form-post', $vars);
    }
    /*
     * Método para configuração de requisitos para criação do registro
     */

    private function form_create_post($data_fields, $project, $page, $section)
    {
        $this->include_components->app_js(array(
            'js/masks/js/jquery.meio.js',
            'posts/js/form.js'
        ));
        if (!$data_fields) {
            return false;
        }

        $current_field = array();
        $table = $section['table'];
        foreach ($data_fields as $field) {
            $column = $field['column'];
            $required = $field['required'];
            $unique = $field['unique'];
            $label = $field['label'];
            $input_col = $this->input->post($column);
            $value = $this->set_value($input_col, $field, $data_fields);
            $current_field["$column"] = $value;
            $rules = array('trim');
            if ($required == '1') {
                $rules[] = 'required';
            }

            if ($unique == '1' && !empty($value)) {
                $rules[] = 'is_unique[' . $table . '.' . $column . ']';
            }

            $rules = implode('|', $rules);
            $this->form_validation->set_rules($column, $label, $rules);
        }

        if ($this->form_validation->run()) {
            // Se o envio for acionado e todos os campos estiverem corretos
            if (!hasError()) {
                $this->posts_model->create($current_field, $section);

                redirect_app('project/' . $project['slug'] . '/' . $page['slug'] . '/' . $section['slug']);
            }
        } else {
            setError(validation_errors());
        }
    }
    /*
     * Método para editar registro
     */

    public function edit($slug_project, $slug_page, $slug_section, $id_post)
    {
        $project = get_project();
        $section = get_section();
        $page = get_page();
        $post = $this->posts_model->get_post($section, $id_post);
        if (!$section or ! $project or ! $page or ! $post) {
            redirect_app();
        }

        $this->include_components->app_css('posts/css/post-form.css');
        $this->load->library_app('config_xml');
        $this->load->library_app('form');
        $data = $this->config_xml->load_config($project['directory'], $page['directory'], $section['directory']);
        $data_fields = $data['fields'];
        $this->form_edit_post($data_fields, $section, $post);
        $fields = $this->form->fields_template($data_fields, $post);
        $vars = array(
            'title' => $this->lang->line(APP . '_title_edit_post'),
            'name_app' => $this->data['name'],
            'breadcrumb_section' => true,
            'fields' => $fields,
            'slug_section' => $section['slug'],
            'slug_project' => $project['slug'],
            'slug_page' => $page['slug'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'dev_mode' => $this->data_user['dev_mode'],
            'method' => $project['slug'] . '-' . $page['slug'] . '-' . $section['slug']
        );

        $this->load->template_app('posts/form-post', $vars);
    }
    /*
     * Método para configuração de requisitos para edição do registro
     */

    private function form_edit_post($data_fields, $section, $post)
    {
        if (!$data_fields) {
            return false;
        }

        $this->include_components->app_js(array(
            'js/masks/js/jquery.meio.js',
            'posts/js/form.js'
        ));
        $project = get_project();
        $page = get_page();

        $table = $section['table'];
        $current_field = array();
        foreach ($data_fields as $field) {
            $column = $field['column'];
            $required = $field['required'];
            $unique = $field['unique'];
            $label = $field['label'];
            $input_col = $this->input->post($column);
            $value = $this->set_value($input_col, $field, $data_fields);
            $current_field["$column"] = $value;
            $rules = array('trim');
            if ($required == '1') {
                $rules[] = 'required';
            }

            if ($unique == '1' && !empty($value) && $value != $post[$column]) {
                $rules[] = 'is_unique[' . $table . '.' . $column . ']';
            }

            $rules = implode('|', $rules);
            $this->form_validation->set_rules($column, $label, $rules);
        }
        if ($this->form_validation->run()) {
            // Se o envio for acionado e todos os campos estiverem corretos
            if (!hasError()) {
                $this->posts_model->edit($current_field, $post, $section);
                $list = search($data_fields, 'list_registers', '1');
                if ($list) {
                    redirect_app('project/' . $project['slug'] . '/' . $page['slug'] . '/' . $section['slug']);
                } else {
                    redirect_app(current_url());
                }
            }
        } else {
            setError(validation_errors());
        }
    }
    /*
     * Método para remover registro
     */

    public function remove($slug_project, $slug_page, $slug_section)
    {
        $section = get_section();
        $project = get_project();
        $page = get_page();
        $post = $this->input->post('post');
        $this->lang->load_app('posts/remove');
        $this->load->library_app('config_xml');
        $this->load->library_app('form');
        $data = $this->config_xml->load_config($project['directory'], $page['directory'], $section['directory']);
        if (!$section or ! $page or ! $data or count($post) <= 0) {
            redirect_app();
        }

        $posts = $this->posts_model->get_posts_remove($data, $section['table'], $post);
        if (!$posts) {
            redirect_app();
        }

        $this->form_remove($page, $project, $section);
        $this->include_components->app_css('posts/css/posts-list.css');
        $vars = array(
            'title' => $this->lang->line(APP . '_title_remove'),
            'list' => $data['list'],
            'posts' => $this->form->treat_list($posts, $data),
            'name_app' => $this->data['name'],
            'slug_project' => $project['slug'],
            'name_project' => $project['name'],
            'slug_section' => $section['slug'],
            'name_section' => $section['name'],
            'slug_page' => $page['slug'],
            'name_page' => $page['name'],
            'dev_mode' => $this->data_user['dev_mode']
        );

        $this->load->template_app('posts/remove', $vars);
    }

    private function form_remove($page, $project, $section)
    {
        $pass = $this->input->post('password');
        if (!empty($pass)) {
            $this->form_validation->set_rules('password', $this->lang->line(APP . '_label_password'), 'callback_verify_password');
        }

        if ($this->form_validation->run()) {
            $slug_project = $project['slug'];
            $slug_page = $page['slug'];
            $slug_section = $section['slug'];
            $table_section = $section['table'];
            $posts = $this->input->post('post');
            if (count($posts) > 0) {
                $this->posts_model->remove($table_section, $posts);
            }

            redirect_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section);
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
}