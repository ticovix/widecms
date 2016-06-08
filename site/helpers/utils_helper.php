<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * Método para listar arquivos de uma coluna json do banco de dados
 * param @json: JSON com os arquivos salvos da coluna no banco de dados
 * param @limit: limita a quantidade de listagem de arquivos
 * param @offset: chave que o ponteiro começará a contar
 * param @order_by_check: define se a ordem da listagem, iniciando pela capa, padrão: true (a capa sempre aparecerá primeiro, exceto se alterado para false)
 */
if (!function_exists('list_files')) {

    function list_files($json, $limit = null, $offset = 0, $order_by_check = true) {
        if (empty($json)) {
            return false;
        }
        // Decodifica o json para object
        $files = \json_decode($json);
        // Verifica se foi decodificado
        if (is_object($files) or is_array($files)) {
            $files = (array) $files;
        } else {
            return false;
        }
        // Se o limit for setado como 1, busca o arquivo principal ou exibe o primeiro arquivo que encontrar.
        if ($limit == '1') {
            $file = search($files, 'checked', 1);
            if ($file) {
                $file = $file[0];
            } else {
                $file = (array) array_shift($files);
            }
            if (is_file('wd-content/upload/' . $file['file'])) {
                return $file;
            } else {
                return false;
            }
        } else {
            // Se for necessário listar mais de um arquivo
            $arr_file = array();
            // Busca o arquivo principal
            $search_checked = search($files, 'checked', 1);
            // Caso encontre, seta no array de arquivos
            if (isset($search_checked[0]) && $order_by_check == true) {
                $arr_file[] = array('file' => $search_checked[0]['file'], 'title' => $search_checked[0]['title'], 'checked' => 1);
            }
            // Lista os arquivos do objeto
            foreach ($files as $file) {
                $checked = (isset($file->checked)) ? $file->checked : 0;
                $title = (isset($file->title)) ? $file->title : '';
                $name_file = (isset($file->file)) ? $file->file : '';
                // Seta imagens que o caminho exista e que não seja o arquivo principal
                if (!empty($name_file) && is_file('wd-content/upload/' . $name_file) && ($checked == '0' or ! $order_by_check)) {
                    $arr_file[] = array('file' => $name_file, 'title' => $title, 'checked' => $checked);
                }
            }
            /* Se o parametro offset ou limit for preenchido
             * limita a exibição
             */
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
        }
    }

}

/*
 * Método para inserir arquivos no json
 */
if (!function_exists('insert_files')) {
    function insert_files($json, $add_files = array()){
        // Decodifica o json para object
        $files = \json_decode($json);
        // Verifica se foi decodificado
        if (is_object($files) or is_array($files)) {
            $files = (array) $files;
        } else {
            return false;
        }
        if (!isset($add_files[0])) {
            $add_files = array($add_files);
        }
        $count = count($files);
        foreach ($add_files as $arr) {
            $file = $arr['file'];
            $checked = (isset($arr['checked']))?$arr['checked']:'';
            $title = (isset($arr['title']))?$arr['title']:'';
            $files[] = array('file' => $file, 'checked' => $checked, 'title' => $title);
            if($checked==true){
                $encode = json_encode($files);
                $files = json_decode(check_file($encode, $count));
            }
            $count++;
        }
        return json_encode($files);
        
    }
}
/*
 * Método para alterar o arquivo principal
 */

if (!function_exists('check_file')) {

    function check_file($json, $key) {
        // Decodifica o json para object
        $files = \json_decode($json);
        // Verifica se foi decodificado
        if (is_object($files) or is_array($files)) {
            $files = (array) $files;
        } else {
            return false;
        }

        foreach ($files as $k => $file) {
            if ($k == $key) {
                $file->checked = true;
            } else {
                $file->checked = false;
            }
            $arr[] = $file;
        }
        return json_encode($arr);
    }

}

/*
 * Método para remover multiplos arquivos
 */
if (!function_exists('remove_files')) {

    function remove_files($json, $keys, $remove_files = false) {
        // Decodifica o json para object
        $files = \json_decode($json);
        // Verifica se foi decodificado
        if (is_object($files) or is_array($files)) {
            $files = (array) $files;
        } else {
            return false;
        }
        if (!is_array($keys)) {
            $keys = array($keys);
        }
        foreach ($files as $k => $file) {
            if (!in_array($k, $keys)) {
                $arr[] = $file;
            } elseif ($remove_files) {
                @unlink('wd-content/upload/' . $file['file']);
            }
        }
        return json_encode($arr);
    }

}

if (!function_exists('search')) {

    function search($array, $key, $value, $regex = false) {
        $results = array();
        if (is_array($value) && is_array($array)) {
            foreach ($value as $val) {
                $results = array_merge($results, search($array, $key, $val, $regex));
            }
        } elseif (is_array($array) && !is_array($value)) {
            if ((isset($array[$key]) && $array[$key] == $value) or ($regex==true && isset($array[$key]) && preg_match('/'.$value.'/', $array[$key])>0)) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                if (is_object($subarray)) {
                    $subarray = (array) $subarray;
                }
                $results = array_merge($results, search($subarray, $key, $value, $regex));
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