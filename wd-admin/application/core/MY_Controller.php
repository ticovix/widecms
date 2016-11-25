<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('cms/panel/index');
        $this->load->library('apps');
        $this->security();
        $this->allow_project_list();
        $this->check_permissions();
        $default_values = array(
            'PROFILE' => $this->data_user,
            'APPS' => $this->apps->list_apps()
        );

        $this->load->setVars($default_values);
    }

    private function allow_project_list()
    {
        if ($this->uri->segment(3) == 'project') {
            $project = get_project();
            if ($this->data_user['dev_mode'] == 0 && $project && $project['status'] == 0) {
                redirect('projects');
            }
        }
    }

    private function security()
    {
        if ($this->session->userdata('logged_in') && $this->session->userdata('id')) {
            if (!empty($this->data_user)) {
                return $this->data_user;
            } else {
                $this->load->model_app('users_model', 'users');
                $id_user = $this->session->userdata('id');
                $this->data_user = $this->users_model->get_user($id_user);
                if ($this->data_user) {
                    return $this->data_user;
                }
            }
        }

        $this->session->set_userdata('redirect', current_url());

        redirect('login');
    }

    private function check_permissions()
    {
        $page = $this->uri->segment(1);
        $app = $this->uri->segment(2);
        $url = implode('/', array_slice($this->uri->segments, 2));
        if ($page == 'apps' && (!check_app($app) || !empty($url) && !check_url($app, $url))) {
            redirect();
        }
    }
}