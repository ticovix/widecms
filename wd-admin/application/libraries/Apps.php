<?php

class Apps
{
    public $path = 'application/apps/';
    private $apps = array();
    private $widgets_loaded = false;

    public function list_apps()
    {
        if (!empty($this->apps)) {
            return $this->apps;
        }

        $path_apps = $this->path;
        $this->apps = array();
        $opendir = \opendir($path_apps);
        while (false !== ($app = readdir($opendir))) {
            if (is_dir($path_apps . $app) && is_file($path_apps . $app . '/app.yml') && check_app($app)) {
                $set_app = $this->set_app($path_apps . $app . '/app.yml', $app);
                if ($set_app) {
                    $this->apps[] = $set_app;
                }
            }
        }

        closedir($opendir);

        return $this->apps;
    }

    private function load_widgets()
    {
        if ($this->widgets_loaded) {
            return $this->widgets_loaded;
        }

        $apps = $this->list_apps();
        $CI = &get_instance();
        foreach ($apps as $app) {
            $status = $app['status'];
            $dir_app = $app['app'];
            $widget_path = $this->path . $dir_app . '/libraries/' . ucfirst($dir_app) . '_widget.php';
            if ($status == true && is_file($widget_path)) {
                $CI->load->app($dir_app)->library($dir_app . '_widget');
            }
        }
    }

    public function list_nav()
    {
        $this->load_widgets();

        $CI = &get_instance();
        $apps = $this->list_apps();
        $nav = array();
        foreach ($apps as $app) {
            $status = $app['status'];
            $dir_app = $app['app'];
            $widget_class = $dir_app . '_widget';
            if ($status === 1) {
                $get_dropdown = null;
                if (isset($CI->$widget_class) && method_exists($CI->$widget_class, 'dropdown')) {
                    $get_dropdown = $CI->$widget_class->dropdown();
                    if (isset($get_dropdown['name'])) {
                        $get_dropdown = array($get_dropdown);
                    }
                }

                if ($get_dropdown) {
                    $app['dropdown'] = $get_dropdown;
                }

                $nav[] = $app;
            }
        }

        return $nav;
    }

    private function set_app($path, $app)
    {
        $CI = &get_instance();
        $spyc = new Spyc();
        $config = $spyc->loadFile($path, false);
        if (is_array($config)) {
            $config['app'] = $app;
            if (isset($config['status']) && $config['status'] != 1) {
                return false;
            }

            $app_path = $this->path . $app . '/controllers/' . ucfirst($app) . '.php';
            if (!is_file($app_path)) {
                $config['show_nav'] = 0;
            }

            if (isset($config['name']) && is_array($config['name'])) {
                $language = $CI->config->item('language');
                if (isset($config['name'][$language])) {
                    $config['name'] = $config['name'][$language];
                }
            }

            if (empty($config['name'])) {
                $config['name'] = $config['app'];
            }

            if (!isset($config['icon']) or isset($config['icon']) && empty($config['icon'])) {
                $config['icon'] = 'fa-exclamation-triangle';
            }

            $config['is_icon_fa'] = (strpos($config['icon'], '/') == false && strpos($config['icon'], 'fa-') >= 0);


            return $config;
        }
    }

    public function list_apps_permissions()
    {
        $apps = $this->list_apps();
        $list_permissions = array();
        foreach ($apps as $app) {
            $status = $app['status'];
            $dir_app = $app['app'];
            $path_permissions = $this->path . $dir_app . '/config/permissions.php';
            if ($status === 1 && is_file($path_permissions)) {
                require($path_permissions);
                if (isset($permission) && is_array($permission)) {
                    $app['permissions'] = $permission;
                    unset($permission);
                }

                $list_permissions[] = $app;
            }
        }

        return $list_permissions;
    }

    public function list_dashboard()
    {
        $apps = $this->list_apps();
        $dashboard = array();

        $CI = &get_instance();
        foreach ($apps as $app) {
            $col = 6;
            $status = $app['status'];
            $dir_app = $app['app'];
            $widget_class = $dir_app . '_widget';
            if ($status == true && isset($CI->$widget_class) && method_exists($CI->$widget_class, 'dashboard')) {
                $get_dashboard = $CI->$widget_class->dashboard();
                if ($get_dashboard) {
                    $app['title'] = $app['name'];
                    $app['content'] = $get_dashboard;
                    $app['col'] = $col;
                    $dashboard[] = $app;
                }
            }
        }

        return $dashboard;
    }

    public function data_app($app = APP)
    {
        $search = search($this->apps, 'app', $app);
        if ($search) {
            return $search[0];
        } else {
            return false;
        }
    }
}