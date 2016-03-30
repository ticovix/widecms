<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('navigation_model');
        $this->security();
        $this->listNav = $this->navigation_model->listNav();
        $this->allow_project_list();
        $default_values = [
            'profile' => $this->data_user,
            'navigation' => $this->listNav
        ];
        $this->load->setVars($default_values);
    }

    public function allow_project_list() {
        if ($this->uri->segment(1) == 'project') {
            $project = $this->get_project();
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
                $this->load->model('users_model');
                $id_user = $this->session->userdata('id');
                $this->data_user = $this->users_model->getUser($id_user);
                if ($this->data_user) {
                    return $this->data_user;
                }
            }
        }
        redirect('login');
    }

    public function get_project() {
        if (empty($this->project)) {
            $slug_project = $this->uri->segment(2);
            $this->load->model('projects_model');
            return $this->project = $this->projects_model->getProject($slug_project);
        } else {
            return $this->project;
        }
    }

    public function func_only_dev() {
        if (!$this->data_user['dev_mode']) {
            header('HTTP/1.1 403 Forbidden');
            die();
        }
    }

}
