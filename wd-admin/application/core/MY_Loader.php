<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

    public $vars = array();

    private function view_template($path = '', $template, $vars = array(), $return = false) {
        $content = $this->view('template/header', $this->vars, $return)->output->final_output;

        if (!is_array($template)) {
            $content .= $this->view($path . $template, $this->vars, $return)->output->final_output;
        } else {
            foreach ($template as $temp) {
                $content .= $this->view($temp, $this->vars, $return)->output->final_output;
            }
        }

        $content .= $this->view('template/footer', $this->vars, $return)->output->final_output;
        return $content;
    }

    public function setVars($vars = array()) {
        if (!empty($vars)) {
            $this->vars = array_merge($this->vars, $vars);
        }
    }

    public function template($template, $vars = array(), $return = false) {
        $this->vars = array_merge($this->vars, $vars);
        $content = $this->view_template(null, $template, $vars, $return);
        if ($return) {
            return $content;
        }
    }

    public function module_view($template, $vars = array(), $return = false) {
        $get_project = get_project();
        $get_page = get_page();
        $get_section = get_section();
        $project = $get_project['slug'];
        $page = $get_page['slug'];
        $section = $get_section['slug'];
        $path = '../modules/' . $project . '/' . $page . '/' . $section . '/views/';
        return $this->view($path . $template, $vars, $return);
    }

    public function module_template($template, $vars = array(), $return = false) {
        $this->vars = array_merge($this->vars, $vars);
        $get_project = get_project();
        $get_page = get_page();
        $get_section = get_section();
        $project = $get_project['slug'];
        $page = $get_page['slug'];
        $section = $get_section['slug'];
        $path = '../modules/' . $project . '/' . $page . '/' . $section . '/views/';
        $content = $this->view_template($path, $template, $vars, $return);
        if ($return) {
            return $content;
        }
    }

    public function module_model($model) {
        $get_project = get_project();
        $get_page = get_page();
        $get_section = get_section();
        $project = $get_project['slug'];
        $page = $get_page['slug'];
        $section = $get_section['slug'];
        $path = '../modules/' . $project . '/' . $page . '/' . $section . '/models/';
        return $this->model($path . $model);
    }

}
