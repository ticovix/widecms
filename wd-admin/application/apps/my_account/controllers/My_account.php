<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_account extends MY_Controller {
    /*
     * Método para criação de template com formulário para editar usuário
     */

    public function index() {
        $user = $this->data_user;
        $this->form_edit();

        add_js([
            '../../../../assets/plugins/dropzone/js/dropzone.js',
            '../../../../assets/plugins/fancybox/js/jquery.fancybox.pack.js',
            '../../../../assets/plugins/fancybox/js/jquery.fancybox-buttons.js',
            '../../../../assets/plugins/embeddedjs/ejs.js',
            'js/load_gallery.js',
            'js/upload.js',
            'js/form.js'
        ]);
        add_css([
            '../../../../assets/plugins/fancybox/css/jquery.fancybox.css',
            '../../../../assets/plugins/fancybox/css/jquery.fancybox-buttons.css',
            '../../../../assets/plugins/dropzone/css/dropzone.css',
            'css/style.css'
        ]);
        

        $vars = array(
            'title' => 'Minha conta',
            'name' => $user['name'],
            'login' => $user['login'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'image' => $user['image'],
            'about' => $user['about'],
        );
        $this->load->template('index', $vars);
    }

    /*
     * Método para edição da conta
     */

    private function form_edit() {
        $user = $this->data_user;
        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        if($this->input->post('email')!=$user['email']){
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|is_unique[wd_users.email]|valid_email');
        }
        if($this->input->post('login')!=$user['login']){
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
                'email' => $this->input->post('email')
            ];
            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->load->helper('passwordhash');
                $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $data['password'] = $PasswordHash->HashPassword($password);
            } else {
                $data['password'] = $user['password'];
            }
            $this->load->model('user_model');
            $this->user_model->update($data);
            redirect(current_url());
        }
    }

}
