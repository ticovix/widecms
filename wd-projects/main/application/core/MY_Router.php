<?php

class MY_Router extends CI_Router {

    public function _set_request($segments = array()) {
        // Fix only the first 2 segments
        for ($i = 0; $i < 2; ++$i) {
            if (isset($segments[$i])) {
                $segments[$i] = str_replace('-', '_', $segments[$i]);
            }
        }
        parent::_set_request($segments);
    }

}
