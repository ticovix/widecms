<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Router extends CI_Router {

    public function _set_request($segments = array()) {
        $type = $segments[0];
        $is_project = ($this->uri->segment(1) == 'project');
        if ($is_project) {
            $method = str_replace('-', '_', $segments[1]);
            $project = $this->uri->segment(2);
            $path_file_controllers = getcwd() . '/application/controllers/Project/' . $project . '/';
            $path_library = '../controllers/Project/' . $project . '/';
            $route_default = array('Project', $project);
            switch ($type) {
                case 'pages':
                    /*
                     * Pages
                     */

                    if (is_file($path_file_controllers . 'MY_Pages.php')) {
                        $segments[0] = 'MY_' . ucfirst($segments[0]);
                        $segments = array_merge($route_default, $segments);
                    }
                    break;
                case 'sections':
                    /*
                     * Sections
                     */
                    if (is_file($path_file_controllers . 'MY_Sections.php')) {
                        $segments[0] = 'MY_' . ucfirst($segments[0]);
                        $segments = array_merge($route_default, $segments);
                    }
                    break;
                case 'posts':
                    /*
                     * Posts
                     */
                    if (is_file($path_file_controllers . 'MY_Posts.php')) {
                        $segments[0] = 'MY_' . ucfirst($segments[0]);
                        $segments = array_merge($route_default, $segments);
                    }
                    break;
                default:
                    
                break;
            }
        }
        parent::_set_request($segments);
    }

}
