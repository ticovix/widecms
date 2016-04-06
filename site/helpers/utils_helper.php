<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('list_files')) {

    function list_files($json, $limit = null, $offset = 0) {
        if (empty($json)) {
            return false;
        }
        $files = \json_decode($json);
        if (is_object($files)) {
            $files = (array) $files;
        } else {
            return false;
        }
        if ($limit == null or $limit > 1 or ($limit===1 && $offset != null)) {
            $arr_file = array();
            
            foreach ($files as $file) {
                $checked = (isset($file->checked)) ? $file->checked : 0;
                $title = (isset($file->title)) ? $file->title : '';
                $name_file = (isset($file->file)) ? $file->file : '';
                if (!empty($name_file) && is_file(getcwd() . '/wd-content/upload/' . $name_file)) {
                    $arr_file[] = array('file' => $name_file, 'title' => $title, 'checked' => $checked);
                }
            }

            if ($offset or $limit) {
                $total_files = count($arr_file);
                for ($i = $offset; $i < $total_files; $i++) {
                    $final_files[] = $arr_file[$i];
                    if ($limit && $limit == ($i + 1)) {
                        break;
                    }
                }
            } else {
                $final_files = $arr_file;
            }
            return $final_files;
        } else {
            $file = search($files, 'checked', 1);
            if ($file) {
                $file = $file[0];
            } else {
                $file = (array) array_shift($files);
            }

            if (is_file(getcwd() . '/wd-content/upload/' . $file['file'])) {
                return $file;
            } else {
                return false;
            }
        }
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
                if (is_object($subarray)) {
                    $subarray = (array) $subarray;
                }
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