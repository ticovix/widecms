<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('path_assets')) {

    function path_assets($file, $app) {
        if ($file[0] == '/') {
            $path = 'assets';
        } elseif (!empty($app)) {
            $path = 'application/apps/' . $app . '/assets/';
        } else {
            $path = 'assets/';
        }
        return $path;
    }

}

if (!function_exists('add_js')) {

    function add_js($file = '', $app = APP) {
        $ci = &get_instance();
        $footer_js = $ci->config->item('add_js');
        if (empty($file)) {
            return;
        }

        if (is_array($file)) {
            if (!is_array($file) && count($file) <= 0) {
                return;
            }
            foreach ($file as $item) {
                $path = path_assets($item, $app);
                $footer_js[] = $path . $item;
            }
            $ci->config->set_item('add_js', $footer_js);
        } else {
            $path = path_assets($file, $app);
            $footer_js[] = $path . $file;
            $ci->config->set_item('add_js', $footer_js);
        }
    }

}
if (!function_exists('add_css')) {

    function add_css($file = '', $app = APP) {
        $ci = &get_instance();
        $header_css = $ci->config->item('add_css');
        if (empty($file)) {
            return;
        }

        if (is_array($file)) {
            if (!is_array($file) && count($file) <= 0) {
                return;
            }
            foreach ($file as $item) {
                $path = path_assets($item, $app);
                $header_css[] = $path . $item;
            }
            $ci->config->set_item('add_css', $header_css);
        } else {
            $path = path_assets($file, $app);
            $header_css[] = $path . $file;
            $ci->config->set_item('add_css', $header_css);
        }
    }

}

if (!function_exists('put_css')) {

    function put_css() {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('add_css');

        if ($header_css) {
            $header_css = array_unique($header_css);
            foreach ($header_css AS $item) {
                if (strpos($item, '//')) {
                    $href = $item;
                } else {
                    $href = base_url($item);
                }
                $str .= '<link rel="stylesheet" href="' . $href . '" type="text/css" />' . "\n";
            }
        }

        return $str;
    }

}

if (!function_exists('put_js')) {

    function put_js() {
        $str = '';
        $ci = &get_instance();
        $footer_js = $ci->config->item('add_js');
        if ($footer_js) {
            $footer_js = array_unique($footer_js);
            foreach ($footer_js AS $item) {
                if (strpos($item, '//')) {
                    $src = $item;
                } else {
                    $src = base_url($item);
                }
                $str .= '<script type="text/javascript" src="' . $src . '"></script>' . "\n";
            }
        }

        return $str;
    }

}