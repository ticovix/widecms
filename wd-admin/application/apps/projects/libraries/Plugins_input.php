<?php

class Plugins_input
{
    private $list_plugins = array();

    public function list_plugins()
    {
        $path_apps = getcwd() . '/application/' . APP_PATH . 'plugins_input/';
        $opendir = \opendir($path_apps);
        while (false !== ($plugin = readdir($opendir))) {
            if ($plugin != '.' && $plugin != '..') {
                if (is_dir($path_apps . $plugin)) {
                    if (is_file($path_apps . $plugin . '/plugin.yml')) {
                        $this->set_plugin($path_apps . $plugin . '/plugin.yml', $plugin);
                    }
                }
            }
        }

        closedir($opendir);
        asort($this->list_plugins);

        return $this->list_plugins;
    }

    private function set_plugin($path, $plugin)
    {
        $spyc = new Spyc();
        $config = $spyc->loadFile($path);
        if (!is_array($config)) {
            return false;
        }

        $config['plugin'] = $plugin;
        if (empty($config['name'])) {
            $config['name'] = $config['plugin'];
        }

        $config['name'] = ucfirst(strtolower($config['name']));

        return $this->list_plugins[] = $config;
    }

    public function get_plugins($plugins)
    {
        $plugins = explode("|", $plugins);
        if (!$plugins) {
            return false;
        }

        $arr_plugins = array();
        foreach ($plugins as $plugin) {
            if (!empty($plugin)) {
                if (empty($this->list_plugins)) {
                    $plugins = $this->list_plugins();
                } else {
                    $plugins = $this->list_plugins;
                }

                if ($plugins) {
                    $plugin = search($plugins, 'plugin', $plugin);
                    if (isset($plugin[0])) {
                        $arr_plugins[] = $plugin[0];
                    }
                }
            }
        }

        return $arr_plugins;
    }
}