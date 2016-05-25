<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

    private $segments = false;
    private $vars = array();

    public function __construct() {
        parent::__construct();
    }

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
            } elseif ($segment == 'apps' && !empty($segment2)) {
                $dir = '../apps/' . $segment2 . '/' . $path . '/';
            }
        }
        return $dir;
    }

    public function view($template, $vars = array(), $return = false, $path_default = false) {
        if ($path_default) {
            $dir = '';
        } else {
            $dir = $this->config_load('views', $template);
        }
        return $this->_ci_load(array('_ci_view' => $dir . $template, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    public function view_app($template, $app = APP, $vars = array(), $return = false, $path_default = false) {
        if (!empty($app)) {
            if (strpos($app, '/') === false) {
                $template = '../apps/' . $app . '/views/' . $template;
            } else {
                $template = '../apps/' . $app . $template;
            }
        }
        return $this->_ci_load(array('_ci_view' => $template, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    public function library_app($library, $app = APP, $params = NULL, $object_name = NULL) {
        if (!empty($app)) {
            if (strpos($app, '/') === false) {
                $library = '../apps/' . $app . '/libraries/' . $library;
            } else {
                $library = '../apps/' . $app . $library;
            }
        }
        parent::library($library, $params, $object_name);
    }

    public function model_app($model, $app = APP, $name = '', $db_conn = FALSE) {
        if (!empty($app)) {
            $model = '../apps/' . $app . '/models/' . $model;
        }
        parent::model($model, $name, $db_conn);
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

}
