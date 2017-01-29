<?php

class Gallery_widget
{
    public $limit = 9;

    public function dashboard()
    {
        $CI = & get_instance();
        $CI->load->library('form_validation');
        $CI->load->app('gallery')->model('files_model');
        $files = $CI->files_model->search('', $this->limit);
        if (check_method('upload', 'gallery')) {
            $CI->include_components->main_js(array(
                'plugins/dropzone/js/dropzone.js'
            ))->main_css(array(
                'plugins/dropzone/css/dropzone.css',
            ));
        }

        $CI->include_components->app_css('css/style.css', 'gallery');

        $vars = array(
            'title' => 'Galeria',
            'files' => $files,
            'lang' => $CI->lang,
            'search' => $CI->input->get('search'),
            'check_upload' => check_method('upload', 'gallery'),
            'check_view' => check_method('view-files', 'gallery'),
            'check_edit' => check_method('edit', 'gallery'),
            'check_remove' => check_method('remove', 'gallery'),
        );

        return $CI->load->app('gallery')->render('dashboard.twig', $vars);
    }
}