<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_account extends MY_Controller
{

    public function index()
    {
        $this->lang->load_app(APP);
        $data = $this->apps->data_app();
        $user = $this->user_data;
        if (!check_method('edit')) {
            redirect('apps/users/profile/' . $user['login']);
        }

        $this->form_edit();
        load_gallery();
        $this->include_components
                ->app_js(array('js/upload.js', 'js/form.js'))
                ->app_css('css/style.css');

        $this->data = array_merge($this->data, array(
            'title' => $data['name'],
            'name' => $user['name'],
            'login' => $user['login'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'image' => $user['image'],
            'about' => $user['about'],
        ));

        echo $this->load->app()->render('index.twig', $this->data);
    }

    private function form_edit()
    {
        $this->load->library('form_validation');
        $user = $this->user_data;
        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        if ($this->input->post('email') != $user['email']) {
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|is_unique[wd_users.email]|valid_email');
        }

        if ($this->input->post('login') != $user['login']) {
            $this->form_validation->set_rules('login', 'Login', 'trim|required|is_unique[wd_users.login]|min_length[3]');
        }

        if ($this->form_validation->run()) {
            $data = [
                'id' => $user['id'],
                'name' => $this->input->post('name'),
                'lastname' => $this->input->post('lastname'),
                'image' => $this->input->post('image'),
                'about' => $this->input->post('about'),
                'login' => $this->input->post('login'),
                'email' => $this->input->post('email'),
                'password' => $user['password']
            ];
            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->load->helper('passwordhash');
                $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $data['password'] = $PasswordHash->HashPassword($password);
            }

            $this->load->app()->model('user_model');
            $this->user_model->update($data);
            add_history($this->lang->line(APP . '_update_profile'));

            app_redirect();
        }
    }
}