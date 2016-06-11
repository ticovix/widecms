<?php

class MY_Controller extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('crud_model');
        $topics = $this->crud_model->read('doc_topics')->result();
        $this->load->setVars(array(
            'topics' => $topics
        ));
    }
}
