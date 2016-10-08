<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Include_components
{
    private $files_js = array();
    private $files_css = array();
    private $path_default = 'assets/';
    private $path_vendor_default = '../vendor/';
    private $path_app_default = 'application/apps/%s/assets/';
    private $path_current = '';

    /* Change directory to main */

    private function cd_main()
    {
        $this->path_current = $this->path_default;
        return $this;
    }
    /* Change directory to vendor */

    private function cd_vendor()
    {
        $this->path_current = $this->path_vendor_default;
        return $this;
    }
    /* Change directory to app */

    private function cd_app($app = APP)
    {
        $this->path_current = sprintf($this->path_app_default, $app);
        return $this;
    }

    public function main_js($files)
    {
        $this->cd_main();
        $this->js($files);

        return $this;
    }

    public function main_css($files)
    {
        $this->cd_main();
        $this->css($files);

        return $this;
    }

    public function vendor_js($files)
    {
        $this->cd_vendor();
        $this->js($files);

        return $this;
    }

    public function vendor_css($files)
    {
        $this->cd_vendor();
        $this->css($files);

        return $this;
    }

    public function app_js($files, $app = APP)
    {
        $this->cd_app($app);
        $this->js($files);

        return $this;
    }

    public function app_css($files, $app = APP)
    {
        $this->cd_app($app);
        $this->css($files);

        return $this;
    }

    private function js($files)
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->js($file);
            }
        } else {
            $file = $files;
            if (!strpos($file, '//')) {
                $url = base_url($this->path_current . $file);
            }
            $this->files_js[] = $this->treat_url($url);
        }

        return $this;
    }

    private function css($files)
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->css($file);
            }
        } else {
            $file = $files;
            if (!strpos($file, '//')) {
                $url = base_url($this->path_current . $file);
            }
            $this->files_css[] = $this->treat_url($url);
        }

        return $this;
    }

    public function put_js()
    {
        $template = '';
        if ($this->files_js) {
            $files = array_unique($this->files_js);
            foreach ($files AS $file) {
                $template .= '<script type="text/javascript" src="' . $file . '"></script>' . "\n";
            }
        }

        return $template;
    }

    public function put_css()
    {
        $template = '';
        if ($this->files_css) {
            $files = array_unique($this->files_css);
            foreach ($files AS $file) {
                $template .= '<link rel="stylesheet" href="' . $file . '" type="text/css" />' . "\n";
            }
        }

        return $template;
    }

    private function treat_url($url)
    {
        $line = array();
        if (is_array($url)) {
            foreach ($url as $url_cur) {
                $line[] = $this->treat_url($url_cur);
            }
        } else {
            while (strpos($url, '..') > 0) {
                $url = preg_replace('/\/[a-z0-9_-]*\/\.\./i', '', $url);
            }
            $line = str_replace('./', '', $url);
        }
        return $line;
    }
}