<?php

if (!defined('BASEPATH'))
    exit('Não é permitido o acesso direto ao arquivo.');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Carrega a model login
        $this->load->model('users_model');
    }

    /*
     * Método para criar template de login
     */

    public function index() {
        // Validação de formulário
        $this->form_validation->set_rules('login', 'Login', 'trim|required');
        $this->form_validation->set_rules('password', 'Senha', 'trim|callback_auth_account');
        // Verifica se o formulário foi enviado e se houve algum erro
        $run = $this->form_validation->run();
        if ($run) {
            redirect();
        }
        $header = [
            'title' => 'Login'
        ];
        add_css('view/login/css/style.css');
        $this->load->view('login/inc/header', $header);
        $this->load->view('login/index');
        $this->load->view('login/inc/footer');
    }

    /*
     * Método para autenticar login
     */

    public function auth_account($password) {
        $user = $this->input->post('login');

        // Busca conta pelo login informado
        $account = $this->users_model->user_exists($user);
        $pass = $account['password'];
        $id = $account['id'];
        if (!$account) {
            $this->form_validation->set_message('authAccount', 'Login ou senha incorreto.');
            return false;
        }

        // Inicia helper PasswordHash
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        // Verifica se a senha confere
        if ($PasswordHash->CheckPassword($password, $pass)) {
            // Cria sessão
            $this->session->set_userdata([
                'id' => $id,
                'logged_in' => true
            ]);
            return true;
        } else {
            $this->form_validation->set_message('authAccount', 'Login ou senha incorreto.');
            return false;
        }
    }

}
