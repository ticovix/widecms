<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posts extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('posts_model');
    }

    public function index($slug_project, $slug_page, $slug_section) {
        $section = $this->get_section();
        $project = $this->get_project();
        $page = $this->get_page();
        if ($section && $page) {
            $dir_section = $section['directory'];
            $name = $section['name'];
            $table = $section['table'];
            $this->load->library('config_page');
            $data = $this->config_page->load_config($project['directory'], $page['directory'], $section['directory']);
            $this->load->setVars(array(
                'dev_mode' => $this->data_user['dev_mode']
            ));
            if ($data) {
                if (isset($data['list'])) {
                    $this->mount_list($data, $section, $project, $page);
                } else {
                    $this->mount_form($data, $section, $project, $page);
                }
            } else {
                redirect('project/' . $slug_project . '/' . $slug_page);
            }
        } else {
            redirect('project/' . $slug_project . '/' . $slug_page);
        }
    }

    private function mount_form($data, $section, $project, $page) {
        add_css(array(
            'view/posts/css/post-form.css'
        ));
        $post = $this->posts_model->getPost($section);
        if (!$post) {
            $this->posts_model->createPost(null, $section);
            $post = $this->posts_model->getPost($section);
        }
        $data_fields = $data['fields'];
        if ($data_fields) {
            $current_field = array();
            foreach ($data_fields as $field) {
                $column = $field['column'];
                $required = $field['required'];
                $label = $field['label'];
                $value = $this->set_value($this->input->post($column), $field);
                $current_field["$column"] = $value;
                if ($required == '1') {
                    $this->form_validation->set_rules($column, $label, 'required');
                } else {
                    $this->form_validation->set_rules($column, $label);
                }
            }
            if ($this->form_validation->run()) {
                $this->posts_model->editPost($current_field, $post, $section, $project);
                redirect('project/' . $project['slug'] . '/' . $page['slug']);
            }
        }

        $fields = $this->gen_form($data_fields, $post);
        $vars = array(
            'title' => 'Registro',
            'breadcrumb_section' => false,
            'fields' => $fields,
            'slug_section' => $section['slug'],
            'slug_project' => $project['slug'],
            'slug_page' => $page['slug'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name']
        );
        $this->load->template('posts/form-post', $vars);
    }

    private function mount_list($data, $section, $project, $page) {
        add_css(array(
            'view/posts/css/posts-list.css'
        ));
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $this->form_validation->run();
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = 10;
        $posts = $this->posts_model->search($data, $section, $keyword, $limit, $perPage);
        $total_rows = $posts['total'];

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

        $vars = array(
            'title' => $section['name'],
            'list' => $data['list'],
            'total_list' => (count($data['list']) + 1),
            'posts' => $this->treat_list($posts['rows'], $data),
            'slug_section' => $section['slug'],
            'slug_project' => $project['slug'],
            'slug_page' => $page['slug'],
            'name_section' => $section['name'],
            'name_page' => $page['name'],
            'name_project' => $project['name'],
            'pagination' => $pagination,
            'total' => $total_rows
        );
        $this->load->template('posts/index', $vars);
    }

    private function treat_list($posts, $data) {
        $list = array();
        foreach ($posts as $row) {
            foreach ($row as $key => $value) {
                $field = search($data, 'column', $key);
                if (isset($field[0])) {
                    $type = strtolower($field[0]['type']);
                    if (isset($field[0]['mask'])) {
                        $callback_output = (isset($mask['callback_output'])) ? $mask['callback_output'] : false;
                        if ($callback_output) {
                            $this->load->library('masks_input');
                            if (method_exists($this->masks_input, $callback_output)) {
                                $value = $this->masks_input->$callback_output($value);
                            }
                        }
                    }
                    if ($type == 'select' or $type == 'radio') {
                        if (!empty($value)) {
                            $table = (isset($field[0]['options'])) ? $field[0]['options'] : '';
                            $column = (isset($field[0]['label_options'])) ? $field[0]['label_options'] : '';

                            if ($table && $column) {
                                $val = $this->posts_model->getPostSelected($table, $column, $value);
                                if ($val) {
                                    $value = $val[$column];
                                }
                            }
                        }
                    } elseif ($type == 'file' or $type == 'multifile') {
                        if (!empty($value)) {
                            $files = json_decode($value);
                            $value = $this->list_files($files, 1);
                        }
                    }
                }
                $row[$key] = $value;
            }
            $list[] = $row;
        }
        return $list;
    }

    private function gen_form($fields, $post = null) {
        $this->load->helper('form');
        $this->load->library('config_page');
        $form = array();
        if ($fields) {
            foreach ($fields as $field) {
                $type = strtolower($field['type']);
                $column = $field['column'];
                $required = $field['required'];
                $mask = $this->config_page->get_mask($field['mask']);
                $label = ($required) ? $field['label'] . '<span>*</span>' : $field['label'];
                $value = (!empty($post)) ? $post[$column] : '';
                $new_field = array();
                $attr = array();
                if ($mask) {
                    $attr = (isset($mask['attr'])) ? $mask['attr'] : $attr;
                    $js = (isset($mask['js'])) ? $mask['js'] : false;
                    $css = (isset($mask['css'])) ? $mask['css'] : false;
                    $callback_output = (isset($mask['callback_output'])) ? $mask['callback_output'] : false;
                    if ($js) {
                        add_js($js);
                    }
                    if ($css) {
                        add_css($css);
                    }
                    if ($callback_output) {
                        $this->load->library('masks_input');
                        if (method_exists($this->masks_input, $callback_output)) {
                            $value = $this->masks_input->$callback_output($value);
                        }
                    }
                }
                if ($type == 'file' or $type == 'multifile') {
                    add_css(array(
                        'plugins/fancybox/css/jquery.fancybox.css',
                        'plugins/fancybox/css/jquery.fancybox-buttons.css',
                        'plugins/dropzone/css/dropzone.css',
                        'view/project/css/gallery.css'
                    ));
                    add_js(array(
                        'plugins/dropzone/js/dropzone.js',
                        'plugins/fancybox/js/jquery.fancybox.pack.js',
                        'plugins/fancybox/js/jquery.fancybox-buttons.js',
                        'plugins/embeddedjs/ejs.js',
                        'view/posts/js/gallery.js'
                    ));
                    $new_field['type'] = $type;
                    $new_field['label'] = $label;
                    $attr['data-field'] = $column;
                    $attr['class'] = 'form-control btn-gallery ' . (isset($attr['class']) ? $attr['class'] : '');
                    $attr['data-toggle'] = 'modal';
                    $attr['data-target'] = '#gallery';
                    $files = json_decode($value);
                    $new_field['content_top'] = $this->list_files($files);
                    $new_field['input'] = form_button($attr, '<span class="fa fa-cloud"></span> Galeria');

                    $attr = array();
                    if ($type == 'multifile') {
                        $attr['multiple'] = "true";
                    }
                    $attr['id'] = $column . '-field';
                    $attr['name'] = $column;
                    $attr['type'] = 'hidden';
                    $new_field['input'] .= form_input($attr, $value);
                } elseif ($type == 'textarea') {
                    $new_field['type'] = $type;
                    $new_field['label'] = $label;
                    $attr['name'] = $column;
                    $attr['class'] = 'form-control ' . (isset($attr['class']) ? $attr['class'] : '');
                    $new_field['input'] = form_textarea($attr, $value);
                } elseif ($type == 'select') {
                    add_js(array(
                        'view/posts/js/events-select.js'
                    ));
                    $new_field['type'] = $type;
                    $new_field['label'] = $label;
                    if (isset($field['options']) && isset($field['label_options'])) {
                        $column_trigger = (isset($field['trigger_select'])) ? $field['trigger_select'] : '';
                        $data_trigger = null;
                        if ($column_trigger) {
                            $field_trigger = search($fields, 'column', $column_trigger);
                            if (count($field_trigger) > 0) {
                                $field_trigger = $field_trigger[0];
                                $table_trigger = $field_trigger['options'];
                                $label_trigger = $field_trigger['label'];
                                $value_trigger = $post[$column_trigger];
                                $attr['class'] = (isset($attr['class'])) ? $attr['class'] : '';
                                $attr['class'] .= ' trigger-' . $column_trigger;
                                $data_trigger = array(
                                    'table' => $table_trigger,
                                    'column' => $column_trigger,
                                    'value' => $value_trigger,
                                    'label' => $label_trigger
                                );
                            } else {
                                $field_trigger = null;
                            }
                        }
                        $array_options = $this->set_options($field['options'], $field['label_options'], $data_trigger);
                    } else {
                        $array_options = array('' => 'Nenhum opção adicionada.');
                    }
                    $attr['class'] = 'form-control trigger-select ' . (isset($attr['class']) ? $attr['class'] : '');
                    $new_field['input'] = form_dropdown($column, $array_options, $value, $attr);
                } elseif ($type == 'hidden') {
                    $attr['name'] = $column;
                    $attr['class'] = 'form-control ' . (isset($attr['class']) ? $attr['class'] : '');
                    $new_field['input'] = form_hidden('my_array', $attr);
                } else {
                    $new_field['type'] = $type;
                    $new_field['label'] = $label;
                    $attr['name'] = $column;
                    $attr['type'] = $type;
                    $attr['class'] = 'form-control ' . (isset($attr['class']) ? $attr['class'] : '');
                    $new_field['input'] = form_input($attr, $value);
                }
                $form[] = $new_field;
            }
        }
        return $form;
    }

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
            $this->load->library('config_page');
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
                    
                    $posts = $this->posts_model->listPostsSelect($field_destination['options'], $field_destination['label_options'], $data_trigger);
                }
            }
        }
        echo json_encode($posts);
    }

    private function set_options($table, $column, $data_trigger = null) {
        if (is_array($data_trigger) && empty($data_trigger['value'])) {
            return array('' => 'Selecione uma ' . $data_trigger['label']);
        }

        $posts = $this->posts_model->listPostsSelect($table, $column, $data_trigger);
        $options = array();
        if ($posts) {
            $options[''] = 'Selecione';
            foreach ($posts as $post) {
                $id = $post['value'];
                $value = $post['label'];
                $options[$id] = $value;
            }
        } else {
            $options[''] = 'Nenhuma opção encontrada.';
        }
        return $options;
    }

    private function list_files($files, $cols = 2) {
        $files = (array) $files;
        if ($files) {
            $path = PATH_UPLOAD;
            $ctt = '<div class="content-files">';
            foreach ($files as $file) {
                $file_ = $file->file;
                if (!empty($file)) {
                    $ctt .= '<div class="files-list thumbnail"><img src="' . base_url('gallery/image/thumb/' . $file_) . '" class="img-responsive"></div>';
                }
            }
            $ctt .= '</div>';
            return $ctt;
        }
    }

    private function set_value($value, $field) {
        if ($value) {
            $type = strtolower($field['type']);
            $mask = $this->config_page->get_mask($field['mask']);
            if ($mask) {
                $callback_input = (isset($mask['callback_input'])) ? $mask['callback_input'] : false;
                if ($callback_input) {
                    $this->load->library('masks_input');
                    if (method_exists($this->masks_input, $callback_input)) {
                        $value = $this->masks_input->$callback_input($value);
                    }
                }
            }
            /* if ($type == 'file' or $type == 'multifile') {

              } */
        }
        return $value;
    }

    public function create_post($slug_project, $slug_page, $slug_section) {
        add_css(array(
            'view/posts/css/post-form.css'
        ));
        $section = $this->get_section();
        $project = $this->get_project();
        $page = $this->get_page();
        if ($section && $project && $page) {
            $this->load->library('config_page');
            $data = $this->config_page->load_config($project['directory'], $page['directory'], $section['directory']);
            $data_fields = $data['fields'];
            if ($data_fields) {
                $current_field = array();
                foreach ($data_fields as $field) {
                    $column = $field['column'];
                    $required = $field['required'];
                    $label = $field['label'];

                    $value = $this->set_value($this->input->post($column), $field);
                    $current_field["$column"] = $value;

                    if ($required == '1') {
                        $this->form_validation->set_rules($column, $label, 'required');
                    } else {
                        $this->form_validation->set_rules($column, $label);
                    }
                }
                if ($this->form_validation->run()) {
                    $this->posts_model->createPost($current_field, $section);
                    redirect('project/' . $project['slug'] . '/' . $page['slug'] . '/' . $section['slug']);
                }
            }

            $fields = $this->gen_form($data_fields);
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
                'dev_mode' => $this->data_user['dev_mode']
            );
            $this->load->template('posts/form-post', $vars);
        } else {
            redirect('projects');
        }
    }

    public function edit_post($slug_project, $slug_page, $slug_section, $id_post) {
        add_css(array(
            'view/posts/css/post-form.css'
        ));
        $project = $this->get_project();
        $section = $this->get_section();
        $page = $this->get_page();
        $post = $this->posts_model->getPost($section, $id_post);
        if ($section && $project && $page && $post) {
            $this->load->library('config_page');
            $data = $this->config_page->load_config($project['directory'], $page['directory'], $section['directory']);
            $data_fields = $data['fields'];
            if ($data_fields) {
                $current_field = array();
                foreach ($data_fields as $field) {
                    $column = $field['column'];
                    $required = $field['required'];
                    $label = $field['label'];
                    $value = $this->set_value($this->input->post($column), $field);
                    $current_field["$column"] = $value;
                    if ($required == '1') {
                        $this->form_validation->set_rules($column, $label, 'required');
                    } else {
                        $this->form_validation->set_rules($column, $label);
                    }
                }
                if ($this->form_validation->run()) {
                    $this->posts_model->editPost($current_field, $post, $section);
                    redirect('project/' . $project['slug'] . '/' . $page['slug'] . '/' . $section['slug']);
                }
            }

            $fields = $this->gen_form($data_fields, $post);
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
                'dev_mode' => $this->data_user['dev_mode']
            );
            $this->load->template('posts/form-post', $vars);
        } else {
            redirect('projects');
        }
    }

    public function remove_post($slug_project, $slug_page, $slug_section, $id_post) {
        $section = $this->get_section();
        $project = $this->get_project();
        $page = $this->get_page();
        $post = $this->posts_model->getPost($section, $id_post);
        if ($section && $page && $post) {
            $this->posts_model->removePost($section, $post);
            redirect('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section);
        } else {
            redirect('projects');
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

    private function get_section() {
        $section = $this->uri->segment(4);
        if (empty($this->section)) {
            $this->load->model('sections_model');
            return $this->section = $this->sections_model->getSection($section);
        } else {
            return $this->section;
        }
    }

}
