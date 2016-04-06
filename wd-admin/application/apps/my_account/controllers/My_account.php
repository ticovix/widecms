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
            APP_PATH.'js/form.js'
        ]);

        $vars = array(
            'title' => 'Minha conta',
            'name' => $user['name'],
            'login' => $user['login'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
        );
        $this->load->template('index', $vars);
    }

    /*
     * Método para edição da conta
     */

    private function form_edit() {
        $user = $this->data_user;
        $this->form_validation->set_rules('name', 'Nome', 'trim|required');
        if ($this->form_validation->run()) {
            $data = [
                'login' => $user['login'],
                'name' => $this->input->post('name'),
                'lastname' => $this->input->post('lastname'),
                'status' => $user['status'],
                'allow_dev' => $user['allow_dev'],
                'root' => $user['root']
            ];
            if (!empty($this->input->post('password'))) {
                $this->load->helper('passwordhash');
                $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $data['password'] = $PasswordHash->HashPassword($this->input->post('password'));
            } else {
                $data['password'] = $user['password'];
            }
            $this->users_model->update($data);
            redirect(current_url());
        }
    }

}
