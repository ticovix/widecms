<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages extends MY_Controller
{
    private $config_path = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->app()->model('pages_model');
        $this->config_path = 'application/' . APP_PATH . 'projects/';
        $this->app_data = $this->apps->data_app();
    }

    public function index()
    {
        $this->lang->load_app('pages/pages');
        $project = get_project();
        $dev_mode = $this->user_data['dev_mode'];
        $pages = $this->form_search($project, $dev_mode);
        $total = $this->pages_model->total_pages($project['directory'], $this->user_data['dev_mode']);

        $this->data = array_merge($this->data, array(
            'title' => $project['name'],
            'name_app' => $this->app_data['name'],
            'pages' => $pages,
            'total' => $total,
            'project' => $project,
            'search' => $this->input->get('search')
        ));
        if ($dev_mode) {
            $template = $this->load->app()->render('dev-pages/index.twig', $this->data);
        } else {
            $this->data['pages'] = $this->include_sections($project, $pages);
            $template = $this->load->app()->render('projects/project.twig', $this->data);
        }

        echo $template;
    }

    private function form_search($project, $dev_mode)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('search', $this->lang->line(APP . '_field_search'), 'trim|required');
        $keyword = $this->input->get('search');
        $this->form_validation->run();
        return $this->pages_model->search($project['directory'], $dev_mode, $keyword);
    }

    private function include_sections($project, $pages)
    {
        $this->load->app()->model('sections_model');
        if (count($pages)) {
            foreach ($pages as $page) {
                $page['sections'] = $this->sections_model->list_sections($project['directory'], $page['directory']);
                $arr[] = $page;
            }

            return $arr;
        }
    }

    public function create()
    {
        func_only_dev();
        $this->lang->load_app('pages/form');
        $project = get_project();
        $this->form_create($project);
        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_add_page'),
            'name_app' => $this->app_data['name'],
            'project' => $project,
            'errors' => $this->error_reporting->get_errors()
        ));

        echo $this->load->app()->render('dev-pages/form.twig', $this->data);
    }

    public function form_create($project)
    {
        try {
            $this->load->library('form_validation');
            $this->load->library('error_reporting');
            $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'required');
            $this->form_validation->set_rules('status', $this->lang->line(APP . '_label_status'), 'required|integer');
            $run = $this->form_validation->run();
            if ($run) {
                $name = $this->input->post('name');
                $status = $this->input->post('status');
                $directory = slug($name);
                $project_dir = $this->config_path . $project['directory'] . '/';
                if (!is_writable($project_dir)) {
                    throw new Exception(printf($this->lang->line(APP . '_not_allowed_create'), $project_dir));
                }

                if (is_dir($project_dir . $directory)) {
                    throw new Exception($this->lang->line(APP . '_create_folder_exists'));
                }

                $user_id = $this->user_data['id'];
                $data = array(
                    'name' => $name,
                    'status' => $status,
                    'directory' => $directory,
                    'project_dir' => $project['directory'],
                    'user_id' => $user_id
                );
                mkdir($project_dir . $directory);
                $this->pages_model->create($data);

                app_redirect('project/' . $project['directory']);
            } else {
                $this->error_reporting->set_error(validation_errors());
            }
        } catch (Exception $e) {
            $this->error_reporting->set_error($e->getMessage());
        }
    }

    public function edit($slug_page)
    {
        func_only_dev();
        $this->lang->load_app('pages/form');
        $project = get_project();
        $page = $this->pages_model->get_page($project['directory'], $slug_page);
        if (!$project or ! $page) {
            app_redirect('project/' . $project['directory']);
        }

        $this->form_edit($project, $page);
        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_edit_page'),
            'name_app' => $this->app_data['name'],
            'project' => $project,
            'name' => $page['name'],
            'status' => $page['status']
        ));

        echo $this->load->app()->render('dev-pages/form.twig', $this->data);
    }

    public function form_edit($project, $page)
    {
        try {
            $this->load->library('form_validation');
            $this->load->library('error_reporting');
            $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'required');
            $this->form_validation->set_rules('status', $this->lang->line(APP . '_label_status'), 'required|integer');
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            if ($page['name'] != $name) {
                $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'required');
            }

            if ($this->form_validation->run()) {
                $page_dir = slug($name);
                $project_dir = $this->config_path . $project['directory'] . '/';
                if ($page_dir != $page['directory']) {
                    if (is_dir($project_dir . $page_dir)) {
                        throw new Exception($this->lang->line(APP . '_folder_exists'));
                    }

                    if (!is_writable($project_dir . $page['directory'])) {
                        throw new Exception($this->lang->line(APP . '_not_allowed_create'));
                    }

                    rename($project_dir . $page['directory'], $project_dir . $page_dir);
                }

                $data = array(
                    'name' => $name,
                    'status' => $status,
                    'directory' => $page_dir
                );
                $config = array_merge($page, $data);
                $this->pages_model->save($config, $project['directory'], $page_dir);

                app_redirect('project/' . $project['directory']);
            } else {
                $this->error_reporting->set_error(validation_errors());
            }
        } catch (Exception $e) {
            $this->error_reporting->set_error($e->getMessage());
        }
    }

    public function remove($page_dir)
    {
        func_only_dev();
        $this->lang->load_app('pages/remove');
        $project = get_project();
        $page = $this->pages_model->get_page($project['directory'], $page_dir);
        if (!$page or ! $project) {
            app_redirect();
        }

        $this->form_remove($page, $project);
        $this->data = array_merge($this->data, array(
            'title' => sprintf($this->lang->line(APP . '_title_remove_page'), $page['name']),
            'name_app' => $this->app_data['name'],
            'project' => $project,
            'page' => $page
        ));

        echo $this->load->app()->render('dev-pages/remove.twig', $this->data);
    }

    private function form_remove($page, $project)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', $this->lang->line(APP . '_label_password'), 'required|callback_verify_password');
        $this->form_validation->set_rules('page', $this->lang->line(APP . '_label_page'), 'trim|required');
        if ($this->form_validation->run()) {
            $project_dir = $project['directory'];
            $page_dir = $page['directory'];

            if ($page['directory'] == $this->input->post('page')) {
                $this->delete_tables($project, $page);
                forceRemoveDir($this->config_path . $project_dir . '/' . $page_dir);

                app_redirect('project/' . $project_dir);
            } else {
                app_redirect('project/' . $project_dir . '/' . $page_dir);
            }
        }
    }

    private function delete_tables($project, $page)
    {
        $this->load->model_app('sections_model');
        $project_dir = $project['directory'];
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