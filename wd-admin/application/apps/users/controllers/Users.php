<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Users extends MY_Controller {
    /*
     * Variável pública com o limite de usuários por página
     */

    public $limit = 10;

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    /*
     * Método com template de listagem de usuários
     */

    public function index() {

        $search = $this->form_search();
        $users = $search['users'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);
        $data = [
            'title' => 'Usuários',
            'user_logged' => $this->data_user,
            'users' => $users,
            'pagination' => $pagination,
            'total' => $total_rows
        ];
        add_js([
            'js/index.js'
        ]);
        $this->load->template('index', $data);
    }

    /*
     * Método para pesquisa de listagem de usuários
     */

    private function form_search() {
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = $this->limit;
        $this->form_validation->run();
        $users = $this->users_model->search($keyword, $limit, $perPage);
        $total_rows = $this->users_model->search_total_rows($keyword);
        return array(
            'users' => $users,
            'total_rows' => $total_rows
        );
    }

    /*
     * Método para gerar template de páginação para listagem de usuários
     */

    private function pagination($total_rows) {
        $this->load->library('pagination');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->limit;
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
        return $this->pagination->create_links();
    }

    /*
     * Método para criação de template para criar usuário 
     */

    public function create() {
        $this->form_create();
        add_js([
            'js/form.js'
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
            'about' => null
        ];
        $this->load->template('form', $vars);
    }

    /*
     * Método para criação de usuário
     */

    private function form_create() {
        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        $this->form_validation->set_rules('email', 'E-mail', 'trim|required|is_unique[wd_users.email]|valid_email');
        $this->form_validation->set_rules('login', 'Login', 'trim|required|is_unique[wd_users.login]|min_length[3]');
        $this->form_validation->set_rules('password', 'Senha', 'trim|required');

        if ($this->form_validation->run()) {
            $this->load->helper('passwordhash');
            $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $root = $this->input->post('root');
            $allow_dev = $this->input->post('allow_dev');
            if($this->data_user['root']!='1'){
                $root = 0;
                $allow_dev = 0;
            }
            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'login' => $this->input->post('login'),
                'password' => $PasswordHash->HashPassword($this->input->post('password')),
                'lastname' => $this->input->post('lastname'),
                'status' => $this->input->post('status'),
                'about' => $this->input->post('about'),
                'root' => $root,
                'allow_dev' => $allow_dev
            ];
            $this->users_model->create($data);
            redirect_app('users');
        }
    }

    /*
     * Método para criação de template de edição de usuário
     */

    public function edit($login) {
        $user = $this->users_model->get_user_edit($login);
        if (!$user) {
            redirect_app('users');
        }

        $this->form_edit($user);

        add_js([
            'js/form.js'
        ]);
        $vars = [
            'title' => 'Editar usuário',
            'name' => $user['name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'login' => $user['login'],
            'status' => $user['status'],
            'root' => $user['root'],
            'allow_dev' => $user['allow_dev'],
            'about' => $user['about'],
        ];
        $this->load->template('form', $vars);
    }

    /*
     * Método para edição de usuário
     */

    private function form_edit($user) {
        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        if($this->input->post('email')!=$user['email']){
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|is_unique[wd_users.email]|valid_email');
        }
        if($this->input->post('login')!=$user['login']){
            $this->form_validation->set_rules('login', 'Login', 'trim|required|is_unique[wd_users.login]|min_length[3]');
        }
        if ($this->form_validation->run()) {
            if($user['root']){
                $root = $this->input->post('root');
                $allow_dev = $this->input->post('allow_dev');
            }else{
                $root = $user['root'];
                $allow_dev = $user['allow_dev'];
            }
            $data = [
                'login_old' => $user['login'],
                'name' => $this->input->post('name'),
                'lastname' => $this->input->post('lastname'),
                'status' => $this->input->post('status'),
                'email' => $this->input->post('email'),
                'login' => $this->input->post('login'),
                'about' => $this->input->post('about'),
                'root' => $root,
                'allow_dev' => $allow_dev,
            ];
            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->load->helper('passwordhash');
                $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $data['password'] = $PasswordHash->HashPassword($password);
            } else {
                $data['password'] = $user['password'];
            }
            $this->users_model->update($data);
            die();
            redirect_app('users');
        }
    }

    /*
     * Método para deletar usuário
     */

    public function delete() {
        $del = $this->input->post('del');
        if ($del > 1) {
            $this->users_model->delete($del);
        }
        redirect_app('users');
    }

    /*
     * Método para ativar e desativar o modo desenvolvedor, acionado por js
     */

    public function dev_mode() {
        $this->form_validation->set_rules('dev', 'Dev', 'required');
        if ($this->form_validation->run()) {
            $dev = $this->input->post('dev');
            $this->users_model->change_mode(['dev' => $dev, 'id_user' => $this->data_user['id']]);
        }
    }

}
