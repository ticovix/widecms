<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class MY_Loader extends CI_Loader{
    
    public function template($template_name, $vars = array(), $return = false){
        $content = $this->view('template/header', $vars, $return)->output->final_output;
        $content .= $this->view($template_name, $vars, $return)->output->final_output;
        $content .= $this->view('template/footer', $vars, $return)->output->final_output;
        
        if($return){
            return $content;
        }
    }
}

