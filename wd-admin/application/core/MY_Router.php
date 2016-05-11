<?php

class MY_Router extends CI_Router {

    public function __construct($routing = NULL) {
        parent::__construct($routing);
    }

    protected function _set_default_controller() {
        parent::_set_default_controller();
        if ($this->uri->segment(1) == 'apps') {
            $class = $this->uri->segment(2);
            $method = 'index';
            $this->set_class($class);
            $this->set_method($method);
            $this->uri->rsegments = array(
                1 => $class,
                2 => $method
            );
        }
    }

}
