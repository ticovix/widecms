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
        $this->data = array(
            'user_data' => $this->user_data,
            'list_apps' => $this->apps->list_apps(),
            'lang' => $this->lang,
            'include_components' => $this->include_components,
            'uri' => $this->uri,
            'APP' => (defined('APP') ? APP : ''),
            'APP_PATH' => (defined('APP_PATH') ? APP_PATH : ''),
            'APP_ASSETS' => (defined('APP_ASSETS') ? APP_ASSETS : '')
        );
    }

    private function allow_project_list()
    {
        if ($this->uri->segment(3) == 'project') {
            $project = get_project();
            if ($this->user_data['dev_mode'] == 0 && $project && $project['status'] == 0) {
                redirect('projects');
            }
        }
    }

    private function security()
    {
        if ($this->session->userdata('logged_in') && $this->session->userdata('id')) {
            if (!empty($this->user_data)) {
                return $this->user_data;
            } else {
                $this->load->app('users')->model('users_model');
                $id_user = $this->session->userdata('id');
                $this->user_data = $this->users_model->get_user($id_user);
                if ($this->user_data) {
                    return $this->user_data;
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