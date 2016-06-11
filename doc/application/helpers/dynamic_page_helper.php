<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('add_js')) {

    function add_js($file = '') {
        $str = '';
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
                $footer_js[] = $item;
            }
            $ci->config->set_item('add_js', $footer_js);
        } else {
            $str = $file;
            $footer_js[] = $str;
            $ci->config->set_item('add_js', $footer_js);
        }
    }

}
if (!function_exists('add_css')) {

    function add_css($file = '') {
        $str = '';
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
                $header_css[] = $item;
            }
            $ci->config->set_item('add_css', $header_css);
        } else {
            $str = $file;
            $header_css[] = $str;
            $ci->config->set_item('add_css', $header_css);
        }
    }

}

if (!function_exists('put_css')) {

    function put_css() {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('add_css');
        $path_asset = $ci->config->item('path_asset');
        
        if ($header_css) {
            foreach ($header_css AS $item) {
                if(strpos($item, '//')){
                    $href = $item;
                }else{
                    $href = base_url().$path_asset.$item;
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
        $path_asset = $ci->config->item('path_asset');
        if($footer_js){
            foreach ($footer_js AS $item) {
                if(strpos($item, '//')){
                    $src = $item;
                }else{
                    $src = base_url().$path_asset.$item;
                }
                $str .= '<script type="text/javascript" src="' . $src . '"></script>' . "\n";
            }
        }

        return $str;
    }

}