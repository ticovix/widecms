<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gallery extends MY_Controller {
    /*
     * Variável pública com extensões permitidas para upload
     */

    public $allowed_types = 'xls|xml|pdf|gif|jpg|jpeg|png|doc|docx|rar|3gp|7z|ace|ai|aif|aiff|amr|asf|asx|bmp|bup|cab|cbr|cda|cdl|cdr|chm|dat|divx|dmg|dss|dvf|dwg|eml|eps|flv|gz|hqx|htm|html|ifo|indd|iso|jar|js|lnk|log|m4a|m4b|m4p|m4v|mcd|mdb|mid|mov|mp2|mp3|mp4|mpeg|mpg|msi|ogg|pdf|pps|ps|psd|pst|ptb|pub|qbb|qbw|qxd|ram|rm|rmvb|rtf|svg|sea|ses|sit|sitx|ss|swf|tgz|thm|tif|tmp|torrent|ttf|txt|vcd|vob|wav|wmv|wps|xpi|xcf|zip|avi|sql|css';
    /*
     * Variável pública com o limite de usuários por página
     */
    public $limit = 18;

    public function __construct() {
        parent::__construct();
        $this->load->model_app('files_model');
    }

    /*
     * Método para criação de template da listagem de arquivos
     */

    public function index() {
        $search = $this->form_search();
        $files = $search['files'];
        $total = $search['total'];
        if (check_method('upload')) {
            add_js(array(
                '../../../../assets/plugins/dropzone/js/dropzone.js'
            ));
            add_css(array(
                '../../../../assets/plugins/dropzone/css/dropzone.css',
            ));
        }
        add_js(array(
            '../../../../assets/plugins/fancybox/js/jquery.fancybox-buttons.js',
            '../../../../assets/plugins/fancybox/js/jquery.fancybox.pack.js',
            '../../../../assets/plugins/embeddedjs/ejs.js',
            'js/script.js'
        ));
        add_css(array(
            '../../../../assets/plugins/fancybox/css/jquery.fancybox.css',
            '../../../../assets/plugins/fancybox/css/jquery.fancybox-buttons.css',
            'css/style.css'
        ));
        $vars = array(
            'title' => 'Galeria',
            'files' => $files
        );
        $this->load->template('index', $vars);
    }

    /*
     * Método para pesquisar arquivos
     */

    private function form_search() {
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

    public function files_list() {
        $per_page = (int) $this->input->get('per_page') or 0;
        $keyword = $this->input->get('search');
        $limit = $this->input->post('limit');
        if (empty($limit)) {
            $limit = $this->limit;
        }
        $files = $this->files_model->search($keyword, $limit, $per_page);
        $total = $this->files_model->search_total_rows($keyword);
        $pagination = $this->pagination($total, $limit);
        echo json_encode(array('files' => $files, 'total' => $total, 'pagination' => $pagination));
    }

    /*
     * Método para criação de template da paginação
     */

    private function pagination($total, $limit) {
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

    public function upload() {
        $upload = false;
        $insert = false;
        $path = PATH_UPLOAD;
        if (isset($_FILES['file']['name'])) {
            $name = $_FILES['file']['name'];
            $config['file_name'] = preg_replace("/[^a-z0-9_\s-\._\-]/", "", $name);
            $config['upload_path'] = PATH_UPLOAD;
            $config['file_ext_tolower'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['allowed_types'] = $this->allowed_types;
            $this->load->library('upload', $config);
            $upload = $this->upload->do_upload('file');
            if ($upload) {
                $data = $this->upload->data();
                $file = $data['file_name'];
                chmod($path . $file, 0755);
                $insert = $this->files_model->insert_file($file);
                add_history('Inseriu um novo arquivo "' . $file . '"');
            }
        }
        if (!$upload) {
            if (isset($config)) {
                echo $this->upload->display_errors('', '');
            } else {
                echo 'Houve um erro ao tentar enviar o arquivo.';
            }
            header("HTTP/1.0 404 Not Found");
        } elseif (!$insert) {
            echo 'Houve um erro ao tentar enviar o arquivo para o banco de dados.';
            if (isset($data)) {
                unlink($path . $file);
            }
            header("HTTP/1.0 404 Not Found");
        }
    }

    /*
     * Método para criar imagem de exibição com tamanho especifico
     */

    public function image($type = false, $file = false) {
        if ($type && $file) {
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
                if ($icon) {
                    $file_tmp->image_crop = '0 -50 0 -50';
                    $file_tmp->image_x = 50;
                } else {
                    $file_tmp->image_x = 150;
                }
                $file_tmp->image_y = 100;
                $file_tmp->image_ratio_fill = true;
            }
            echo $file_tmp->Process();
        }
    }

    /*
     * Método para remover arquivo
     */

    public function delete() {
        $file = $this->input->post('file');
        $path = PATH_UPLOAD;
        @unlink($path . $file);
        $this->files_model->delete($file);
        add_history('Removeu o arquivo "' . $file . '"');
    }

    /*
     * Método exibir dados de um arquivo
     */

    public function file() {
        $file = $this->input->post('file');
        $path = PATH_UPLOAD;
        $path_file = $path . $file;
        $filesize = filesize($path_file);
        $get_file = $this->files_model->file($file);
        $name = $get_file['name'];
        echo json_encode(array('file' => $file, 'name' => $name, 'path_file' => wd_base_url('wd-content/upload/' . $file), 'filesize' => FileSizeConvert($filesize)));
    }

    /*
     * Método para editar arquivo
     */

    public function edit_file() {
        $this->form_validation->set_rules('name', 'Nome', 'required|callback_verify_name');
        $this->form_validation->set_rules('file', 'Arquivo', 'required');
        if ($this->form_validation->run()) {
            $path = PATH_UPLOAD;
            $file = $this->input->post('file');
            $new_file = $this->input->post('new_file');
            $name = $this->input->post('name');
            $rename = \rename($path . $file, $path . $new_file);
            if ($rename) {
                $data = array(
                    'file' => $file,
                    'new_file' => $new_file,
                    'name' => $name
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

    public function verify_name($name) {
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

}
