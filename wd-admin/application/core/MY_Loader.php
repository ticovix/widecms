<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
    private $segments = false;
    private $vars = array();

    public function __construct()
    {
        parent::__construct();
    }

    private function view_template($path = '', $template, $vars = array(), $return = false)
    {
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

    public function setVars($vars = array())
    {
        if (!empty($vars)) {
            $this->vars = array_merge($this->vars, $vars);
        }
    }

    public function template($template, $vars = array(), $return = false)
    {

        $this->vars = array_merge($this->vars, $vars);
        $content = $this->view_template(null, $template, $vars, $return);
        if ($return) {
            return $content;
        }
    }
    /* LOAD APPS */

    public function view_app($template, $app = APP, $vars = array(), $return = false, $path_default = false)
    {
        $template = '../apps/' . $app . '/views/' . $template;
        return $this->_ci_load(array('_ci_view' => $template, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    public function library_app($library, $app = APP, $params = NULL, $object_name = NULL)
    {
        $library = '../apps/' . $app . '/libraries/' . $library;
        parent::library($library, $params, $object_name);
    }

    public function helper_app($helpers = array(), $app = APP)
    {
        if (is_array($helpers)) {
            foreach ($helpers as $helper) {
                $this->helper_app($helper, $app);
            }
        } else {
            if (!empty($app)) {
                $helpers = '../apps/' . $app . '/helpers/' . $helpers;
            }
        }

        parent::helper($helpers);
    }

    public function model_app($model, $app = APP, $name = '', $db_conn = FALSE)
    {
        $model = '../apps/' . $app . '/models/' . $model;

        parent::model($model, $name, $db_conn);
    }

    public function template_app($template, $vars = array(), $app = APP, $return = false)
    {
        $app = '../apps/' . $app . '/views/';
        $this->vars = array_merge($this->vars, $vars);
        $content = $this->view_template($app, $template, $vars, $return);
        if ($return) {
            return $content;
        }
    }
    /* LOAD MODULES */

    public function view_module($template, $module = null, $vars = array(), $return = false, $path_default = false)
    {
        if ($module === null) {
            $project = get_project();
            $page = get_page();
            $section = get_section();
            $module = $project['directory'] . '/' . $page['directory'] . '/' . $section['directory'];
        }

        $template = '../apps/projects/modules/' . $module . '/views/' . $template;

        return $this->_ci_load(array('_ci_view' => $template, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    public function library_module($library, $module = null, $params = NULL, $object_name = NULL)
    {
        if ($module === null) {
            $project = get_project();
            $page = get_page();
            $section = get_section();
            $module = $project['directory'] . '/' . $page['directory'] . '/' . $section['directory'];
        }

        $library = '../apps/projects/modules/' . $module . '/libraries/' . $library;

        parent::library($library, $params, $object_name);
    }

    public function helper_module($helpers = array(), $module = null)
    {
        if ($module === null) {
            $project = get_project();
            $page = get_page();
            $section = get_section();
            $module = $project['directory'] . '/' . $page['directory'] . '/' . $section['directory'];
        }

        if (is_array($helpers)) {
            foreach ($helpers as $helper) {
                $this->helper_app($helper, $module);
            }
        } else {
            if (!empty($module)) {
                $helpers = '../apps/projects/modules/' . $module . '/helpers/' . $helpers;
            }
        }

        parent::helper($helpers);
    }

    public function model_module($model, $module = null, $name = '', $db_conn = FALSE)
    {
        if ($module === null) {
            $project = get_project();
            $page = get_page();
            $section = get_section();
            $module = $project['directory'] . '/' . $page['directory'] . '/' . $section['directory'];
        }

        $model = '../apps/projects/modules/' . $module . '/models/' . $model;

        parent::model($model, $name, $db_conn);
    }

    public function template_module($template, $vars = array(), $module = null, $return = false)
    {
        if ($module === null) {
            $project = get_project();
            $page = get_page();
            $section = get_section();
            $module = $project['directory'] . '/' . $page['directory'] . '/' . $section['directory'];
        }

        $module = '../apps/projects/modules/' . $module . '/views/';
        $this->vars = array_merge($this->vars, $vars);
        $content = $this->view_template($module, $template, $vars, $return);
        if ($return) {
            return $content;
        }
    }
}