<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$path_projects = 'application/apps/projects/views/project/';
$path_modules = 'application/apps/projects/modules/';
$CI = &get_instance();
$CI->load->model('../apps/projects/models/projects_model', null, false, true);
$CI->load->model('../apps/projects/models/pages_model', null, false, true);
$CI->load->model('../apps/projects/models/sections_model', null, false, true);
$CI->load->library('../apps/projects/libraries/config_xml');
$projects = $CI->projects_model->list_projects_permissions();
if ($projects) {
    foreach ($projects as $project) {
        $id_project = $project['id'];
        $name_project = $project['name'];
        $slug_project = $project['slug'];
        $dir_project = $project['directory'];
        $pages = $CI->pages_model->list_pages_permissions($id_project);
        if ($pages) {
            foreach ($pages as $page) {
                $id_page = $page['id'];
                $name_page = $page['name'];
                $slug_page = $page['slug'];
                $dir_page = $page['directory'];
                $sections = $CI->sections_model->list_sections_permissions($id_page);
                if ($sections) {
                    foreach ($sections as $section) {
                        $name_section = $section['name'];
                        $slug_section = $section['slug'];
                        $dir_section = $section['directory'];
                        $config = $path_projects . $dir_project . '/' . $dir_page . '/' . $dir_section . '/config.xml';
                        $module = $path_modules . $dir_project . '/' . $dir_page . '/' . $dir_section . '/controllers/' . ucfirst($dir_section) . '.php';
                        $method = $slug_project . '-' . $slug_page . '-' . $slug_section;
                        $page = 'project/' . $slug_project . '/' . $slug_page . '/' . $slug_section;
                        if (!is_file($module)) {
                            // Verifica se possui listagem de registros na seção, se existir insere mais duas permissões
                            if (is_file($config)) {
                                $config = $CI->config_xml->load_config($dir_project, $dir_page, $dir_section);
                                if (isset($config['list']) && count($config['list']) > 0) {
                                    $permission[$page][$method] = '<strong>' . $name_project . ' - ' . $name_page . ' - ' . $name_section . '</strong>';
                                    $permission[$page][$page . '/' . 'create'][$method . '-create'] = 'Criar';
                                    $permission[$page][$page . '/' . 'edit/.*'][$method . '-edit'] = 'Editar';
                                    $permission[$page][$page . '/' . $slug_section . '/' . 'remove/.*'][$method . '-remove'] = 'Remover';
                                } else {
                                    $permission[$page][$method] = '<strong>' . $name_project . ' - ' . $name_page . ' - ' . $name_section . '</strong> - Editar';
                                }
                            }
                        } else {
                            $file_permissions = $path_modules . $dir_project . '/' . $dir_page . '/' . $dir_section . '/config/permissions.php';
                            if (is_file($file_permissions)) {
                                require($file_permissions);
                            }
                        }
                    }
                }
            }
        }
    }
}
