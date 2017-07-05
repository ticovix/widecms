<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();
$CI->lang->load_app('gallery_permissions', 'gallery');

$permission['/']['upload'] = $CI->lang->line('gallery_upload');
$permission['/']['view-files'] = $CI->lang->line('gallery_view_files');
$permission['/']['remove'] = $CI->lang->line('gallery_remove_file');
$permission['/']['edit'] = $CI->lang->line('gallery_edit_file');
