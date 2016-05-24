<?php

class Apps {

    public $path = 'application/apps/';
    private $apps = array();

    public function list_apps() {
        $path_apps = $this->path;
        $this->apps = array();
        $opendir = \opendir($path_apps);
        while (false !== ($app = readdir($opendir))) {
            if ($app != '.' && $app != '..') {
                if (is_dir($path_apps . $app)) {
                    $CI = &get_instance();
                    if (is_file($path_apps . $app . '/app.yml') && check_app($app)) {
                        $this->set_app($path_apps . $app . '/app.yml', $app);
                    }
                }
            }
        }
        closedir($opendir);
        return $this->apps;
    }

    private function set_app($path, $app) {
        $CI = &get_instance();
        $CI->load->library('spyc');
        $config = $CI->spyc->loadFile($path, false);
        if (is_array($config)) {
            $config['app'] = $app;
            if (empty($config['name'])) {
                $config['name'] = $config['app'];
            }
            if (!isset($config['icon']) or isset($config['icon']) && empty($config['icon'])) {
                $config['icon'] = 'fa-exclamation-triangle';
            }
            if (isset($config['status']) && $config['status'] == 1) {
                return $this->apps[] = $config;
            }
        }
    }

    public function list_apps_permissions() {
        if (empty($this->apps)) {
            $apps = $this->list_apps();
        }
        $apps = $this->apps;
        $config_permissions = array();
        foreach ($apps as $app) {
            $status = $app['status'];
            $dir_app = $app['app'];
            if ($status === 1) {
                $path_permissions = $this->path . $dir_app . '/config/permissions.php';
                if (is_file($path_permissions)) {
                    require($path_permissions);
                    $app['permissions'] = $permission;
                    unset($permission);
                }
                $config_permissions[] = $app;
            }
        }
        return $config_permissions;
    }

    public function list_widgets_dashboards() {
        if (empty($this->apps)) {
            $apps = $this->list_apps();
        }
        $apps = $this->apps;
        $widgets = array();
        $CI = &get_instance();
        foreach ($apps as $app) {
            $status = $app['status'];
            $dir_app = $app['app'];
            if ($status === 1) {
                $path = $this->path . $dir_app . '/widgets/dashboard/';
                if (is_dir($path)) {
                    $opendir = \opendir($path);
                    while (false !== ($widget = readdir($opendir))) {
                        if (is_file($path.$widget) && strpos($widget, '.php') !== false) {
                            $CI->load->library_app($widget, $dir_app.'/widgets/dashboard/');
                        }
                    }
                }
            }
        }
        return $widgets;
    }

}
