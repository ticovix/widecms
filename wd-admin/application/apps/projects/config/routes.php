<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Pages
$route['project/(:any)/create'] = 'pages/create/$1';
$route['project/(:any)/edit/(:any)'] = 'pages/edit/$2';
$route['project/(:any)/remove/(:any)'] = 'pages/remove/$2';
// Sections
$route['project/(:any)/(:any)/create'] = 'sections/create/$1/$2';
$route['project/(:any)/(:any)/import'] = 'sections/import/$1/$2';
$route['project/(:any)/(:any)/remove/(:any)'] = 'sections/remove/$3';
$route['project/(:any)/(:any)/edit/(:any)'] = 'sections/edit/$3';
$route['project/(:any)/(:any)/export/(:any)'] = 'sections/export/$3';
// Posts
$route['project/(:any)/(:any)/(:any)/create'] = 'posts/create/$1/$2/$3';
$route['project/(:any)/(:any)/(:any)/edit/(:any)'] = 'posts/edit/$1/$2/$3/$4';
$route['project/(:any)/(:any)/(:any)/remove'] = 'posts/remove/$1/$2/$3';
$route['project/(:any)/(:any)/(:any)/mod/(:any)(.*)'] = 'posts/$4$5';

$route['project/(:any)/(:any)/(:any)'] = 'posts/index/$1/$2/$3';
$route['project/(:any)/(:any)'] = 'sections/index/$1/$2';
$route['project/(:any)'] = 'pages/index/$1';
