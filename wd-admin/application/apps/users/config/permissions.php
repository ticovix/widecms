<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();
$CI->lang->load_app('users_permissions', 'users');

$permission['edit/.*']['edit'] = $CI->lang->line('users_edit_user');
$permission['edit/.*'][]['edit-permission'] = $CI->lang->line('users_edit_permission');
$permission['create']['create'] = $CI->lang->line('users_create_user');
$permission['delete']['delete'] = $CI->lang->line('users_remove_user');
