<?php

class Project extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('project_model');
    }

    public function index($project) {
        $project = $this->project_model->getProject($project);
        if (!$project) {
            redirect('projects');
        }
        $dev_mode = $this->dataUser['dev_mode'];
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = 10;
        $this->form_validation->run();
        $pages = $this->project_model->search($project['id'], $dev_mode, $keyword, $limit, $perPage);
        $total_rows = $this->project_model->searchTotalRows($project['id'], $dev_mode, $keyword);

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

        add_js([
            'view/project/js/list-pages.js'
        ]);
        $vars = [
            'title' => $project['name'],
            'pages' => $this->includeSections($pages),
            'pagination' => $pagination,
            'total' => $total_rows,
            'project' => $project
        ];
        if ($dev_mode) {
            $this->load->template('dev-project/index', $vars);
        } else {
            $this->load->template('project/index', $vars);
        }
    }

    protected function includeSections($pages) {
        if (count($pages)) {
            foreach ($pages as $page) {
                $page['sections'] = $this->project_model->listSections($page['id']);
                $arr[] = $page;
            }
            return $arr;
        }
    }

    public function create_page($project) {
        $project = $this->project_model->getProject($project);
        if (!$project) {
            redirect('projects/' . $project['slug']);
        }

        $this->form_validation->set_rules('name', 'Nome', 'required|is_unique[wd_pages.name]');
        $this->form_validation->set_rules('status', 'Status', 'required|integer');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $slug = slug($name);
            if (@mkdir(getcwd() . '/application/views/project/' . $project['slug'] . '/' . $slug)) {
                $id_user = $this->dataUser['id'];
                $data = [
                    'name' => $name,
                    'status' => $status,
                    'slug' => $slug,
                    'directory' => $slug,
                    'id_project' => $project['id'],
                    'id_user' => $id_user
                ];
                $this->project_model->createPage($data);
                redirect('project/' . $project['slug']);
            } else {
                setError('createPage', 'Não foi possível criar o diretório, você não possui privilégios suficiente.');
            }
        } else {
            setError('createPage', validation_errors());
        }

        $vars = [
            'title' => 'Nova página',
            'project' => $project
        ];
        $this->load->template('dev-project/form-page', $vars);
    }

    public function edit_page($project, $page) {
        $project = $this->project_model->getProject($project);
        $page = $this->project_model->getPage($page);
        if (!$project or ! $page) {
            redirect('project/' . $project['slug']);
        }

        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|integer');
        if ($project['name'] != $this->input->post('name')) {
            $this->form_validation->set_rules('name', 'Nome', 'required|is_unique[wd_pages.name]');
        }
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $slug = slug($name);
            $dir_project = getcwd() . '/application/views/project/' . $project['slug'] . '/';
            if ($slug != $project['slug'] && !is_dir($dir_project . $slug)) {
                if (!@rename($dir_project . $project['slug'], $dir_project . $slug)) {
                    setError('editPage', 'Não foi possível renomear a página, esse nome já existe ou você não possui privilégios suficiente.');
                    $name = $page['name'];
                }
            }

            $id_user = $this->dataUser['id'];
            $data = [
                'name' => $name,
                'status' => $status,
                'slug' => $slug,
                'id_project' => $project['id'],
                'id_user' => $id_user
            ];
            $this->project_model->createPage($data);
            redirect('project/' . $project['slug'] . '/' . $page['slug']);
        } else {
            setError('editPage', validation_errors());
        }

        $vars = [
            'title' => 'Editar página',
            'project' => $project,
            'page' => $page
        ];
        $this->load->template('project/form-page', $vars);
    }

    public function sections($project, $page) {
        if (!$this->dataUser['dev_mode']) {
            header('HTTP/1.1 403 Forbidden');
            die();
        }

        $project = $this->project_model->getProject($project);
        $page = $this->project_model->getPage($page);
        if (!$project or ! $page) {
            redirect('projects');
        }
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = 10;
        $this->form_validation->run();
        $sections = $this->project_model->searchSections($page['id'], $keyword, $limit, $perPage);
        $total_rows = $this->project_model->searchSectionsTotalRows($page['id'], $keyword);

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

    public function create_section($project, $page) {
        if (!$this->dataUser['dev_mode']) {
            header('HTTP/1.1 403 Forbidden');
            die();
        }

        $project = $this->project_model->getProject($project);
        $page = $this->project_model->getPage($page);
        if (!$project or ! $page) {
            redirect('projects');
        }

        $this->load->library('section');

        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        $this->form_validation->set_rules('directory', 'Diretório', 'trim|required|is_unique[wd_sections.directory]');
        $this->form_validation->set_rules('table', 'Tabela', 'trim|required|is_unique[wd_sections.table]');
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
            $required_field = $this->input->post('required_field');

            if (substr($table, 0, 3) == 'wd_') {
                setError('sufix', 'Nomes iniciados por "wd_" são reservados pelo sistema. ');
            } elseif (!$this->project_model->createTable($project['database'], $table)) {
                setError('create_table', 'Não foi possível criar a tabela ' . $table . ', a tabela existe ou você não tem permissões suficientes.');
            } elseif (!@mkdir(getcwd() . '/application/views/project/' . $project['directory'] . '/' . $page['directory'] . '/' . $directory, 0755)) {
                setError('create_dir', 'Não foi possível criar o diretório, já existe ou voce não tem permissões suficientes.');
                $this->project_model->removeTable($project['database'], $table);
            } else {
                $data = [
                    'database' => $project['database'],
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
                    'required_field' => $required_field
                ];

                if ($this->createFields($data)) {
                    redirect('project/' . $project['directory'] . '/' . $page['directory']);
                }
            }
        } else {
            setError(null, validation_errors());
        }

        add_js([
            'js/jquery.meio.js',
            'view/project/js/form-section.js'
        ]);

        $vars = [
            'title' => 'Nova seção',
            'project' => $project,
            'page' => $page,
            'inputs' => $this->section->inputs(),
            'types' => $this->section->types()
        ];
        $this->load->template('dev-project/form-section', $vars);
    }

    protected function createFields($data) {
        $fields = $this->filterFields($data);
        if ($fields) {
            if (count($fields)) {
                $config_xml = $this->section->create_config_xml($fields);
                var_dump($config_xml);
                $path_config_xml = 'application/views/project/' . $data['project_directory'] . '/' . $data['page_directory'] . '/' . $data['directory'] . '/config.xml';
                $fp = fopen($path_config_xml, 'w');
                fwrite($fp, $config_xml);
                fclose($fp);
                chmod($path_config_xml, 0640);

                $this->project_model->createColumns($data['database'], $data['table'], $fields);
            }
            $this->project_model->createSection($data);
            return true;
        } else {
            $this->project_model->removeTable($data['database'], $data['table']);
            forceRemoveDir('application/views/project/' . $data['project_directory'] . '/' . $data['page_directory'] . '/' . $data['directory']);
            return false;
        }
    }

    protected function filterFields($data) {
        $total = count($data['name_field']);
        if ($total) {
            $fields = array();
            for ($i = 0; $i < $total; $i++) {
                $name_field = $data['name_field'][$i];
                $input_field = $data['input_field'][$i];
                $list_reg_field = $data['list_reg_field'][$i];
                $column_field = $data['column_field'][$i];
                $type_field = $data['type_field'][$i];
                $limit_field = $data['limit_field'][$i];
                $required_field = $data['required_field'][$i];
                $verify = $this->verifyFields([
                    'column_field' => $column_field,
                    'name_field' => $name_field,
                    'type_field' => $type_field,
                    'input_field' => $input_field,
                    'fields' => $fields,
                ]);

                if (!$verify) {
                    return false;
                } elseif (!empty($name_field) && !empty($input_field) && !empty($column_field) && !empty($type_field)) {
                    if (empty($limit_field)) {
                        $this->load->library('section');
                        $search = search($this->section->types(), 'type', $type_field);
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
                        'required' => $required_field
                    ];
                }
            }
            return $fields;
        }
    }

    protected function verifyFields($data) {
        $column_field = $data['column_field'];
        $name_field = $data['name_field'];
        $type_field = $data['type_field'];
        $input_field = $data['input_field'];
        $fields = $data['fields'];

        if ($column_field == 'id') {
            setError('error_column', 'O campo "id" é criado automaticamente pelo sistema, informe outro nome.');
            return false;
        } elseif (count(search($fields, 'column', $column_field)) > 0) {
            setError('column_duplicate', 'O campo "' . $column_field . '" foi duplicado.');
            return false;
        } elseif (!empty($name_field) && (empty($input_field) or empty($column_field) or empty($type_field))) {
            setError('fields_required', 'Ao preencher o nome do campo, os campos Input, Coluna, Tipo se tornam obrigatórios.');
            return false;
        } else {
            return true;
        }
    }

}
