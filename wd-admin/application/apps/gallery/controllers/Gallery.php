<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gallery extends MY_Controller
{
    /*
     * Variável pública com extensões permitidas para upload
     */
    public $allowed_types = 'xls|xml|pdf|gif|jpg|jpeg|png|doc|docx|rar|3gp|7z|ace|ai|aif|aiff|amr|asf|asx|bmp|bup|cab|cbr|cda|cdl|cdr|chm|dat|divx|dmg|dss|dvf|dwg|eml|eps|flv|gz|hqx|htm|html|ifo|indd|iso|jar|js|lnk|log|m4a|m4b|m4p|m4v|mcd|mdb|mid|mov|mp2|mp3|mp4|mpeg|mpg|msi|ogg|pdf|pps|ps|psd|pst|ptb|pub|qbb|qbw|qxd|ram|rm|rmvb|rtf|svg|sea|ses|sit|sitx|ss|swf|tgz|thm|tif|tmp|torrent|ttf|txt|vcd|vob|wav|wmv|wps|xpi|xcf|zip|avi|sql|css';
    /*
     * Variável pública com o limite de usuários por página
     */
    public $limit = 18;

    public function __construct()
    {
        parent::__construct();
        $this->load->model_app('files_model');
    }
    /*
     * Método para criação de template da listagem de arquivos
     */

    public function index()
    {
        $this->lang->load_app(APP);
        $data = $this->apps->data_app();
        $search = $this->form_search();
        $files = $search['files'];

        if (check_method('upload')) {
            $this->include_components
                    ->main_js('plugins/dropzone/js/dropzone.js')
                    ->main_css('plugins/dropzone/css/dropzone.css');
        }

        $this->include_components
                ->main_js(array(
                    'plugins/fancybox/js/jquery.fancybox-buttons.js',
                    'plugins/fancybox/js/jquery.fancybox.pack.js',
                    'plugins/embeddedjs/ejs.js',
                ))
                ->main_css(array(
                    'plugins/fancybox/css/jquery.fancybox.css',
                    'plugins/fancybox/css/jquery.fancybox-buttons.css',
                ))
                ->app_js('js/script.js')
                ->app_css('css/style.css');

        $vars = array(
            'title' => $data['name'],
            'files' => $files
        );

        $this->load->template_app('index', $vars);
    }
    /*
     * Método para pesquisar arquivos
     */

    private function form_search()
    {
        $keyword = $this->input->get('search');
        $per_page = (int) $this->input->get('per_page') or 0;
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $this->form_validation->run();

        $limit = $this->limit;
        $files = $this->files_model->search($keyword, $limit, $per_page);
        $total = $this->files_model->search_total_rows($keyword);

        return array(
            'files' => $files,
            'total' => $total
        );
    }
    /*
     * Método para listar arquivos, acionado por js
     */

    public function files_list()
    {
        $per_page = (int) $this->input->get('per_page') or 0;
        $keyword = $this->input->get('search');
        $limit = $this->input->post('limit');
        $config = $this->input->post('config');
        $filter_extensions = null;
        $filter_thumbs = null;
        if (strpos($config, '{') !== false) {
            $json_config = json_decode(str_replace('\'', '"', $config));
            if (is_object($json_config)) {
                if (isset($json_config->extensions_allowed)) {
                    $extensions = $json_config->extensions_allowed;
                    $filter_extensions = explode(',', $extensions);
                }

                if (isset($json_config->image_thumbnails)) {
                    $thumbs = json_decode(str_replace('\'', '"', $json_config->image_thumbnails));
                    if ($thumbs) {
                        foreach ($thumbs as $thumb) {
                            $preffix = $thumb->preffix;
                            if (!empty($preffix)) {
                                $filter_thumbs[] = $preffix;
                            }
                        }
                    }
                }
            }
        }

        if (empty($limit)) {
            $limit = $this->limit;
        }

        $files = $this->files_model->search($keyword, $filter_extensions, $filter_thumbs, $limit, $per_page);
        $total = $this->files_model->search_total_rows($keyword, $filter_extensions, $filter_thumbs);
        $pagination = $this->pagination($total, $limit);

        echo json_encode(array('files' => $files, 'total' => $total, 'pagination' => $pagination));
    }
    /*
     * Método para criação de template da paginação
     */

    private function pagination($total, $limit)
    {
        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $limit;
        $config['base_url'] = base_url('apps/gallery/files-list');
        $config['attributes'] = array('class' => 'btn-page');
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['first_url'] = base_url('apps/gallery/files-list?per_page=0');
        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }
    /*
     * Método para enviar arquivo
     */

    public function upload()
    {
        try {
            $upload = false;
            $insert = false;
            $path = PATH_UPLOAD;
            if (!isset($_FILES['file']['name'])) {
                throw new Exception('Nenhum arquivo enviado');
            }

            $name = rand(0000000000, 9999999999);
            $config_upload = $this->input->post('config_upload');
            if (!empty($config_upload)) {
                $config_upload = json_decode(str_replace('\'', '"', $config_upload));
                $name = $this->create_file_unique($name, $config_upload);
            }

            $config['file_name'] = $name;
            $config['upload_path'] = PATH_UPLOAD;
            $config['file_ext_tolower'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['allowed_types'] = $this->allowed_types;
            if (isset($config_upload->extensions_allowed) && !empty($config_upload->extensions_allowed)) {
                $config['allowed_types'] = str_replace(array(','), array('|'), $config_upload->extensions_allowed);
            }

            $this->load->library('upload', $config);
            $upload = $this->upload->do_upload('file');
            if (!$upload) {
                throw new Exception($this->upload->display_errors('', ''));
            }

            $data = $this->upload->data();
            $file = $data['file_name'];
            $is_image = $data['is_image'];
            chmod($path . $file, 0755);

            $thumbs = array();
            if ($config_upload && $is_image) {
                $thumbs = $this->config_upload($config_upload, $file);
            }

            $insert = $this->files_model->insert_file($file, $thumbs);
            if (!$insert) {
                unlink($path . $file);
                throw new Exception('Houve um erro ao tentar enviar o arquivo para o banco de dados');
            }

            add_history('Inseriu um novo arquivo "' . $file . '"');
        } catch (Exception $e) {
            echo $e->getMessage();
            header("HTTP/1.0 404 Not Found");
        }
    }

    private function create_file_unique($file, $config)
    {
        $image_thumbnails = (isset($config->image_thumbnails) && strpos($config->image_thumbnails, '{') !== false) ? json_decode($config->image_thumbnails) : array();
        $exists = false;
        $new_file = $file;

        while ($exists == true) {
            $new_file = rand(0000000000, 9999999999);
            if (is_file($new_file)) {
                $exists = true;
            } elseif (count($image_thumbnails) > 0) {
                foreach ($image_thumbnails as $thumb) {
                    $preffix = $thumb->preffix;
                    if (is_file($preffix . $new_file)) {
                        $exists = true;
                    }
                }
            }
        }

        return $file;
    }

    private function config_upload($data, $file)
    {
        $image_resize = (isset($data->image_resize)) ? to_boolean($data->image_resize) : '';
        $image_y = (isset($data->image_y)) ? $data->image_y : '';
        $image_x = (isset($data->image_x)) ? $data->image_x : '';
        $image_ratio = (isset($data->image_ratio)) ? to_boolean($data->image_ratio) : '';
        $image_ratio_x = (isset($data->image_ratio_x)) ? $data->image_ratio_x : '';
        $image_ratio_y = (isset($data->image_ratio_y)) ? $data->image_ratio_y : '';
        $image_ratio_crop = (isset($data->image_ratio_crop)) ? to_boolean($data->image_ratio_crop) : '';
        $image_ratio_fill = (isset($data->image_ratio_fill)) ? to_boolean($data->image_ratio_fill) : '';
        $image_background_color = (isset($data->image_background_color)) ? $data->image_background_color : '';
        $image_convert = (isset($data->image_convert)) ? to_boolean($data->image_convert) : '';
        $image_text = (isset($data->image_text)) ? $data->image_text : '';
        $image_text_color = (isset($data->image_text_color)) ? $data->image_text_color : '';
        $image_text_background = (isset($data->image_text_background)) ? $data->image_text_background : '';
        $image_text_opacity = (isset($data->image_text_opacity)) ? $data->image_text_opacity : '';
        $image_text_background_opacity = (isset($data->image_text_background_opacity)) ? $data->image_text_background_opacity : '';
        $image_text_padding = (isset($data->image_text_padding)) ? $data->image_text_padding : '';
        $image_text_position = (isset($data->image_text_position)) ? $data->image_text_position : '';
        $image_text_direction = (isset($data->image_text_direction)) ? $data->image_text_direction : '';
        $image_text_x = (isset($data->image_text_x)) ? $data->image_text_x : '';
        $image_text_y = (isset($data->image_text_y)) ? $data->image_text_y : '';
        $image_thumbnails = (isset($data->image_thumbnails) && strpos($data->image_thumbnails, '{') !== false) ? json_decode($data->image_thumbnails) : array();
        $thumbs = array();
        $this->load->library('upload_verot');
        $upload = new Upload_verot(PATH_UPLOAD . $file);
        if (!empty($image_resize)) {
            $upload->image_resize = $image_resize;
        }

        if (!empty($image_y)) {
            $upload->image_y = $image_y;
        }

        if (!empty($image_x)) {
            $upload->image_x = $image_x;
        }

        if (!empty($image_ratio)) {
            $upload->image_ratio = $image_ratio;
        }

        if (!empty($image_ratio_x)) {
            $upload->image_ratio_x = $image_ratio_x;
        }

        if (!empty($image_ratio_y)) {
            $upload->image_ratio_y = $image_ratio_y;
        }

        if (!empty($image_ratio_crop)) {
            $upload->image_ratio_crop = $image_ratio_crop;
        }

        if (!empty($image_ratio_fill)) {
            $upload->image_ratio_fill = $image_ratio_fill;
        }

        if (!empty($image_background_color)) {
            $upload->image_background_color = $image_background_color;
        }

        if (!empty($image_convert)) {
            $upload->image_convert = $image_convert;
        }

        if (!empty($image_text)) {
            $upload->image_text = $image_text;
        }

        if (!empty($image_text_color)) {
            $upload->image_text_color = $image_text_color;
        }

        if (!empty($image_text_background)) {
            $upload->image_text_background = $image_text_background;
        }

        if (!empty($image_text_opacity)) {
            $upload->image_text_opacity = $image_text_opacity;
        }

        if (!empty($image_text_background_opacity)) {
            $upload->image_text_background_opacity = $image_text_background_opacity;
        }

        if (!empty($image_text_padding)) {
            $upload->image_text_padding = $image_text_padding;
        }

        if (!empty($image_text_position)) {
            $upload->image_text_position = $image_text_position;
        }

        if (!empty($image_text_direction)) {
            $upload->image_text_direction = $image_text_direction;
        }

        if (!empty($image_text_x)) {
            $upload->image_text_x = $image_text_x;
        }

        if (!empty($image_text_y)) {
            $upload->image_text_y = $image_text_y;
        }

        $upload->file_overwrite = true;
        $upload->file_auto_rename = false;
        $upload->process(PATH_UPLOAD);

        if (count($image_thumbnails) > 0) {
            foreach ($image_thumbnails as $thumb) {
                $preffix = $thumb->preffix;
                $width = $thumb->width;
                $height = $thumb->height;
                $crop = $thumb->crop;
                $upload->file_name_body_pre = $preffix;
                $upload->image_resize = true;
                $upload->image_x = $width;
                if (empty($height)) {
                    $upload->image_ratio_y = true;
                } else {
                    $upload->image_y = $height;
                }

                if (!empty($crop)) {
                    $upload->image_ratio_crop = $crop;
                }

                $upload->process(PATH_UPLOAD);
                if ($upload->processed) {
                    $thumbs[] = $upload->file_dst_name;
                }
            }
        }

        return $thumbs;
    }
    /*
     * Método para criar imagem de exibição com tamanho especifico
     */

    public function image($type, $file)
    {
        $file = urldecode($file);
        $path = PATH_UPLOAD;
        $is_image = array('jpg', 'png', 'gif', 'jpeg');
        $explode_file = explode('.', $file);
        $ext = array_pop($explode_file);
        $icon = false;
        if (!in_array($ext, $is_image)) {
            $file = 'assets/images/icons/' . $ext . '.png';
            if (!is_file($file)) {
                $file = 'assets/images/icons/other.png';
            }
            $icon = true;
        } else {
            $file = $path . $file;
        }

        $this->load->library('upload_verot');
        $file_tmp = new Upload_verot($file);
        if (!$file_tmp->uploaded) {
            $file = 'assets/images/icons/' . $ext . '.png';
            if (!is_file($file)) {
                $file = 'assets/images/icons/other.png';
            }

            $icon = true;
            $file_tmp = new Upload_verot($file);
        }

        header('Content-type: ' . $file_tmp->file_src_mime);
        if ($type == 'thumb') {
            $file_tmp->image_resize = true;
            $file_tmp->image_ratio_fill = true;
            $file_tmp->image_transparent_color = true;
            $file_tmp->image_y = 100;
            if ($icon) {
                $file_tmp->image_crop = '0 -50 0 -50';
                $file_tmp->image_x = 50;
            } else {
                $file_tmp->image_x = 150;
            }
        }

        echo $file_tmp->Process();
    }
    /*
     * Método para remover arquivo
     */

    public function delete()
    {
        $path = PATH_UPLOAD;
        $file = $this->input->post('file');
        $get_file = $this->files_model->file($file);
        if (!$get_file) {
            redirect_app();
        }

        if (is_file($path . $file)) {
            unlink($path . $file);
        }

        $this->files_model->delete($file);
        add_history('Removeu o arquivo "' . $file . '"');
        // Verifica a existencia de miniaturas e remove
        $thumbnails = $get_file['thumbnails'];
        if (!empty($thumbnails) && strpos($thumbnails, '[') !== false) {
            $thumbs = json_decode($thumbnails);
            if (count($thumbs) > 0) {
                foreach ($thumbs as $thumb) {
                    if (is_file($path . $thumb)) {
                        unlink($path . $thumb);
                    }
                }
            }
        }
    }
    /*
     * Método exibir dados de um arquivo
     */

    public function file()
    {
        $file = $this->input->post('file');
        $path = PATH_UPLOAD;
        $path_file = $path . $file;
        $filesize = filesize($path_file);
        $get_file = $this->files_model->file($file);
        $name = $get_file['name'];
        $thumbnails = $get_file['thumbnails'];
        if (strpos($thumbnails, '[') !== FALSE) {
            $thumbnails = json_decode($thumbnails);
        } else {
            $thumbnails = '';
        }

        echo json_encode(array('file' => $file, 'name' => $name, 'path_file' => wd_base_url('wd-content/upload/' . $file), 'filesize' => FileSizeConvert($filesize), 'thumbnails' => $thumbnails));
    }
    /*
     * Método para editar arquivo
     */

    public function edit_file()
    {
        $this->form_validation->set_rules('file', 'Arquivo', 'required|callback_verify_name');
        if ($this->form_validation->run()) {
            $path = PATH_UPLOAD;
            $file = $this->input->post('file');
            $new_file = $this->input->post('new_file');
            $name = $this->input->post('name');
            $infors_file = $this->files_model->file($file);
            $thumbs = array();
            if ($infors_file && strpos($infors_file['thumbnails'], '[') !== FALSE && $thumbnails = json_decode($infors_file['thumbnails'])) {
                foreach ($thumbnails as $thumb) {
                    $preffix = str_replace($file, '', $thumb);
                    $rename = \rename($path . $thumb, $path . $preffix . $new_file);
                    $thumbs[] = $preffix . $new_file;
                }
            }

            $rename = \rename($path . $file, $path . $new_file);
            if ($rename) {
                $data = array(
                    'file' => $file,
                    'new_file' => $new_file,
                    'name' => $name,
                    'thumbnails' => json_encode($thumbs)
                );
                $this->files_model->edit_file($data);
                if ($file != $new_file) {
                    add_history('Renomeou o arquivo de "' . $file . '" para "' . $new_file . '"');
                } else {
                    add_history('Alterou o título do arquivo para "' . $name . '"');
                }

                echo json_encode(array('message' => 'Arquivo editado com sucesso!', 'error' => 0, 'change_file' => $new_file));
            } else {
                echo json_encode(array('message' => 'Não foi possível renomear o arquivo, você não possui permissões suficiente.', 'error' => 1));
            }
        } else {
            echo json_encode(array('message' => validation_errors(), 'error' => 1));
        }
    }
    /*
     * Método para verificar existencia do arquivo
     */

    public function verify_name($name)
    {
        $path = PATH_UPLOAD;
        $file = $this->input->post('file');
        $explode_file = explode('.', $file);
        $ext = array_pop($explode_file);
        $new_file = $name . '.' . $ext;
        if ($file != $new_file) {
            if (is_file($path . $new_file)) {
                $this->form_validation->set_message('verify_name', 'Já existe um arquivo com esse nome.');

                return false;
            }
        }

        return true;
    }

    public function list_permissions()
    {
        $data = array();
        $data['app'] = check_app('gallery');
        $data['upload'] = check_method('upload', 'gallery');
        $data['view'] = check_method('view-files', 'gallery');

        echo json_encode($data);
    }

    public function list_lang()
    {
        $this->lang->load_app('gallery');

        echo json_encode($this->lang->language);
    }
}