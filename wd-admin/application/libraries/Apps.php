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
            // Verifica se possui tradução
            if (isset($config['name']) && is_array($config['name'])) {
                $language = $CI->config->item('language');
                if(isset($config['name'][$language])){
                    $config['name'] = $config['name'][$language];
                }
            } elseif (empty($config['name'])) {
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
                    if (isset($permission)) {
                        $app['permissions'] = $permission;
                        unset($permission);
                    }
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
            $col = 6;
            $status = $app['status'];
            $dir_app = $app['app'];
            $name_app = $app['name'];
            $icon_app = $app['icon'];
            if ($status === 1) {
                $path = $this->path . $dir_app . '/libraries/widgets/';
                if (is_dir($path)) {
                    $opendir = \opendir($path);
                    
                    while (false !== ($widget = readdir($opendir))) {
                        if (is_file($path . $widget) && strpos($widget, '_dashboard.php') !== false) {
                            ob_start();
                            $CI->load->library_app('widgets/' . $widget, $dir_app);
                            $class = strtolower(str_replace('.php', '', $widget));
                            if(isset($CI->$class->col) && $CI->$class->col === 12){
                                $col = $CI->$class->col;
                            }
                            $content = ob_get_contents();
                            ob_end_clean();
                            if (!empty($content)) {
                                $widgets[] = array(
                                    'title' => $name_app,
                                    'icon' => $icon_app,
                                    'content' => $content,
                                    'app' => $app,
                                    'col' => $col
                                );
                            }
                        }
                    }
                }
            }
        }
        return $widgets;
    }
    
    public function data_app($app=APP){
        $search = search($this->apps,'app',$app);
        if($search){
            return $search[0];
        }else{
            return false;
        }
    }

}
