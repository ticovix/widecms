<?php

class Gallery_dashboard
{
    public $limit = 9;

    public function __construct()
    {
        $CI = & get_instance();
        $CI->load->library('form_validation');
        $search = $this->form_search();
        $files = $search['files'];
        if (check_method('upload', 'gallery')) {
            $CI->include_components->main_js(array(
                'plugins/dropzone/js/dropzone.js'
            ))->main_css(array(
                'plugins/dropzone/css/dropzone.css',
            ));
        }

        $CI->include_components->main_js(array(
                    'plugins/fancybox/js/jquery.fancybox-buttons.js',
                    'plugins/fancybox/js/jquery.fancybox.pack.js',
                    'plugins/embeddedjs/ejs.js'))
                ->app_js('js/scripts_dashboard.js', 'gallery')
                ->main_css(array(
                    'plugins/fancybox/css/jquery.fancybox.css',
                    'plugins/fancybox/css/jquery.fancybox-buttons.css',))
                ->app_css('css/style.css', 'gallery');

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

        echo $CI->load->app('gallery')->render('dashboard.twig', $vars);
    }
    /*
     * Método para pesquisar arquivos
     */

    private function form_search()
    {
        $CI = & get_instance();
        $keyword = $CI->input->get('search');
        $per_page = (int) $CI->input->get('per_page') or 0;
        $CI->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $CI->form_validation->run();

        $limit = $this->limit;
        $CI->load->app('gallery')->model('files_model');
        $files = $CI->files_model->search($keyword, $limit, $per_page);
        $total = $CI->files_model->search_total_rows($keyword);

        return array(
            'files' => $files,
            'total' => $total
        );
    }
}