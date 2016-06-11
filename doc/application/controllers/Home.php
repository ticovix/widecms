<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function index() {
        $this->load->model('crud_model');
        $about = $this->crud_model->read('doc_about')->row();
        $reason = $this->crud_model->read('doc_reason')->result();
        $vars = array(
            'about' => $about,
            'reason' => $reason
        );
        $this->load->template('home/index', $vars);
    }

}

?>