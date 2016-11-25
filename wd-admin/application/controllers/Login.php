<?php

if (!defined('BASEPATH'))
    exit('Não é permitido o acesso direto ao arquivo.');

class Login extends CI_Controller
{
    private $max_attempts = 3;
    private $path_captcha = 'assets/captcha/';

    public function __construct()
    {
        parent::__construct();
        // Carrega a model login
        $this->load->model_app('users_model', 'users');
    }
    /*
     * Método para criar template de login
     */

    public function index()
    {
        $this->lang->load('cms/login/login');

        // Validação de formulário
        $captcha = false;
        $this->form_validation->set_rules('login', 'Login', 'trim|required');
        $this->form_validation->set_rules('password', 'Senha', 'trim|callback_auth_account');
        $this->form_validation->set_rules('captcha', 'Captcha', 'callback_check_captcha');
        // Verifica se o formulário foi enviado e se houve algum erro
        $run = $this->form_validation->run();
        if ($run) {
            $this->session->set_userdata('attempts', 0);
            $url = $this->session->redirect;

            redirect($url);
        }
        $validation_errors = validation_errors();
        if (!empty($validation_errors)) {
            $captcha = $this->protection_brute_force();
        }

        $data = array(
            'title' => 'Login',
            'captcha' => $captcha
        );
        $this->include_components->main_css('view/login/css/style.css');
        $this->load->view('login/inc/header', $data);
        $this->load->view('login/index', $data);
        $this->load->view('login/inc/footer');
    }
    /*
     * Método para verificar e exibir captcha caso seja detectado força bruta
     */

    private function protection_brute_force()
    {
        $attempts = $this->session->userdata('attempts') + 1;
        $this->session->set_userdata(array('attempts' => $attempts));
        if ($attempts > $this->max_attempts) {
            $this->load->helper('captcha');
            // numeric random number for captcha
            $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            // setting up captcha config
            $vals = array(
                'word' => $random_number,
                'img_path' => $this->path_captcha,
                'img_url' => base_url($this->path_captcha),
                'img_width' => 350,
                'img_height' => 55,
                'expiration' => 900
            );
            $captcha = create_captcha($vals);
            $this->session->set_userdata('captchaWord', $captcha['word']);

            return $captcha;
        } else {
            return false;
        }
    }
    /*
     * Método para checar captcha caso seja detectado acesso por força bruta
     */

    public function check_captcha($str)
    {
        $attempts = $this->session->userdata('attempts');
        // Maximo de 3 tentativas para exibir o captcha
        if ($attempts > $this->max_attempts) {
            $word = $this->session->userdata('captchaWord');
            if (strcmp(strtoupper($str), strtoupper($word)) == 0) {
                return true;
            } else {
                $this->form_validation->set_message('check_captcha', 'Digite corretamente o que vê na imagem.');
                return false;
            }
        }

        return true;
    }
    /*
     * Método para autenticar login
     */

    public function auth_account($password)
    {
        $user = $this->input->post('login');

        // Busca conta pelo login informado
        $account = $this->users_model->user_exists($user);
        $pass = $account['password'];
        $id = $account['id'];
        if (!$account) {
            $this->form_validation->set_message('auth_account', 'Login ou senha incorreto, caso tenha esquecido sua senha <a href="' . base_url('login/recovery') . '">clique aqui</a>');
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
            $this->form_validation->set_message('auth_account', 'Login ou senha incorreto, caso tenha esquecido sua senha <a href="' . base_url('login/recovery') . '">clique aqui</a>');

            return false;
        }
    }

    public function recovery()
    {
        $this->lang->load('cms/login/recovery');
        $this->form_validation->set_rules('captcha', 'Captcha', 'callback_check_captcha_recovery');
        $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email|callback_check_status_user');
        if ($this->form_validation->run()) {
            $user = $this->user;
            if ($user) {
                // Inicia helper PasswordHash
                $this->load->helper('passwordhash');
                $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $token_val = md5(uniqid(rand(), true));
                $token = $PasswordHash->HashPassword($token_val);
                $this->users_model->change_recovery_token($token_val, $user['id']);
                $login = $user['login'];
                $this->load->library('email');
                $email = $this->input->post('email');
                $message = '
                    <h1>Redefinição de senha</h1>
                    <p>Você solicitou a redefinição de senha? Se sim clique nesse <a href="' . base_url('login/redefine-pass?token=' . $token . '&login=' . $login) . '">link</a> para redefinir a senha, caso não tenha solicitado, ignore esse e-mail.</p>
                ';
                $this->email->from($email, 'Wide CMS');
                $this->email->to($email);
                $this->email->subject('Email para redefinição de senha');
                $this->email->message($message);
                $this->email->send();
            }

            redirect('login/recovery?send=true');
        }

        $this->load->helper('captcha');
        // numeric random number for captcha
        $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        // setting up captcha config
        $vals = array(
            'word' => $random_number,
            'img_path' => $this->path_captcha,
            'img_url' => base_url($this->path_captcha),
            'img_width' => 350,
            'img_height' => 55,
            'expiration' => 900
        );
        $captcha = create_captcha($vals);
        $this->session->set_userdata('captchaWordRecovery', $captcha['word']);
        $data = array(
            'title' => 'Recuperar senha',
            'captcha' => $captcha
        );
        add_css('view/login/css/style.css');
        $this->load->view('login/inc/header', $data);
        $this->load->view('login/recovery', $data);
        $this->load->view('login/inc/footer');
    }
    /*
     * Método para checar captcha da página de recuperação de senha
     */

    public function check_captcha_recovery($str)
    {
        $word = $this->session->userdata('captchaWordRecovery');
        if (strcmp(strtoupper($str), strtoupper($word)) == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_captcha_recovery', 'Digite corretamente o que vê na imagem.');
            return false;
        }

        return true;
    }

    public function check_status_user($email)
    {
        $this->user = $this->users_model->list_user_email($email);
        if ($this->user) {
            if ($this->user['status'] == '0') {
                $this->form_validation->set_message('check_status_user', 'Conta bloqueada, não é possível recuperar senha, entre em contato com o administrador.');

                return false;
            }
        }
    }

    public function redefine_pass()
    {
        $this->lang->load('cms/login/redefinepass');
        $token = $this->input->get('token');
        $login = $this->input->get('login');
        $send = $this->input->get('send');
        if ($token or $login) {
            $this->verify_token($token, $login);
        }

        if (!$token && !$login && !$send) {
            redirect('login');
        }

        $this->form_validation->set_rules('pass', 'Senha', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('re_pass', 'Repetir senha', 'required|trim|matches[pass]');
        if ($this->form_validation->run()) {
            // Inicia helper PasswordHash
            $this->load->helper('passwordhash');
            $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $pass = $PasswordHash->HashPassword($this->input->post('pass'));
            $this->users_model->change_pass_user($pass, $login);
            redirect('login/redefine-pass?send=true');
        }

        $data = array(
            'title' => 'Redefinir senha',
            'token' => $token,
            'login' => $login
        );
        add_css('view/login/css/style.css');
        $this->load->view('login/inc/header', $data);
        $this->load->view('login/redefine-pass', $data);
        $this->load->view('login/inc/footer');
    }

    private function verify_token($token, $login)
    {
        $user = $this->users_model->user_recovery($login);
        if (!$user) {
            redirect('login');
        }

        $token_val = $user['recovery_token'];
        // Inicia helper PasswordHash
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        if (!$PasswordHash->CheckPassword($token_val, $token)) {
            redirect('login');
        }
    }
}