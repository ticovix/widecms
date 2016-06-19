<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class MY_Loader extends CI_Loader{
    public $vars = array();
    public function setVars($vars = array()){
        if(!empty($vars)){
            $this->vars = array_merge($this->vars,$vars);
        }
    }
    public function getVars(){
        return $this->vars;
    }
    public function template($template, $vars = array(), $return = false){
        $this->vars = array_merge($this->vars,$vars);
        $content = $this->view('template/header', $this->vars, $return)->output->final_output;
        
        if(!is_array($template)){
            $content .= $this->view($template, $this->vars, $return)->output->final_output;
        }else{
            foreach($template as $temp){
                $content .= $this->view($temp, $this->vars, $return)->output->final_output;
            }
        }
        
        $content .= $this->view('template/footer', $this->vars, $return)->output->final_output;
        
        if($return){
            return $content;
        }
    }
}

