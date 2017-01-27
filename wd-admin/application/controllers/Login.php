<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    private $max_attempts = 3;
    private $path_captcha = 'assets/captcha/';

    public function __construct()
    {
        parent::__construct();
        $this->load->app('users')->model('users_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->lang->load('cms/login/login');

        $captcha = false;
        $this->form_validation->set_rules('login', $this->lang->line('login_login_field'), 'trim|required');
        $this->form_validation->set_rules('password', $this->lang->line('login_password_field'), 'trim|callback_auth_account');
        $this->form_validation->set_rules('captcha', $this->lang->line('login_captcha_field'), 'callback_check_captcha');
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
            'title' => $this->lang->line('login_title'),
            'captcha' => $captcha,
            'lang' => $this->lang
        );
        echo $this->load->render('login/login.twig', $data);
    }

    private function protection_brute_force()
    {
        $attempts = $this->session->userdata('attempts') + 1;
        $this->session->set_userdata(array('attempts' => $attempts));
        if ($attempts > $this->max_attempts) {
            $this->load->helper('captcha');
            $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
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

    public function check_captcha($str)
    {
        $attempts = $this->session->userdata('attempts');
        // Maximo de 3 tentativas para exibir o captcha
        if ($attempts > $this->max_attempts) {
            $word = $this->session->userdata('captchaWord');
            if (strcmp(strtoupper($str), strtoupper($word)) == 0) {
                return true;
            } else {
                $this->form_validation->set_message('check_captcha', $this->lang->line('login_error_captcha_invalid'));
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
            $this->form_validation->set_message('auth_account', sprintf($this->lang->line('login_error_invalid_login'), base_url('login/recovery')));
            return false;
        }

        // Inicia helper PasswordHash
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        // Verifica se a senha confere
        if (!$PasswordHash->CheckPassword($password, $pass)) {
            $this->form_validation->set_message('auth_account', sprintf($this->lang->line('login_error_invalid_login'), base_url('login/recovery')));

            return false;
        }

        // Cria sessão
        $this->session->set_userdata([
            'id' => $id,
            'logged_in' => true
        ]);

        return true;
    }

    public function recovery()
    {
        $this->lang->load('cms/login/recovery');
        $this->form_validation->set_rules('captcha', $this->lang->line('recovery_captcha_field'), 'callback_check_captcha_recovery');
        $this->form_validation->set_rules('email', $this->lang->line('recovery_email_field'), 'trim|required|valid_email|callback_check_status_user');
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
                $title = $this->lang->line('recovery_email_title');
                $message = sprintf($this->lang->line('recovery_email_message'), base_url('login/redefine-pass?token=' . $token . '&login=' . $login));
                $body = '
                    <h1>' . $title . '</h1>
                    <p>' . $message . '</p>
                ';
                $email = $this->input->post('email');
                $this->load->library('email');
                $this->email->from($email, 'WIDECMS');
                $this->email->to($email, $user['name']);
                $this->email->subject($title);
                $this->email->message($body);
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
            'title' => $this->lang->line('recovery_title'),
            'captcha' => $captcha,
            'sended' => ($this->input->get('send') === 'true'),
            'lang' => $this->lang
        );
        echo $this->load->render('login/recovery.twig', $data);
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
            $this->form_validation->set_message('check_captcha_recovery', $this->lang->line('recovery_error_captcha_invalid'));
            return false;
        }

        return true;
    }

    public function check_status_user($email)
    {
        $this->user = $this->users_model->list_user_email($email);
        if ($this->user) {
            if ($this->user['status'] == '0') {
                $this->form_validation->set_message('check_status_user', $this->lang->line('recovery_error_user_blocked'));

                return false;
            }
        }

        return true;
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

        $this->form_validation->set_rules('pass', $this->lang->line('redefine_pass_field'), 'required|trim|min_length[3]');
        $this->form_validation->set_rules('re_pass', $this->lang->line('redefine_repass_field'), 'required|trim|matches[pass]');
        if ($this->form_validation->run()) {
            // Inicia helper PasswordHash
            $this->load->helper('passwordhash');
            $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $pass = $PasswordHash->HashPassword($this->input->post('pass'));
            $this->users_model->change_pass_user($pass, $login);
            redirect('login/redefine-pass?send=true');
        }

        $data = array(
            'title' => $this->lang->line('redefine_title'),
            'token' => $token,
            'login' => $login,
            'sended' => ($this->input->get('send') === 'true'),
            'lang' => $this->lang
        );
        echo $this->load->render('login/redefine-pass.twig', $data);
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