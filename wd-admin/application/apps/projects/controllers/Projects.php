<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Projects extends MY_Controller
{
    private $config_path = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->app()->model('projects_model');
        $this->config_path = 'application/' . APP_PATH . 'projects/';
        $this->app_data = $this->apps->data_app();
    }

    public function index()
    {
        $this->lang->load_app('projects/projects');
        $projects = $this->form_search();
        $total = $this->projects_model->total_projects($this->user_data['dev_mode']);

        $this->data = array_merge($this->data, array(
            'title' => $this->app_data['name'],
            'projects' => $projects,
            'total' => $total,
            'search' => $this->input->get('search')
        ));

        if ($this->user_data['dev_mode']) {
            $template = $this->load->app()->render('dev-projects/index.twig', $this->data);
        } else {
            $template = $this->load->app()->render('projects/index.twig', $this->data);
        }

        echo $template;
    }

    private function form_search()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('search', $this->lang->line(APP . '_field_search'), 'trim|required');
        $this->form_validation->run();
        $dev_mode = $this->user_data['dev_mode'];
        $keyword = $this->input->get('search');

        return $this->projects_model->search($dev_mode, $keyword);
    }

    public function create()
    {
        func_only_dev();
        $this->lang->load_app('projects/form');
        $this->form_create();

        $this->include_components->app_js('js/form.js');
        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_add_project'),
            'name_app' => $this->app_data['name'],
            'errors' => $this->error_reporting->get_errors()
        ));

        echo $this->load->app()->render('dev-projects/form.twig', $this->data);
    }

    private function form_create()
    {
        $this->load->library('form_validation');
        $this->load->library('error_reporting');
        $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'trim|required');
        $this->form_validation->set_rules('preffix', $this->lang->line(APP . '_label_preffix'), 'trim');
        $this->form_validation->set_rules('dir', $this->lang->line(APP . '_label_directory'), 'trim|required|callback_verify_dir');
        if (!$this->input->post('main')) {
            $this->form_validation->set_rules('preffix', 'Prefixo', 'required|max_length[6]');
        }
        try {
            $run = $this->form_validation->run();
            if ($run) {
                $name = $this->input->post('name');
                $dir = slug($this->input->post('dir'));
                $main = $this->input->post('main');
                $extract_ci = $this->input->post('extract_ci');
                $preffix = $this->input->post('preffix');

                if (!empty($preffix)) {
                    $preffix = str_replace('_', '', $preffix) . '_';
                }

                $status = $this->input->post('status');

                $user = $this->user_data;
                $data = array(
                    'name' => $name,
                    'dir' => $dir,
                    'id_user' => $user['id'],
                    'preffix' => $preffix,
                    'main' => $main,
                    'extract_ci' => $extract_ci,
                    'status' => $status
                );
                $this->createDir($data);
                $this->projects_model->create($data);

                if ($extract_ci) {
                    $this->extractProject($data);
                }

                app_redirect();
            }

            $validation_errors = validation_errors();
            if (!empty($validation_errors)) {
                $this->error_reporting->set_error($validation_errors);
            }
        } catch (Exception $e) {
            $this->error_reporting->set_error($e->getMessage());
        }
    }

    public function edit($slug_project)
    {
        func_only_dev();
        $this->lang->load_app('projects/form');
        $project = $this->projects_model->get_project($slug_project);
        if (!$project) {
            app_redirect();
        }

        $this->form_edit($project);
        $preffix = $project['preffix'];
        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_edit_project'),
            'name_app' => $this->app_data['name'],
            'name' => $project['name'],
            'directory' => $project['directory'],
            'preffix' => $preffix,
            'status' => $project['status'],
            'main' => $project['main_project'],
            'errors' => $this->error_reporting->get_errors()
        ));

        echo $this->load->app()->render('dev-projects/form.twig', $this->data);
    }

    private function form_edit($project)
    {
        $this->load->library('form_validation');
        $this->load->library('error_reporting');
        $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'trim|required');

        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $data = array(
                'name' => $name,
                'status' => $status
            );
            $config = array_merge($project, $data);
            $this->projects_model->save($config, $project['directory']);

            app_redirect();
        } else {
            $this->error_reporting->set_error(validation_errors());
        }
    }

    public function verify_dir($dir)
    {
        $main = $this->input->post('main');
        $extract = $this->input->post('extract_ci');
        $dir_project = '../';
        $dir_admin = $this->config_path;
        if ($main) {
            $mainExists = $this->projects_model->main_exists();
            if ($mainExists && is_dir('../' . $mainExists['directory'])) {
                $this->form_validation->set_message('verify_dir', $this->lang->line(APP . '_main_project_exists'));

                return false;
            }
        }

        if ((is_dir($dir_project . $dir) && $extract == '1') or is_dir($dir_admin . $dir)) {
            $this->form_validation->set_message('verify_dir', $this->lang->line(APP . '_folder_exists'));

            return false;
        } elseif (!is_writable($dir_project)) {
            $this->form_validation->set_message('verify_dir', sprintf($this->lang->line(APP . '_only_read_permission'), $dir_project));

            return false;
        } elseif (!is_writable($dir_admin)) {
            $this->form_validation->set_message('verify_dir', sprintf($this->lang->line(APP . '_only_read_permission'), $dir_admin));

            return false;
        }

        return true;
    }

    protected function createDir($data)
    {
        $dir = $data['dir'];
        $extract_ci = $data['extract_ci'];
        $dir_project = '../';
        $dir_admin = $this->config_path;
        if (is_writable($dir_admin)) {
            mkdir($dir_admin . $dir, 0755);
            if (is_writable($dir_project) && $extract_ci) {
                mkdir($dir_project . $dir, 0755);
            }

            return true;
        }
    }

    protected function extractProject($data)
    {
        $dir = $data['dir'];
        $dir_project = '../' . $dir;
        $file = getcwd() . '/application/' . APP_PATH . 'files_project/project_default.zip';
        $to = $dir_project;

        $zip = new ZipArchive;
        $zip->open($file);
        if ($zip->extractTo($to)) {
            $this->configProject($data);
        }

        $zip->close();
    }

    protected function configProject($data)
    {
        $dir_project = $data['dir'];
        $main = $data['main'];
        $dir_system = '../' . DIR_ADMIN_DEFAULT . 'system';
        $dir_application = 'application';

        if ($main) {
            // As configurações mudam, caso seja o projeto principal
            $dir_system = DIR_ADMIN_DEFAULT . 'system';
            $dir_application = $dir_project . '/application';
        }

        // Config /index.php
        $path_index = '../' . $dir_project . '/index.php';
        $file_index = file_get_contents($path_index);
        $index = str_replace(array(
            '[[system_path]]',
            '[[application_folder]]'
                ), array(
            $dir_system,
            $dir_application
                ), $file_index);
        file_put_contents($path_index, $index);
        // End config
        // Config application/config/config.php
        $path_config = '../' . $dir_project . '/application/config/config.php';
        $file_config = file_get_contents($path_config);

        $encryption_key = md5(uniqid(rand(), true));
        $config = str_replace(array(
            '[[encryption_key]]'
                ), array(
            $encryption_key
                ), $file_config);
        file_put_contents($path_config, $config);
        // End config
        if ($main) {
            rename($path_index, '../index.php');
        }
    }

    public function remove($slug_project)
    {
        func_only_dev();
        $this->lang->load_app('projects/remove');
        $project = $this->projects_model->get_project($slug_project);
        if (!$project) {
            app_redirect();
        }

        $this->form_remove($project);
        $this->data = array_merge($this->data, array(
            'title' => sprintf($this->lang->line(APP . '_title_remove_project'), $project['name']),
            'name_app' => $this->app_data['name'],
            'project' => $project
        ));

        echo $this->load->app()->render('dev-projects/remove.twig', $this->data);
    }

    private function form_remove($project)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', $this->lang->line(APP . '_field_password'), 'required|callback_verify_password');
        $this->form_validation->set_rules('project', $this->lang->line(APP . '_field_project'), 'trim|required');
        if ($this->form_validation->run()) {
            if ($project['directory'] == $this->input->post('project')) {
                $delete_all = $this->input->post('delete_all');
                $dir_project = $project['directory'];
                $main = $project['main_project'];

                $this->delete_tables($project);

                // Remove todos arquivos de views
                $dir_views_project = $this->config_path . $dir_project;
                if (is_dir($dir_views_project)) {
                    forceRemoveDir($dir_views_project);
                }

                if ($delete_all) {
                    // Remove todos os arquivos do projeto no diretório inicial
                    if (is_dir('../' . $dir_project)) {
                        forceRemoveDir('../' . $dir_project);
                    }

                    if ($main) {
                        unlink('../index.php');
                    }
                }

                app_redirect();
            }
        }
    }

    private function delete_tables($project)
    {
        $this->load->model_app('pages_model');
        $this->load->model_app('sections_model');
        $project_dir = $project['directory'];
        $pages = $this->pages_model->list_pages($project_dir);
        if ($pages) {
            foreach ($pages as $page) {
                $page_dir = $page['directory'];
                $sections = $this->sections_model->list_sections($project_dir, $page_dir);
                if ($sections) {
                    foreach ($sections as $section) {
                        $table = $section['table'];
                        $check = $this->sections_model->check_table_exists($table);
                        if ($check) {
                            $this->sections_model->remove_table($table);
                        }
                    }
                }
            }
        }
    }

    public function verify_password($v_pass)
    {
        $pass_user = $this->user_data['password'];
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