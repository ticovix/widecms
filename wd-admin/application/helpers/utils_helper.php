<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('only_number')) {

    function only_numbers($str) {
        return preg_replace('/[^0-9]/', '', $str);
    }

}
if (!function_exists('is_nav_active')) {

    function is_nav_active($page_current = null, $keys = null) {
        $result = 'active';
        $return = null;
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        if (in_array($page_current, $keys)) {
            $return = $result;
        }
        return $return;
    }

}

if (!function_exists('verify_permission')) {

    function verify_permission($nav) {
        $CI = & get_instance();
        $CI->securitypanel->verifyPermission($nav);
    }

}

if (!function_exists('search')) {

    function search($array, $key, $value) {
        $results = array();
        if (is_array($value) && is_array($array)) {
            foreach ($value as $val) {
                $results = array_merge($results, search($array, $key, $val));
            }
        } elseif (is_array($array) && !is_array($value)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, search($subarray, $key, $value));
            }
        }
        return $results;
    }

}

if (!function_exists('slug')) {

    function slug($string, $transform_space = '-') {
        $table = ['Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z',
            'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
            'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
            'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r'
        ];
        $strReplace = strtr($string, $table);
        $strToLower = strtolower($strReplace);
        $strClean = preg_replace("/[^a-z0-9_\s-]/", "", $strToLower);
        $treatStr = preg_replace("/[\s-]+/", " ", $strClean);
        $strTrim = trim($treatStr);
        $slug = preg_replace("/[\s_]/", $transform_space, $strTrim);
        return $slug;
    }

}

if (!function_exists('forceRemoveDir')) {

    function forceRemoveDir($dir) {
        if ($dd = opendir($dir)) {
            while (false !== ($file = readdir($dd))) {
                if ($file != '.' && $file != '..') {
                    $path = $dir . '/' . $file;
                    if (is_dir($path)) {
                        forceRemoveDir($path);
                    } elseif (is_file($path)) {
                        unlink($path);
                    }
                }
            }
            closedir($dd);
        }
        rmdir($dir);
    }

}
if (!function_exists('generateXML') && !function_exists('arrayToXML')) {

    function generateXML($tag_in, $value_in = "", $attribute_in = "", $cdata) {
        $return = "";
        $attributes_out = "";
        if (is_array($attribute_in)) {
            if (count($attribute_in) != 0) {
                foreach ($attribute_in as $k => $v):
                    $attributes_out .= " " . $k . "=\"" . $v . "\"";
                endforeach;
            }
        }
        if (!is_array($value_in) && $cdata) {
            $value_in = '<![CDATA[' . $value_in . ']]>';
        }
        if (!is_numeric($tag_in)) {
            return "<" . $tag_in . "" . $attributes_out . ((trim($value_in) == "") ? "/>" : ">" . $value_in . "</" . $tag_in . ">" );
        } else {
            return $value_in;
        }
    }

    function arrayToXML($array_in, $cdata = false, $header = true) {
        $return = '';
        if ($header) {
            $header = 1;
            $return = '<?xml version="1.0" encoding="UTF-8"?>';
        }
        $attributes = array();
        foreach ($array_in as $k => $v):
            if ($k[0] == "@") {
                $attributes[str_replace("@", "", $k)] = $v;
            } else {
                if (is_array($v)) {
                    $return .= generateXML($k, arrayToXML($v, $cdata, false), $attributes, false);
                    $attributes = array();
                } else if (is_bool($v)) {
                    $return .= generateXML($k, (($v == true) ? "true" : "false"), $attributes, false);
                    $attributes = array();
                } else {
                    $return .= generateXML($k, htmlspecialchars($v, ENT_NOQUOTES), $attributes, $cdata);
                    $attributes = array();
                }
            }
        endforeach;
        return $return;
    }

}


if (!function_exists('setError') && !function_exists('getErrors')) {

    function setError($lang, $message) {
        $CI = & get_instance();
        if ($CI->load->is_loaded('error_reporting')) {
            $CI->error_reporting->setError($lang, $message);
        } else {
            return false;
        }
    }

    function getErrors($prefix = '<div class="alert alert-danger">', $suffix = '</div>') {
        $CI = & get_instance();
        if ($CI->load->is_loaded('error_reporting')) {
            return $CI->error_reporting->getErrors($prefix, $suffix);
        } else {
            return false;
        }
    }

}

if (!function_exists('projects')) {

    function projects() {
        $CI = & get_instance();
        $CI->load->model('projects_model');
        $id_user = $CI->session->userdata('id');
        return $CI->projects_model->search($CI->dataUser['dev_mode']);
    }

}