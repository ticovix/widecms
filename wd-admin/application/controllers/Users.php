<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function index() {
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = 10;
        $this->form_validation->run();
        $users = $this->users_model->search($keyword, $limit, $perPage);
        $total_rows = $this->users_model->searchTotalRows($keyword);

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

        $data = [
            'title' => 'Usuários',
            'user_logged' => $this->data_user,
            'users' => $users,
            'pagination' => $pagination,
            'total' => $total_rows
        ];
        add_js([
            'view/users/js/index.js'
        ]);
        $this->load->template('users/index', $data);
    }

    public function create() {
        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        $this->form_validation->set_rules('email', 'E-mail', 'trim|required|is_unique[wd_users.email]');
        $this->form_validation->set_rules('login', 'Login', 'trim|required|is_unique[wd_users.login]');
        $this->form_validation->set_rules('password', 'Senha', 'trim|required');

        if ($this->form_validation->run()) {
            $this->load->helper('passwordhash');
            $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'login' => $this->input->post('login'),
                'password' => $PasswordHash->HashPassword($this->input->post('password')),
                'lastname' => $this->input->post('lastname'),
                'status' => $this->input->post('status'),
                'root' => $this->input->post('root'),
                'allow_dev' => $this->input->post('allow_dev')
            ];
            $this->users_model->create($data);
            redirect('users');
        }

        add_js([
            'view/users/js/form.js'
        ]);
        $vars = [
            'title' => 'Novo usuário',
            'user_logged' => $this->data_user,
            'name' => null,
            'last_name' => null,
            'email' => null,
            'login' => null,
            'status' => null,
            'root' => null,
            'allow_dev' => null,
        ];
        $this->load->template('users/form', $vars);
    }

    public function edit($login) {
        $user = $this->users_model->getUserEdit($login);
        if (!$user) {
            redirect('users');
        }

        $this->form_validation->set_rules('name', 'Nome', 'trim|required');

        if ($this->form_validation->run()) {
            $data = [
                'login' => $user['login'],
                'name' => $this->input->post('name'),
                'lastname' => $this->input->post('lastname'),
                'status' => $this->input->post('status'),
                'root' => $this->input->post('root'),
                'allow_dev' => $this->input->post('allow_dev')
            ];
            if (!empty($this->input->post('password'))) {
                $this->load->helper('passwordhash');
                $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $data['password'] = $PasswordHash->HashPassword($this->input->post('password'));
            }else{
                $data['password'] = $user['password'];
            }
            $this->users_model->update($data);
            redirect('users');
        }

        add_js([
            'view/users/js/form.js'
        ]);
        $vars = [
            'title' => 'Editar usuário',
            'user_logged' => $this->data_user,
            'name' => $user['name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'login' => $user['login'],
            'status' => $user['status'],
            'root' => $user['root'],
            'allow_dev' => $user['allow_dev'],
        ];
        $this->load->template('users/form', $vars);
    }
    
    public function delete() {
        $del = $this->input->post('del');
        $this->users_model->delete($del);
        redirect('users');
    }
    
    public function dev_mode(){
        $this->form_validation->set_rules('dev', 'Dev', 'required');
        if($this->form_validation->run()){
            $dev = $this->input->post('dev');
            $this->users_model->change_mode(['dev'=>$dev,'id_user'=>$this->data_user['id']]);
        }
    }

}
