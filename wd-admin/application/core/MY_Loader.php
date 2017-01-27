<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
    private $app_path_views;
    private $app_path_libraries;
    private $app_path_helpers;
    private $app_path_models;

    public function __construct()
    {
        parent::__construct();
    }

    public function app($app = APP)
    {
        $this->app_path_views = '../apps/' . $app . '/views/';
        $this->app_path_libraries = '../apps/' . $app . '/libraries/';
        $this->app_path_helpers = '../apps/' . $app . '/helpers/';
        $this->app_path_models = '../apps/' . $app . '/models/';

        return $this;
    }

    public function clear_app_vars()
    {
        $this->app_path_views = null;
        $this->app_path_libraries = null;
        $this->app_path_helpers = null;
        $this->app_path_models = null;
    }

    public function module($module = null)
    {
        if (!$module) {
            $project = get_project();
            $page = get_page();
            $section = get_section();
            $module = $project['directory'] . '/' . $page['directory'] . '/' . $section['directory'];
        }

        $this->app_path_views = '../apps/projects/modules/' . $module . '/views/';
        $this->app_path_libraries = '../apps/projects/modules/' . $module . '/libraries/';
        $this->app_path_helpers = '../apps/projects/modules/' . $module . '/helpers/';
        $this->app_path_models = '../apps/projects/modules/' . $module . '/models/';

        return $this;
    }

    public function render($name, array $context = array())
    {
        $path_views = array();
        $path_views[] = APPPATH . 'views/';
        if (!empty($this->app_path_views)) {
            $path_views[] = APPPATH . 'views/' . $this->app_path_views;
        }

        $options = array();
        if (ENVIRONMENT == 'development') {
            $options = array_merge($options, array(
                'debug' => true
            ));
        }

        $loader = new Twig_Loader_Filesystem($path_views);
        $twig = new Twig_Environment($loader);
        foreach (get_defined_functions() as $functions) {
            foreach ($functions as $function) {
                $twig->addFunction($function, new Twig_Function_Function($function));
            }
        }

        $this->clear_app_vars();

        return $twig->render($name, $context);
    }

    public function view($views = array(), $vars = array(), $return = false)
    {
        if (!empty($this->app_path_views)) {
            if (is_array($views)) {
                $aux = array();
                foreach ($views as $view) {
                    $aux[] = $this->app_path_views . $view;
                }

                $views = $aux;
            } else {
                $views = $this->app_path_views . $views;
            }
        }

        $this->clear_app_vars();
        return $this->_ci_load(array('_ci_view' => $views, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    public function library($library, $params = NULL, $object_name = NULL)
    {
        if (!empty($this->app_path_libraries)) {
            $library = $this->app_path_libraries . $library;
        }

        $this->clear_app_vars();
        parent::library($library, $params, $object_name);
    }

    public function helper($helpers = array())
    {
        if (!empty($this->app_path_helpers)) {
            if (is_array($helpers)) {
                $aux = array();
                foreach ($helpers as $helper) {
                    $aux[] = $this->app_path_helpers . $helper;
                }

                $helpers = $aux;
            } else {
                $helpers = $this->app_path_helpers . $helpers;
            }
        }

        $this->clear_app_vars();
        parent::helper($helpers);
    }

    public function model($model, $name = '', $db_conn = FALSE)
    {
        if (!empty($this->app_path_models)) {
            $model = $this->app_path_models . $model;
        }

        $this->clear_app_vars();
        parent::model($model, $name, $db_conn);
    }
}