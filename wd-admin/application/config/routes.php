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
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;
$route['logout'] = 'home/logout';


$route['project/(:any)/create-page'] = 'pages/create-page/$1';
$route['project/(:any)/edit-page/(:any)'] = 'pages/edit-page/$1/$2';
$route['project/(:any)/delete-page/(:any)'] = 'pages/delete-page/$1/$2';
$route['project/(:any)/(:any)/create-section'] = 'sections/create-section/$1/$2';
$route['project/(:any)/(:any)/delete-section/(:any)'] = 'sections/delete-section/$1/$2/$3';
$route['project/(:any)/(:any)/edit-section/(:any)'] = 'sections/edit-section/$1/$2/$3';
$route['project/(:any)/(:any)/(:any)/create-post'] = 'posts/create-post/$1/$2/$3';
$route['project/(:any)/(:any)/(:any)/(:any)/edit-post'] = 'posts/edit-post/$1/$2/$3/$4';
$route['project/(:any)/(:any)/(:any)/(:any)/remove-post'] = 'posts/remove-post/$1/$2/$3/$4';
$route['project/(:any)/(:any)/(:any)'] = 'posts/index/$1/$2/$3';
$route['project/(:any)/(:any)'] = 'sections/index/$1/$2';
$route['project/(:any)'] = 'pages/index/$1';
