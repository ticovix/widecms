<?php

if (!defined('BASEPATH'))
    exit('Não é permitido o acesso direto ao arquivo.');

class Login extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // carrega a model login
        $this->load->model('users_model');
    }

    public function index() {
        // validação de formulário
        $this->form_validation->set_rules('login', 'E-mail', 'trim|required');
        $this->form_validation->set_rules('password', 'Senha', 'trim|callback_authAccount');
        // verifica se o formulário foi enviado e se houve algum erro
        $run = $this->form_validation->run();
        if ($run) {
            redirect();
        }
        $header = [
            'title'=>'Login'
        ];
        $this->load->view('login/inc/header', $header);
        $this->load->view('login/index');
        $this->load->view('login/inc/footer');
    }
    /*public function reset_pass($change=null){
        $header = [
            'title'=>'Redefinir senha'
        ];
        $data = ['reset_pass'=>false];
        $this->load->view('login/inc/header', $header);
        $this->load->view('login/request-pass', $data);
        $this->load->view('login/inc/footer');
    }*/

    public function authAccount($password) {
        $user = $this->input->post('login');

        // busca conta pelo usuário informado
        $account = $this->users_model->userExists($user);
        $pass = $account['password'];
        $id = $account['id'];
        
        if (!$account) {
            $this->form_validation->set_message('authAccount', 'E-mail ou senha incorreto.');
            return false;
        }
        
        // inicia helper PasswordHash
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        // verifica se a senha confere
        if ($PasswordHash->CheckPassword($password, $pass)) {
            // cria sessão
            $this->session->set_userdata([
                'id' => $id,
                'logged_in' => true
            ]);
            return true;
        } else {
            $this->form_validation->set_message('authAccount', 'E-mail ou senha incorreto.');
            return false;
        }
        
    }

}
