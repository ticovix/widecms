<?php

class Apps {

    public $path = 'application/apps/';
    private $apps = array();

    public function list_apps() {
        $path_apps = $this->path;
        $opendir = \opendir($path_apps);
        while (false !== ($app = readdir($opendir))) {
            if ($app != '.' && $app != '..') {
                if (is_dir($path_apps . $app)) {
                    if (is_file($path_apps . $app . '/app.yml')) {
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
        $config = $CI->spyc->loadFile($path);
        if (is_array($config)) {
            $config['app'] = $app;
            if (empty($config['name'])) {
                $config['name'] = $config['app'];
            }
            if (isset($config['status']) && $config['status'] == 1) {
                return $this->apps[] = $config;
            }
        }
    }

}
