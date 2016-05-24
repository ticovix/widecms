<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posts extends MY_Controller {
    /*
     * Variável pública com o limite de seções por página
     */

    public $limit = 10;

    public function __construct() {
        parent::__construct();
        $this->load->model_app('posts_model');
    }

    /*
     * Método para listar os registros de uma tabela
     */

    public function index($slug_project, $slug_page, $slug_section) {
        $section = get_section();
        $project = get_project();
        $page = get_page();
        if ($section && $page) {
            $dir_section = $section['directory'];
            $name = $section['name'];
            $table = $section['table'];
            $this->load->library_app('config_page');
            // Carrega config xml
            $data = $this->config_page->load_config($project['directory'], $page['directory'], $section['directory']);
            $this->load->setVars(array(
                'dev_mode' => $this->data_user['dev_mode']
            ));
            if ($data) {
                if (isset($data['list'])) {
                    add_css(array(
                        '../../../../assets/plugins/fancybox/css/jquery.fancybox.css',
                        '../../../../assets/plugins/fancybox/css/jquery.fancybox-buttons.css',
                    ));
                    add_js(array(
                        '../../../../assets/plugins/fancybox/js/jquery.fancybox.pack.js',
                        '../../../../assets/plugins/fancybox/js/jquery.fancybox-buttons.js',
                        'posts/js/load_gallery.js'
                    ));
                    // Se algum campo estiver para ser listado, significa que o administrador pode inserir mais de um registro
                    $this->mount_list($data, $section, $project, $page);
                } else {
                    // Se não, o administrador pode editar um registro, levando direto para formulário
                    $this->mount_form($data, $section, $project, $page);
                }
            } else {
                redirect_app('project/' . $slug_project . '/' . $slug_page);
            }
        } else {
            redirect_app('project/' . $slug_project . '/' . $slug_page);
        }
    }

    /*
     * Método para montar o formulário de edição 
     */

    private function mount_form($data, $section, $project, $page) {
        add_css(array(
            'posts/css/post-form.css'
        ));
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
        $this->load->library_app('config_page');
        $fields = $this->config_page->fields_template($data_fields, $post);
        $vars = array(
            'title' => $section['name'],
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
        $this->load->template('posts/form-post', $vars);
    }

    /*
     * Método para listar os registros com possibilidade de inserir, editar e deletar registros
     */

    private function mount_list($data, $section, $project, $page) {
        $this->load->library_app('config_page');
        add_css(array(
            'posts/css/posts-list.css'
        ));
        $search = $this->form_search($data, $section);
        $posts = $search['posts'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);

        $vars = array(
            'title' => $section['name'],
            'list' => $data['list'],
            'total_list' => (count($data['list']) + 1),
            'posts' => $this->config_page->treat_list($posts['rows'], $data),
            'slug_section' => $section['slug'],
            'slug_project' => $project['slug'],
            'slug_page' => $page['slug'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'pagination' => $pagination,
            'total' => $total_rows,
            'method' => $project['slug'] . '-' . $page['slug'] . '-' . $section['slug']
        );
        $this->load->template('posts/index', $vars);
    }

    /*
     * Método com formulário de pesquisa de registros
     */

    private function form_search($data, $section) {
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $this->form_validation->run();
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = $this->limit;
        $posts = $this->posts_model->search($data, $section, $keyword, $limit, $perPage);
        $total_rows = $posts['total'];
        return array(
            'posts' => $posts,
            'total_rows' => $total_rows
        );
    }

    /*
     * Método para criar template da paginação da listagem de registros
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
        $config['first_tag_close'] = '</li>';
        $config['first_url'] = '?per_page=0';
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /*
     * Método para listar os options do select no json acionado por um js
     */

    public function options_json() {
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
            $this->load->library_app('config_page');
            $data = $this->config_page->load_config($project, $page, $section);
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
                    // Lista os registros do campo
                    $posts = $this->posts_model->list_posts_select($field_destination['options'], $field_destination['label_options'], $data_trigger);
                }
            }
        }
        echo json_encode($posts);
    }

    /*
     * Método para setar um valor que possui um método de entrada
     */

    private function set_value($value, $field, $fields) {
        $type = strtolower($field['type']);
        $plugins = $this->config_page->get_plugins($field['plugins']);
        if ($plugins) {
            foreach ($plugins as $plugin) {
                $class = ucfirst($plugin['plugin']);
                $class_plugin = getcwd() . '/application/' . APP_PATH . 'plugins_input/' . $plugin['plugin'] . '/' . $class . '.php';
                if (is_file($class_plugin)) {
                    // Se o campo possui método de entrada
                    $this->load->library_app($class, '/plugins_input/' . $plugin['plugin'] . '/');
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

    public function create($slug_project, $slug_page, $slug_section) {
        $section = get_section();
        $project = get_project();
        $page = get_page();

        if ($section && $project && $page) {
            add_css(array(
                'posts/css/post-form.css'
            ));

            $this->load->library_app('config_page');
            $data = $this->config_page->load_config($project['directory'], $page['directory'], $section['directory']);
            $data_fields = $data['fields'];
            $this->form_create_post($data_fields, $project, $page, $section);
            // Seta template para criação do formulário
            $fields = $this->config_page->fields_template($data_fields);
            $vars = array(
                'title' => 'Novo registro',
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
            $this->load->template('posts/form-post', $vars);
        } else {
            redirect_app();
        }
    }

    /*
     * Método para configuração de requisitos para criação do registro
     */

    private function form_create_post($data_fields, $project, $page, $section) {
        if ($data_fields) {
            $current_field = array();
            $table = $section['table'];
            foreach ($data_fields as $field) {
                $column = $field['column'];
                $required = $field['required'];
                $unique = $field['unique'];
                $label = $field['label'];

                $value = $this->set_value($this->input->post($column), $field, $data_fields);
                $current_field["$column"] = $value;
                $rules = array();
                if ($required == '1') {
                    $rules[] = 'required';
                }
                if ($unique == '1' && !empty($value)) {
                    $rules[] = 'is_unique[' . $table . '.' . $column . ']';
                }
                $rules = implode('|', $rules);
                if (empty($rules)) {
                    $this->form_validation->set_rules($column, $label);
                } else {
                    $this->form_validation->set_rules($column, $label, $rules);
                }
            }
            if ($this->form_validation->run()) {

                // Se o envio for acionado e todos os campos estiverem corretos
                if (!hasError()) {
                    // Se não houver nenhum erro
                    // Cria o post no banco de dados
                    $this->posts_model->create($current_field, $section);
                    redirect_app('project/' . $project['slug'] . '/' . $page['slug'] . '/' . $section['slug']);
                }
            } else {
                setError(null, validation_errors());
            }
        }
    }

    /*
     * Método para editar registro
     */

    public function edit($slug_project, $slug_page, $slug_section, $id_post) {
        $project = get_project();
        $section = get_section();
        $page = get_page();
        $post = $this->posts_model->get_post($section, $id_post);
        add_css(array(
            'posts/css/post-form.css'
        ));
        if ($section && $project && $page && $post) {
            $this->load->library_app('config_page');
            $data = $this->config_page->load_config($project['directory'], $page['directory'], $section['directory']);
            $data_fields = $data['fields'];
            $this->form_edit_post($data_fields, $section, $post);
            // Seta template para criação do formulário
            $fields = $this->config_page->fields_template($data_fields, $post);
            $vars = array(
                'title' => 'Editar registro',
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
            $this->load->template('posts/form-post', $vars);
        } else {
            redirect_app();
        }
    }

    /*
     * Método para configuração de requisitos para edição do registro
     */

    private function form_edit_post($data_fields, $section, $post) {
        $project = get_project();
        $page = get_page();
        if ($data_fields) {
            $table = $section['table'];
            $current_field = array();
            foreach ($data_fields as $field) {
                $column = $field['column'];
                $required = $field['required'];
                $unique = $field['unique'];
                $label = $field['label'];
                $value = $this->set_value($this->input->post($column), $field, $data_fields);
                $current_field["$column"] = $value;
                $rules = array();
                if ($required == '1') {
                    $rules[] = 'required';
                }
                if ($unique == '1' && !empty($value) && $value != $post[$column]) {
                    $rules[] = 'is_unique[' . $table . '.' . $column . ']';
                }
                $rules = implode('|', $rules);
                if (empty($rules)) {
                    $this->form_validation->set_rules($column, $label);
                } else {
                    $this->form_validation->set_rules($column, $label, $rules);
                }
            }
            if ($this->form_validation->run()) {
                // Se o envio for acionado e todos os campos estiverem corretos
                if (!hasError()) {
                    // Se não houver nenhum erro
                    // Edita o post no banco de dados
                    $this->posts_model->edit($current_field, $post, $section);
                    redirect_app(current_url());
                }
            } else {
                setError(null, validation_errors());
            }
        }
    }

    /*
     * Método para remover registro
     */

    public function remove($slug_project, $slug_page, $slug_section, $id_post) {
        $section = get_section();
        $project = get_project();
        $page = get_page();
        $post = $this->posts_model->get_post($section, $id_post);
        if ($section && $page && $post) {
            $this->posts_model->remove($section, $post);
            redirect_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section);
        } else {
            redirect_app();
        }
    }

}
