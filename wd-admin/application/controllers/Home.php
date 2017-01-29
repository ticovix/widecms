<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function index()
    {
        $widgets = $this->list_widgets();
        $this->data['widgets'] = $widgets;

        echo $this->load->render('home/index.twig', $this->data);
    }
    /*
     * Método para listar os widgets da página inicial
     */

    private function list_widgets()
    {
        $this->load->library('apps');

        return $this->apps->list_dashboard();
    }
    /*
     * Método para deslogar do painel
     */

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('id');
        session_destroy();

        redirect('login');
    }
}