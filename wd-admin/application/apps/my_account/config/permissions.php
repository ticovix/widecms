<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();
$CI->lang->load_app('my_account_permissions', 'my_account');
$permission['/']['edit'] = $CI->lang->line('my_account_edit_profile');
