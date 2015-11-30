<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Projects extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('projects_model');
    }

    public function index() {
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = 10;
        $this->form_validation->run();
        $projects = $this->projects_model->search($this->dataUser['dev_mode'], $keyword, $limit, $perPage);
        $total_rows = $this->projects_model->searchTotalRows($this->dataUser['dev_mode'], $keyword);

        // paginação
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
            'title' => 'Projetos',
            'projects' => $projects,
            'pagination' => $pagination,
            'total' => $total_rows
        ];
        if ($this->dataUser['dev_mode']) {
            $this->load->template('dev-projects/index', $vars);
        } else {
            $this->load->template('projects/index', $vars);
        }
    }

    public function create() {
        if (!$this->dataUser['dev_mode']) {
            header('HTTP/1.1 403 Forbidden');
            die();
        }

        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        $this->form_validation->set_rules('database', 'Banco de dados', 'trim|required');
        $this->form_validation->set_rules('dir', 'Diretório', 'trim|required|callback_verifyDir');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $slug = $this->slug($name);
            $dir = slug($this->input->post('dir'));
            $main = $this->input->post('main');
            $database = $this->input->post('database');
            $status = $this->input->post('status');
            $create_db = false;
            if ($main) {
                $database = $this->db->database;
                $create_db = true;
            } else {
                $create_db = $this->projects_model->createDB($database);
            }

            if (!$create_db) {
                setError('errorDB', 'Não foi possível criar o banco de dados ' . $database . ', já existe ou você não tem permissões suficientes.');
            } else {
                $user = $this->dataUser;
                $data = [
                    'name' => $name,
                    'dir' => $dir,
                    'slug' => $slug,
                    'id_user' => $user['id'],
                    'main' => $main,
                    'database' => $database,
                    'status' => $status
                ];
                $create = $this->projects_model->create($data);
                if ($create) {
                    $this->extractProject($data);
                }
                redirect('projects');
            }
        } else {
            setError('errors_create', validation_errors());
        }

        add_js([
            'view/projects/js/form.js'
        ]);
        $vars = [
            'title' => 'Novo projeto',
            'name' => '',
            'directory' => '',
            'database' => '',
            'main' => '',
            'status' => ''
        ];
        $this->load->template('dev-projects/form', $vars);
    }

    public function edit($slug) {
        if (!$this->dataUser['dev_mode']) {
            header('HTTP/1.1 403 Forbidden');
            die();
        }

        $project = $this->projects_model->getProject($slug);
        if (!$project) {
            redirect('projects');
        }

        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $slug = $this->slug($name, $project['id']);
            $status = $this->input->post('status');
            $user = $this->dataUser;
            $data = [
                'name' => $name,
                'slug' => $slug,
                'status' => $status,
                'project' => $project['id']
            ];
            $this->projects_model->edit($data);
            redirect('projects');
        } else {
            setError('errors_edit', validation_errors());
        }

        add_js([
            'view/projects/js/form.js'
        ]);
        $vars = [
            'title' => 'Editar projeto',
            'name' => $project['name'],
            'directory' => $project['directory'],
            'database' => $project['database'],
            'status' => $project['status'],
            'main' => $project['main']
        ];
        $this->load->template('dev-projects/form', $vars);
    }

    public function verifyDir($dir) {
        $main = $this->input->post('main');
        if ($main) {
            $mainExists = $this->projects_model->mainExists();
            if ($mainExists && is_dir('../' . $mainExists['directory'])) {
                $this->form_validation->set_message('verifyDir', 'Já existe um diretório principal.');
                return false;
            }
        }

        if (is_dir('../' . $dir)) {
            $this->form_validation->set_message('verifyDir', 'Esse diretório já existe.');
            return false;
        } elseif (!$this->createDir($dir, $main)) {
            $this->form_validation->set_message('verifyDir', 'Não foi possível criar o diretório.');
            return false;
        } else {
            return true;
        }
    }

    protected function createDir($dir, $main) {
        $dir_project = '../' . $dir;
        $dir_admin = getcwd() . '/application/views/project/' . $dir;

        if (!@mkdir($dir_admin, 0755)) {
            return false;
        } elseif (!@mkdir($dir_project, 0755)) {
            return false;
        } else {
            return true;
        }

        return true;
    }

    protected function extractProject($data) {
        $dir = $data['dir'];
        $main = $data['main'];
        $dir_project = '../' . $dir;

        $file = getcwd() . '/application/files_project/project_default.zip';
        $to = $dir_project;

        $zip = new ZipArchive;
        $zip->open($file);
        if ($zip->extractTo($to)) {
            $this->configProject($data);
        }
        $zip->close();
    }

    protected function configProject($data) {
        $dir_project = $data['dir'];
        $main = $data['main'];

        $dir_system = '../' . DIR_ADMIN_DEFAULT . 'system';
        $dir_application = 'application';

        if ($main) {
            $dir_system = DIR_ADMIN_DEFAULT . 'system';
            $dir_application = $dir_project;
        }

        // Config index.php
        $path_index = '../' . $dir_project . '/index.php';
        $index = file_get_contents($path_index);
        $index = str_replace([
            '[[system_path]]',
            '[[application_folder]]'
                ], [
            $dir_system,
            $dir_application
                ], $index);
        file_put_contents($path_index, $index);

        // Config database 
        $path_config_db = '../' . $dir_project . '/application/config/database.php';

        $data_db = $this->db;
        $db = $data['database'];
        $hostname_db = $data_db->hostname;
        $username_db = $data_db->username;
        $pass_db = $data_db->password;

        $config_db = file_get_contents($path_config_db);
        $config_db = str_replace([
            '[[hostname]]',
            '[[username]]',
            '[[password]]',
            '[[database]]',
                ], [
            $hostname_db,
            $username_db,
            $pass_db,
            $db
                ], $config_db);
        file_put_contents($path_config_db, $config_db);


        if ($main) {
            rename($path_index, '../index.php');

            $dir_application_from = '../' . $dir_application . '/application';
            $dir_application_to = '../' . $dir_application . '/';
            $list_dir = dir($dir_application_from);
            while ($file = $list_dir->read()) {
                rename($dir_application_from . $file, $dir_application_to . $file);
            }
            rmdir($dir_application_from);
        }
    }

    protected function slug($name, $id = false) {
        $return = true;
        $slug = null;
        $i = 0;
        while ($return == true) {
            $slug = slug($name);
            if ($i > 0) {
                $slug .= $i;
            }
            $exe = $this->projects_model->verifySlug($slug, $id);
            ++$i;
            $return = ($exe);
        }
        return $slug;
    }

    public function delete($project) {
        if (!$this->dataUser['dev_mode']) {
            header('HTTP/1.1 403 Forbidden');
            die();
        }

        $project = $this->projects_model->getProject($project);
        if (!$project) {
            redirect('projects');
        } else {
            $this->form_validation->set_rules('project', 'Projeto', 'trim|required|integer');
            if ($this->form_validation->run()) {
                if ($project['id'] == $this->input->post('project')) {
                    $delete_all = $this->input->post('delete_all');
                    $delete_db = $this->input->post('delete_db');
                    $this->projects_model->delete($project['id']);

                    if ($project['database'] != $this->db->database && $delete_db) {
                        $this->projects_model->deleteDB($project['database']);
                    }
                    forceRemoveDir(getcwd() . '/application/views/project/' . $project['directory']);
                    if ($delete_all) {
                        forceRemoveDir('../' . $project['directory']);
                        redirect('projects');
                    }
                }
            }
            $vars = [
                'title' => 'Remover projeto',
                'project' => $project
            ];
            $this->load->template('dev-projects/delete', $vars);
        }
    }

}
