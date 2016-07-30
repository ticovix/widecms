<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|   my-controller/my-method -> my_controller/my_method
*/
  
/*
 * No change this code
 */
$path_apps = 'application/apps/';
$opendir = \opendir($path_apps);
$app_routes = array();
while (false !== ($app = readdir($opendir))) {
    if ($app != '.' && $app != '..') {
        if (is_dir($path_apps . $app)) {
            if (is_file($path_apps . $app . '/config/routes.php')) {
                require_once($path_apps . $app . '/config/routes.php');
                if (!empty($route)) {
                    $app_routes[$app] = $route;
                    unset($route);
                }
            }
        }
    }
}
if (isset($app_routes) && count($app_routes) > 0) {
    foreach ($app_routes as $app => $routes) {
        foreach ($routes as $key => $value) {
            $route['apps/' . $app . '/' . $key] = $value;
        }
    }
}

/*
 * No change
 */

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;
$route['logout'] = 'home/logout';
