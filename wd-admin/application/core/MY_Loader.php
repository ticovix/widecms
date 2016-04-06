<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

    private $segments = false;

    public function __construct() {
        parent::__construct();
    }

    public $vars = array();

    private function config_load($path, $file) {
        $CI = & get_instance();
        if (!$this->segments) {
            $this->segments = segments();
        }
        $segment = str_replace('-', '_', $this->segments[0]);
        $segment2 = str_replace('-', '_', $this->segments[1]);
        $dir = '';
        $file_ = (isset($file) && is_string($file)) ? $file[0] : '/';
        if ($file_ != '/') {
            if (defined('LOAD_MODULE')) {
                $project = str_replace('-', '_', $this->segments[3]);
                $page = str_replace('-', '_', $this->segments[4]);
                $section = str_replace('-', '_', $this->segments[5]);
                $dir = '../apps/projects/modules/' . $project . '/' . $page . '/' . $section . '/' . $path . '/';
            } elseif ($segment == 'app' && !empty($segment2)) {
                $dir = '../apps/'.$segment2.'/' . $path . '/';
            }
        }
        return $dir;
    }

    private function filter_model($models) {
        $dir = '';
        if (!is_array($models)) {
            $dir = $this->config_load('models', $models);
            $models = $dir . $models;
        }

        return $models;
    }

    public function view($template, $vars = array(), $return = false) {
        $dir = $this->config_load('views', $template);
        return $this->_ci_load(array('_ci_view' => $dir . $template, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    public function model($model, $name = '', $db_conn = FALSE) {
        parent::model($this->filter_model($model), $name, $db_conn);
    }

    private function view_template($path = '', $template, $vars = array(), $return = false) {
        $content = $this->view('/template/header', $this->vars, $return)->output->final_output;

        if (!is_array($template)) {
            $content .= $this->view($path . $template, $this->vars, $return)->output->final_output;
        } else {
            foreach ($template as $temp) {
                $content .= $this->view($temp, $this->vars, $return)->output->final_output;
            }
        }

        $content .= $this->view('/template/footer', $this->vars, $return)->output->final_output;
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
