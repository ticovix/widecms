<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posts extends MY_Controller
{
    public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->app_data = $this->apps->data_app();
        $this->load->app()->model('posts_model');
    }

    public function index($project_dir, $page_dir)
    {
        $section = $this->treat_section(get_section());
        $project = get_project();
        $page = get_page();
        if (!$section or ! $page) {
            app_redirect('project/' . $project_dir . '/' . $page_dir);
        }


        $this->lang->load_app('posts/posts');
        $this->load->library('form_validation');
        $this->load->app()->library('form');

        if (isset($section['list'])) {
            $this->mount_list($section, $project, $page);
        } else {
            $this->mount_form($section, $project, $page);
        }
    }

    private function treat_section($section)
    {
        if (!$section) {
            return false;
        }

        foreach ($section['fields'] as $field) {
            if ($field['input']['list_registers'] == '1') {
                $section['select_query'][] = $field['database']['column'];
                $section['list'][] = $field;
            }
        }

        return $section;
    }

    private function mount_form($section, $project, $page)
    {
        $post = $this->posts_model->get_post($section);
        if (!$post) {
            $this->posts_model->create(null, $section);
            $post = $this->posts_model->get_post($section);
        }

        $data_fields = $section['fields'];
        $this->form_edit_post($data_fields, $section, $post);

        $fields = $this->form->fields_template($data_fields, $post);
        $this->data = array_merge($this->data, array(
            'title' => $section['name'],
            'dev_mode' => $this->user_data['dev_mode'],
            'name_app' => $this->app_data['name'],
            'breadcrumb_section' => false,
            'fields' => $fields,
            'section_dir' => $section['directory'],
            'project_dir' => $project['directory'],
            'page_dir' => $page['directory'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'method' => $project['directory'] . '-' . $page['directory'] . '-' . $section['directory']
        ));

        $this->include_components->app_js(array(
            'js/masks/js/jquery.meio.js',
            'posts/js/form.js'
        ))->app_css('posts/css/post-form.css');

        echo $this->load->app()->render('posts/form-post.twig', $this->data);
    }

    private function mount_list($section, $project, $page)
    {
        $form_search = $this->mount_form_search($section);
        $search = $this->form_search($form_search, $section);
        $posts = $search['posts'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);

        $this->data = array_merge($this->data, array(
            'title' => $section['name'],
            'dev_mode' => $this->user_data['dev_mode'],
            'name_app' => $this->app_data['name'],
            'list' => $section['list'],
            'total_list' => (count($section['list']) + 1),
            'posts' => $this->form->treat_list($posts['rows'], $section),
            'section_dir' => $section['directory'],
            'project_dir' => $project['directory'],
            'page_dir' => $page['directory'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'form_search' => $form_search,
            'pagination' => $pagination,
            'total' => $total_rows,
            'method' => $project['directory'] . '-' . $page['directory'] . '-' . $section['directory'],
            'search' => $this->input->get('wd_search'),
            'limit' => $this->input->get('wd_limit'),
        ));

        $this->include_components->app_js(array(
            'js/masks/js/jquery.meio.js',
            'posts/js/posts.js',
        ))->app_css('posts/css/posts-list.css');

        echo $this->load->app()->render('posts/index.twig', $this->data);
    }

    private function mount_form_search($section)
    {
        $fields = $section['fields'];
        $fields_search = array();
        if (!$fields) {
            return false;
        }

        foreach ($fields as $field) {
            $type = $field['input']['type'];
            $type_column = $field['database']['type_column'];
            $column = $field['database']['column'];
            $label = $field['input']['label'];
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
                    $field['input']['value'] = $value;
                    $column_trigger = $field['input']['options']['trigger_select'];
                    $column_label = $field['input']['options']['options_label'];
                    $table = $field['input']['options']['table'];
                    $options = array(
                        '' => $this->lang->line(APP . '_select_default')
                    );
                    $attr['class'] .= ' chosen-select trigger-select ';
                    if (!empty($column_trigger)) {
                        $field_trigger = search($fields, 'column', $column_trigger);
                        if ($field_trigger) {
                            $label_trigger = $field_trigger[0]['input']['label'];
                            $table_trigger = $field_trigger[0]['input']['options_table'];
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
                        $attr['placeholder'] = 'YYYY-MM-DD';
                        $attr['name'] = 'field_' . $column . '[]';
                        $attr['alt'] = '9999-19-39';
                        $field['input']['label'] = $label . ' - ' . $this->lang->line(APP . '_label_date_of');
                        $field['input']['input'] = form_input($attr, $value[0]);
                        $field['input']['value'] = $value[0];
                        $field['input']['type_date'] = 'of';
                        $fields_search[] = $field;
                        $value = $value[1];
                        $field['input']['label'] = $label . ' - ' . $this->lang->line(APP . '_label_date_until');
                        $field['input']['type_date'] = 'until';
                    } else {
                        $value_type = $this->input->get('type_search_' . $column);
                        $input .= form_input(array(
                            'id' => 'type_search_' . $column,
                            'name' => 'type_search_' . $column,
                            'type' => 'hidden'
                                ), $value_type);
                        $field['input']['value_type'] = $value_type;
                    }

                    $input .= form_input($attr, $value);
                    $field['input']['value'] = $value;
                }

                $field['input']['html'] = $input;
                $fields_search[] = $field;
            }
        }

        $this->include_components->main_js('plugins/chosen/js/chosen.jquery.min.js')
                ->app_js('posts/js/events-select.js')
                ->main_css('plugins/chosen/css/chosen.css');

        return $fields_search;
    }

    private function form_search($form_search, $section)
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

        $posts = $this->posts_model->search($form_search, $section, $keyword, $limit, $perPage);
        $total_rows = $posts['total'];

        return array(
            'posts' => $posts,
            'total_rows' => $total_rows
        );
    }

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

    public function options_json()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('project', 'Projeto', 'required');
        $this->form_validation->set_rules('page', 'Página', 'required');
        $this->form_validation->set_rules('section', 'Seção', 'required');
        $this->form_validation->set_rules('id_post', 'ID', 'required');
        $this->form_validation->set_rules('name_trigger', 'Select gatilho', 'required');
        $this->form_validation->set_rules('name_destination', 'Select de destino', 'required');
        $posts = array();
        if ($this->form_validation->run()) {
            $project_dir = $this->input->post('project');
            $page_dir = $this->input->post('page');
            $section_dir = $this->input->post('section');
            $value = (int) $this->input->post('id_post');
            $name_trigger = $this->input->post('name_trigger');
            $name_destination = $this->input->post('name_destination');
            $this->load->app()->model('sections_model');
            $this->load->app()->library('form');
            $section = $this->sections_model->get_section($project_dir, $page_dir, $section_dir);
            if ($section) {
                $fields = $section['fields'];
                $field_trigger = $this->form->search_field($name_trigger, $fields);
                $field_destination = $this->form->search_field($name_destination, $fields);
                if ($field_destination && $field_trigger) {
                    $data_trigger = array(
                        'table' => $field_trigger['input']['options']['table'],
                        'column' => $field_trigger['database']['column'],
                        'value' => $value,
                        'label' => $field_trigger['input']['label']
                    );
                    $posts = $this->posts_model->list_posts_select($field_destination['input']['options']['table'], $field_destination['input']['options']['options_label'], $data_trigger);
                }
            }
        }

        echo json_encode($posts);
    }

    private function set_value($value, $field, $fields)
    {
        $input = $field['input'];
        if (isset($input['plugins'])) {
            $plugins = $this->form->get_plugins($input['plugins']);
            if ($plugins) {
                foreach ($plugins as $plugin) {
                    $class = ucfirst($plugin['plugin']);
                    $class_plugin = getcwd() . '/application/' . APP_PATH . 'plugins_input/' . $plugin['plugin'] . '/' . $class . '.php';
                    if (is_file($class_plugin)) {
                        $this->load->app()->library('../plugins_input/' . $plugin['plugin'] . '/' . $class);
                        if (method_exists($class, 'input')) {
                            $class = strtolower($class);
                            $value = $this->$class->input($value, $field, $fields);
                        }
                    }
                }
            }
        }

        $type = strtolower($input['type']);
        switch ($type) {
            case 'checkbox':
                $value = json_encode($value);
                break;
        }

        return $value;
    }

    public function create($project_dir, $page_dir, $section_dir)
    {
        $section = $this->treat_section(get_section());
        $project = get_project();
        $page = get_page();

        if (!$section or ! $project or ! $page) {
            app_redirect();
        }

        $this->load->app()->library('form');

        $data_fields = $section['fields'];
        $this->form_create_post($data_fields, $project, $page, $section);
        // Seta template para criação do formulário
        $fields = $this->form->fields_template($data_fields);
        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_add_post'),
            'name_app' => $this->app_data['name'],
            'breadcrumb_section' => true,
            'fields' => $fields,
            'section_dir' => $section_dir,
            'project_dir' => $project_dir,
            'page_dir' => $page_dir,
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'dev_mode' => $this->user_data['dev_mode'],
            'method' => $project_dir . '-' . $page_dir . '-' . $section_dir
        ));

        $this->include_components->app_css('posts/css/post-form.css');
        echo $this->load->app()->render('posts/form-post.twig', $this->data);
    }

    private function form_create_post($data_fields, $project, $page, $section)
    {
        if (!$data_fields) {
            return false;
        }
        $this->load->library('form_validation');
        $this->load->library('error_reporting');
        $current_field = array();
        $table = $section['table'];
        foreach ($data_fields as $field) {
            $database = $field['database'];
            $input = $field['input'];
            $column = $database['column'];
            $required = $input['required'];
            $unique = $database['unique'];
            $label = $input['label'];
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
            if (!$this->error_reporting->has_error()) {
                $this->posts_model->create($current_field, $section);

                app_redirect('project/' . $project['directory'] . '/' . $page['directory'] . '/' . $section['directory']);
            }
        } else {
            $this->error_reporting->set_error(validation_errors());
        }

        $this->include_components->app_js(array(
            'js/masks/js/jquery.meio.js',
            'posts/js/form.js'
        ));
    }

    public function edit($project_dir, $page_dir, $section_dir, $id_post)
    {
        $project = get_project();
        $section = $this->treat_section(get_section());
        $page = get_page();
        $post = $this->posts_model->get_post($section, $id_post);
        if (!$section or ! $project or ! $page or ! $post) {
            app_redirect();
        }

        $this->load->app()->library('form');
        $data_fields = $section['fields'];
        $this->form_edit_post($data_fields, $section, $post);
        $fields = $this->form->fields_template($data_fields, $post);
        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_edit_post'),
            'name_app' => $this->app_data['name'],
            'breadcrumb_section' => true,
            'fields' => $fields,
            'section_dir' => $section_dir,
            'project_dir' => $project_dir,
            'page_dir' => $page_dir,
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'dev_mode' => $this->user_data['dev_mode'],
            'method' => $project_dir . '-' . $page_dir . '-' . $section_dir
        ));

        $this->include_components->app_css('posts/css/post-form.css');
        echo $this->load->app()->render('posts/form-post.twig', $this->data);
    }

    private function form_edit_post($data_fields, $section, $post)
    {
        if (!$data_fields) {
            return false;
        }

        $this->load->library('form_validation');
        $this->load->library('error_reporting');
        $project = get_project();
        $page = get_page();

        $table = $section['table'];
        $current_field = array();
        foreach ($data_fields as $field) {
            $input = $field['input'];
            $database = $field['database'];
            $column = $database['column'];
            $required = $input['required'];
            $unique = $database['unique'];
            $label = $input['label'];
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
            if (!$this->error_reporting->has_error()) {
                $this->posts_model->edit($current_field, $post, $section);
                $list = search($data_fields, 'list_registers', '1');
                if ($list) {
                    app_redirect('project/' . $project['directory'] . '/' . $page['directory'] . '/' . $section['directory']);
                } else {
                    app_redirect(current_url());
                }
            }
        } else {
            $this->error_reporting->set_error(validation_errors());
        }

        $this->include_components->app_js(array(
            'js/masks/js/jquery.meio.js',
            'posts/js/form.js'
        ));
    }

    public function remove($project_dir, $page_dir, $section_dir)
    {
        $section = $this->treat_section(get_section());
        $project = get_project();
        $page = get_page();
        $post = $this->input->post('post');
        $this->lang->load_app('posts/remove');
        $this->load->app()->library('form');
        if (!$section or ! $page or count($post) <= 0) {
            app_redirect();
        }

        $posts = $this->posts_model->get_posts_remove($section, $section['table'], $post);
        if (!$posts) {
            app_redirect();
        }

        $this->form_remove($page, $project, $section);
        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_remove'),
            'list' => $section['list'],
            'posts' => $this->form->treat_list($posts, $section),
            'name_app' => $this->app_data['name'],
            'project_dir' => $project_dir,
            'page_dir' => $page_dir,
            'section_dir' => $section_dir,
            'name_project' => $project['name'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'dev_mode' => $this->user_data['dev_mode']
        ));

        $this->include_components->app_css('posts/css/posts-list.css');
        echo $this->load->app()->render('posts/remove.twig', $this->data);
    }

    private function form_remove($page, $project, $section)
    {
        $this->load->library('form_validation');
        $pass = $this->input->post('password');
        if (!empty($pass)) {
            $this->form_validation->set_rules('password', $this->lang->line(APP . '_label_password'), 'callback_verify_password');
        }

        if ($this->form_validation->run()) {
            $table_section = $section['table'];
            $posts = $this->input->post('post');
            if (count($posts) > 0) {
                $this->posts_model->remove($table_section, $posts);
            }

            app_redirect('project/' . $project['directory'] . '/' . $page['directory'] . '/' . $section['directory']);
        }
    }

    public function verify_password($v_pass)
    {
        $pass_user = $this->user_data['password'];
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        if (!$PasswordHash->CheckPassword($v_pass, $pass_user)) {
            $this->form_validation->set_message('verify_password', $this->lang->line(APP . '_incorrect_password'));

            return false;
        }

        return true;
    }
}