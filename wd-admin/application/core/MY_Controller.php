<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/navigation_model');
        $this->load->library('apps');
        $this->security();
        $this->allow_project_list();
        $default_values = [
            'PROFILE' => $this->data_user,
            'APPS' => $this->apps->list_apps()
        ];
        $this->load->setVars($default_values);
    }

    public function allow_project_list() {
        if ($this->uri->segment(1) == 'project') {
            $project = get_project();
            if ($this->data_user['dev_mode'] == 0 && $project && $project['status'] == 0) {
                redirect('projects');
            }
        }
    }

    public function security() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('id')) {
            if (!empty($this->data_user)) {
                return $this->data_user;
            } else {
                $this->load->model('/users_model');
                $id_user = $this->session->userdata('id');
                $this->data_user = $this->users_model->get_user($id_user);
                if ($this->data_user) {
                    return $this->data_user;
                }
            }
        }
        redirect('login');
    }

}
