<?php

if (!function_exists('only_number')) {

    function only_numbers($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }
}

if (!function_exists('verify_permission')) {

    function verify_permission($nav)
    {
        $CI = & get_instance();
        $CI->securitypanel->verifyPermission($nav);
    }
}

if (!function_exists('search')) {

    function search($array, $key, $value, $regex = false)
    {
        $results = array();
        if (is_array($value) && is_array($array)) {
            foreach ($value as $val) {
                $results = array_merge($results, search($array, $key, $val, $regex));
            }
        } elseif (is_array($array) && !is_array($value)) {
            if ((isset($array[$key]) && $array[$key] == $value) or ( $regex == true && isset($array[$key]) && preg_match('/' . $value . '/i', $array[$key]) > 0)) {
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

    function slug($string, $transform_space = '-')
    {
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

    function forceRemoveDir($dir)
    {
        $opendir = \opendir($dir);
        if ($opendir) {
            if ($dd = $opendir) {
                while (false !== ($file = readdir($dd))) {
                    if ($file != '.' && $file != '..') {
                        $path = $dir . '/' . $file;
                        if (is_dir($path)) {
                            forceRemoveDir($path);
                        } elseif (is_file($path)) {
                            \unlink($path);
                        }
                    }
                }
                closedir($dd);
            }
            \rmdir($dir);
        }
    }
}

if (!function_exists('FileSizeConvert')) {

    function FileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }
}

if (!function_exists('projects')) {

    function projects()
    {
        $CI = & get_instance();
        $CI->load->model('projects_model');
        $id_user = $CI->session->userdata('id');
        return $CI->projects_model->search($CI->user_data['dev_mode']);
    }
}

if (!function_exists('get_project')) {
    $project = null;

    function get_project()
    {
        $CI = & get_instance();
        if (!isset($CI->project)) {
            $slug_project = $CI->uri->segment(4);
            $CI->load->app('projects')->model('projects_model');
            return $CI->project = $CI->projects_model->get_project($slug_project);
        } else {
            return $CI->project;
        }
    }
}
/*
 * Método para listar página
 */
if (!function_exists('get_page')) {

    function get_page()
    {
        $CI = & get_instance();
        $project = get_project();
        $page = $CI->uri->segment(5);
        if (empty($CI->page)) {
            $CI->load->app('projects')->model('pages_model');

            return $CI->page = $CI->pages_model->get_page($project['directory'], $page);
        } else {
            return $CI->page;
        }
    }
}
/*
 * Método para listar seção
 */
if (!function_exists('get_section')) {

    function get_section($section = null)
    {
        $CI = & get_instance();
        if (!$section) {
            $section = $CI->uri->segment(6);
        }
        if (empty($CI->section)) {
            $project = get_project();
            $page = get_page();
            $CI->load->app('projects')->model('sections_model');

            return $CI->section = $CI->sections_model->get_section($project['directory'], $page['directory'], $section);
        } else {
            return $CI->section;
        }
    }
}
if (!function_exists('func_only_dev')) {

    function func_only_dev()
    {
        $CI = & get_instance();
        if (!$CI->user_data['dev_mode']) {
            header('HTTP/1.1 403 Forbidden');
            die();
        }
    }
}
if (!function_exists('segments')) {

    function segments()
    {
        $CI = & get_instance();
        return array(
            $CI->uri->segment(1),
            $CI->uri->segment(2),
            $CI->uri->segment(3),
            $CI->uri->segment(4),
            $CI->uri->segment(5),
            $CI->uri->segment(6),
            $CI->uri->segment(7),
            $CI->uri->segment(8),
        );
    }
}

if (!function_exists('base_app_url')) {

    function base_app_url($uri = '', $protocol = null)
    {
        $base_url = base_url(APP_PATH . $uri, $protocol);
        return $base_url;
    }
}

if (!function_exists('app_redirect')) {

    function app_redirect($url, $method = 'auto', $code = null)
    {
        if (strpos($url, '://') === false) {
            $url = APP_PATH . $url;
        }
        redirect($url, $method, $code);
    }
}

if (!function_exists('module_redirect')) {

    function module_redirect($url, $method = 'auto', $code = null)
    {
        $segments = segments();
        $project = $segments[3];
        $page = $segments[4];
        $section = $segments[5];
        redirect(APP_PATH . 'project/' . $project . '/' . $page . '/' . $section . '/' . $url, $method, $code);
    }
}

if (!function_exists('base_module_url')) {

    function base_module_url($uri = '', $protocol = null)
    {
        $segments = segments();
        $project = $segments[3];
        $page = $segments[4];
        $section = $segments[5];
        $addslashes = '';
        if (empty($uri)) {
            $addslashes = '/';
        }
        $base_url = base_url(APP_PATH . 'project/' . $project . '/' . $page . '/' . $section . '/' . $uri, $protocol) . $addslashes;
        return $base_url;
    }
}

if (!function_exists('wd_base_url')) {

    function wd_base_url($uri = '', $protocol = null)
    {
        return str_replace(DIR_ADMIN_DEFAULT, '', base_url($uri, $protocol));
    }
}

if (!function_exists('diff_date_today') && !function_exists('month_name')) {

    function month_name($value)
    {
        switch ($value) {
            case '1': $month = "Janeiro";
                break;
            case '2': $month = "Fevereiro";
                break;
            case '3': $month = "Março";
                break;
            case '4': $month = "Abril";
                break;
            case '5': $month = "Maio";
                break;
            case '6': $month = "Junho";
                break;
            case '7': $month = "Julho";
                break;
            case '8': $month = "Agosto";
                break;
            case '9': $month = "Setembro";
                break;
            case '10': $month = "Outubro";
                break;
            case '11': $month = "Novembro";
                break;
            case '12': $month = "Dezembro";
                break;
            default: $month = "ERROR";
                break;
        }

        return $month;
    }

    function diff_date_today($dateSql)
    {
        $datetime1 = new DateTime($dateSql);
        $datetime2 = new DateTime('now');
        $interval = $datetime1->diff($datetime2);
        $days = $interval->days;
        $month = $interval->m;
        $year = $interval->y;
        $hours = $interval->h;
        $result = '';
        $h = ($hours > 1) ? ' horas' : ' hora';
        $d = ($days > 1) ? ' dias' : ' dia';
        $m = ($month > 1) ? ' meses' : ' mês';
        $a = ($year > 1) ? ' anos' : ' ano';
        if ($days == 0 && $hours == 0) {
            $result = 'Agora';
        } elseif ($days == 0) {
            $result = 'Há ' . $hours . $h;
        } elseif ($month == 0) {
            $result = 'Há ' . $days . $d;
        } elseif ($month > 0 && $year == 0) {
            $result = $datetime1->format('d') . ' de ' . month_name($datetime1->format('m'));
        } elseif ($year > 0) {
            $result = $datetime1->format('d') . ' de ' . month_name($datetime1->format('m')) . ' de ' . $datetime1->format('Y');
        }

        return $result;
    }
}

if (!function_exists('format_xml_string')) {

    function format_xml_string($xml)
    {
        $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
        $token = strtok($xml, "\n");
        $result = '';
        $pad = 0;
        $matches = array();
        while ($token !== false) :
            if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
                $indent = 0;
            elseif (preg_match('/^<\/\w/', $token, $matches)) :
                $pad--;
                $indent = 0;
            elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
                $indent = 1;
            else :
                $indent = 0;
            endif;
            $line = str_pad($token, strlen($token) + $pad, ' ', STR_PAD_LEFT);
            $result .= $line . "\n";
            $token = strtok("\n");
            $pad += $indent;
        endwhile;

        return $result;
    }
}

if (!function_exists('load_gallery_upload')) {

    function load_gallery()
    {
        $CI = &get_instance();
        $CI->include_components
                ->main_css(array(
                    'plugins/fancybox/css/jquery.fancybox.css',
                    'plugins/fancybox/css/jquery.fancybox-buttons.css',
                    'plugins/dropzone/css/dropzone.css',
                ))
                ->main_js(array(
                    'plugins/dropzone/js/dropzone.js',
                    'plugins/fancybox/js/jquery.fancybox.pack.js',
                    'plugins/fancybox/js/jquery.fancybox-buttons.js',
                    'plugins/embeddedjs/ejs.js',
                ))
                ->app_css('css/gallery_modal.css', 'gallery')
                ->app_js('js/upload.js', 'gallery');
    }
}