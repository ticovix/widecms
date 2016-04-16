<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages extends MY_Controller {

    private $path_view_project = '';
    /*
     * Variável pública com o limite de páginas
     */
    public $limit = 10;

    public function __construct() {
        parent::__construct();
        $this->load->model('pages_model');
        $this->path_view_project = 'application/' . APP_PATH . 'views/project/';
    }

    /*
     * Método para listar as páginas
     */

    public function index() {
        $project = get_project();
        $dev_mode = $this->data_user['dev_mode'];
        $limit = 10;
        $search = $this->form_search($project, $dev_mode);
        $pages = $search['pages'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);

        add_js([
            APP_PATH . 'project/js/list-pages.js'
        ]);
        $vars = [
            'title' => $project['name'],
            'pages' => $this->includeSections($pages),
            'pagination' => $pagination,
            'total' => $total_rows,
            'project' => $project
        ];
        if ($dev_mode) {
            // Template modo desenvolvedor
            $this->load->template('dev-project/pages', $vars);
        } else {
            // Template modo cliente
            $this->load->template('project/index', $vars);
        }
    }

    /*
     * Método com pesquisa da listagem de páginas
     */

    private function form_search($project, $dev_mode) {
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $this->form_validation->run();
        $pages = $this->pages_model->search($project['id'], $dev_mode, $keyword, $this->limit, $perPage);
        $total_rows = $this->pages_model->search_total_rows($project['id'], $dev_mode, $keyword);
        return array(
            'pages' => $pages,
            'total_rows' => $total_rows
        );
    }

    /*
     * Método para criar template da páginação da listagem de páginas
     */

    private function pagination($total_rows) {
        $this->load->library('pagination');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $this->limit;
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
        $config['first_url'] = '?per_page=0';
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /*
     * Método para incluir seções da página na listagem das páginas (usado somente no modo cliente)
     */

    private function includeSections($pages) {
        $this->load->model('sections_model');
        if (count($pages)) {
            foreach ($pages as $page) {
                $page['sections'] = $this->sections_model->list_sections($page['id']);
                $arr[] = $page;
            }
            return $arr;
        }
    }

    /*
     * Método para exibir template para criação da página
     */

    public function create() {
        func_only_dev();
        $project = get_project();
        $this->form_create($project);
        $vars = [
            'title' => 'Nova página',
            'project' => $project,
            'name' => '',
            'status' => ''
        ];
        $this->load->template('dev-project/form-page', $vars);
    }

    /*
     * Método para criar página
     */

    public function form_create($project) {
        $this->form_validation->set_rules('name', 'Nome', 'required|is_unique[wd_pages.name]');
        $this->form_validation->set_rules('status', 'Status', 'required|integer');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $slug = slug($name);
            $dir_page = $this->path_view_project . $project['slug'] . '/';
            //Tenta criar o novo diretório
            if (is_writable($dir_page)) {
                // Caso o diretório seja criado, os valores são inseridos no banco de dados
                $id_user = $this->data_user['id'];
                $data = [
                    'name' => $name,
                    'status' => $status,
                    'slug' => $slug,
                    'directory' => $slug,
                    'id_project' => $project['id'],
                    'id_user' => $id_user
                ];
                mkdir($dir_page . $slug);
                $this->pages_model->create($data);
                redirect_app('project/' . $project['slug']);
            } else {
                setError('createPage', 'Você não possui privilégios suficiente para criar um diretório em '.$dir_page.'.');
            }
        } else {
            setError('createPage', validation_errors());
        }
    }

    /*
     * Método para exibir template de edição da página
     */

    public function edit($slug_project, $slug_page) {
        func_only_dev();
        $project = get_project();
        $page = $this->pages_model->get_page($slug_page);
        if (!$project or ! $page) {
            redirect_app('project/' . $project['slug']);
        }
        $this->form_edit($project, $page);
        $vars = [
            'title' => 'Editar página',
            'project' => $project,
            'name' => $page['name'],
            'status' => $page['status']
        ];
        $this->load->template('dev-project/form-page', $vars);
    }

    /*
     * Método para editar a página
     */

    public function form_edit($project, $page) {
        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|integer');
        if ($page['name'] != $this->input->post('name')) {
            // Verifica se houve alterações no nome da página e se houer, verifica a existência no banco de dados
            $this->form_validation->set_rules('name', 'Nome', 'required|is_unique[wd_pages.name]');
        }
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $slug = slug($name);
            $dir_project = $this->path_view_project . $project['slug'] . '/';

            if ($slug != $page['slug']) {
                //Se o slug da página estiver diferente do slug atual
                if (is_dir($dir_project . $slug)) {
                    // Se já existir uma pasta com o slug atual
                    setError('editPage', 'Não foi possível renomear a página, esse nome (diretório) já existe.');
                } elseif (!\rename($dir_project . $page['slug'], $dir_project . $slug)) {
                    // Se a nova pasta não for renomeada
                    setError('editPage', 'Não foi possível renomear a página, você não possui privilégios.');
                    $name = $page['name'];
                }
            }
            if (!hasError()) {
                // Caso não haja erros, os dados são alterados no banco de dados
                $data = [
                    'name' => $name,
                    'status' => $status,
                    'slug' => $slug,
                    'id_page' => $page['id'],
                ];
                $this->pages_model->edit($data);
                redirect_app('project/' . $project['slug']);
            }
        } else {
            setError('editPage', validation_errors());
        }
    }

    /*
     * Método para remover página
     */

    public function remove($slug_project, $slug_page) {
        func_only_dev();
        $project = get_project();
        $page = $this->pages_model->get_page($slug_page);
        if ($page) {
            $dir_project = $project['directory'];
            $dir_page = $page['directory'];
            $id_page = $page['id'];
            if ($this->pages_model->remove($id_page)) {
                // Se a página for removida do banco de dados, todos os arquivos incluindo a pasta são removidos.
                forceRemoveDir($this->path_view_project . $dir_project . '/' . $dir_page);
            }
            redirect_app('project/' . $slug_project);
        } else {
            redirect_app('project/' . $slug_project . '/' . $slug_page);
        }
    }

}
