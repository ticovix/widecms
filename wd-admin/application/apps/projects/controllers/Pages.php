<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages extends MY_Controller
{
    private $path_view_project = '';
    /*
     * Variável pública com o limite de páginas
     */
    public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model_app('pages_model');
        $this->path_view_project = 'application/' . APP_PATH . 'views/project/';
        $this->data = $this->apps->data_app();
    }
    /*
     * Método para listar as páginas
     */

    public function index()
    {
        $this->lang->load_app('pages/pages');
        $project = get_project();
        $dev_mode = $this->data_user['dev_mode'];
        $search = $this->form_search($project, $dev_mode);
        $pages = $search['pages'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);

        $vars = array(
            'title' => $project['name'],
            'name_app' => $this->data['name'],
            'pages' => $this->includeSections($pages),
            'pagination' => $pagination,
            'total' => $total_rows,
            'project' => $project
        );
        if ($dev_mode) {
            // Template modo desenvolvedor
            $this->load->template_app('dev-pages/index', $vars);
        } else {
            // Template modo cliente
            $this->load->template_app('projects/project', $vars);
        }
    }
    /*
     * Método com pesquisa da listagem de páginas
     */

    private function form_search($project, $dev_mode)
    {
        $this->form_validation->set_rules('search', $this->lang->line(APP . '_field_search'), 'trim|required');
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

    private function pagination($total_rows)
    {
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

    private function includeSections($pages)
    {
        $this->load->model_app('sections_model');
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

    public function create()
    {
        func_only_dev();
        $this->lang->load_app('pages/form');
        $project = get_project();
        $this->form_create($project);
        $vars = array(
            'title' => $this->lang->line(APP . '_title_add_page'),
            'name_app' => $this->data['name'],
            'project' => $project,
            'name' => '',
            'status' => ''
        );

        $this->load->template_app('dev-pages/form', $vars);
    }
    /*
     * Método para criar página
     */

    public function form_create($project)
    {
        try {
            $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'required|is_unique[wd_pages.name]');
            $this->form_validation->set_rules('status', $this->lang->line(APP . '_label_status'), 'required|integer');
            if ($this->form_validation->run()) {
                $name = $this->input->post('name');
                $status = $this->input->post('status');
                $slug = slug($name);
                $dir_page = $this->path_view_project . $project['directory'] . '/';
                if (!is_writable($dir_page)) {
                    throw new Exception(printf($this->lang->line(APP . '_not_allowed_create'), $dir_page));
                }

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
                setError(validation_errors());
            }
        } catch (Exception $e) {
            setError($e->getMessage());
        }
    }
    /*
     * Método para exibir template de edição da página
     */

    public function edit($slug_page)
    {
        func_only_dev();
        $this->lang->load_app('pages/form');
        $project = get_project();
        $page = $this->pages_model->get_page($slug_page);
        if (!$project or ! $page) {
            redirect_app('project/' . $project['slug']);
        }
        $this->form_edit($project, $page);
        $vars = array(
            'title' => $this->lang->line(APP . '_title_edit_page'),
            'name_app' => $this->data['name'],
            'project' => $project,
            'name' => $page['name'],
            'status' => $page['status']
        );

        $this->load->template_app('dev-pages/form', $vars);
    }
    /*
     * Método para editar a página
     */

    public function form_edit($project, $page)
    {
        try {
            $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'required');
            $this->form_validation->set_rules('status', $this->lang->line(APP . '_label_status'), 'required|integer');
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            if ($page['name'] != $name) {
                // Verifica se houve alterações no nome da página e se houer, verifica a existência no banco de dados
                $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'required|is_unique[wd_pages.name]');
            }

            if ($this->form_validation->run()) {
                $slug = slug($name);
                $dir_project = $this->path_view_project . $project['directory'] . '/';
                if ($slug != $page['slug']) {
                    //Se o slug da página estiver diferente do slug atual
                    if (is_dir($dir_project . $slug)) {
                        // Se já existir uma pasta com o slug atual
                        throw new Exception($this->lang->line(APP . '_folder_exists'));
                    }

                    if (!is_writable($dir_project . $page['directory'])) {
                        // Se a nova pasta não puder ser renomeada
                        throw new Exception($this->lang->line(APP . '_not_allowed_create'));
                    }

                    rename($dir_project . $page['directory'], $dir_project . $slug);
                }
                // Caso não haja erros, os dados são alterados no banco de dados
                $data = array(
                    'name' => $name,
                    'status' => $status,
                    'slug' => $slug,
                    'id_page' => $page['id'],
                );
                $this->pages_model->edit($data);

                redirect_app('project/' . $project['slug']);
            } else {
                setError(validation_errors());
            }
        } catch (Exception $e) {
            setError($e->getMessage());
        }
    }
    /*
     * Método para remover página
     */

    public function remove($slug_page)
    {
        func_only_dev();
        $this->lang->load_app('pages/remove');
        $project = get_project();
        $page = $this->pages_model->get_page($slug_page);
        if (!$page or ! $project) {
            redirect_app();
        }
        $this->form_remove($page, $project);
        $vars = array(
            'title' => sprintf($this->lang->line(APP . '_title_remove_page'), $page['name']),
            'name_app' => $this->data['name'],
            'project' => $project,
            'page' => $page
        );

        $this->load->template_app('dev-pages/remove', $vars);
    }

    private function form_remove($page, $project)
    {
        $this->form_validation->set_rules('password', $this->lang->line(APP . '_label_password'), 'required|callback_verify_password');
        $this->form_validation->set_rules('page', $this->lang->line(APP . '_label_page'), 'trim|required|integer');
        if ($this->form_validation->run()) {
            $slug_project = $project['slug'];
            $slug_page = $page['slug'];

            if ($page['id'] == $this->input->post('page')) {
                $dir_project = $project['directory'];
                $dir_page = $page['directory'];
                $id_page = $page['id'];
                $remove = $this->pages_model->remove($id_page);
                if ($remove) {
                    // Se a página for removida do banco de dados, todos os arquivos incluindo a pasta são removidos.
                    forceRemoveDir($this->path_view_project . $dir_project . '/' . $dir_page);
                }

                redirect_app('project/' . $slug_project);
            } else {
                redirect_app('project/' . $slug_project . '/' . $slug_page);
            }
        }
    }
    /*
     * Método para verificar senha
     */

    public function verify_password($v_pass)
    {
        $pass_user = $this->data_user['password'];
        // Inicia helper PasswordHash
        $this->load->helper('passwordhash');
        $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        // Verifica se a senha está errada
        if (!$PasswordHash->CheckPassword($v_pass, $pass_user)) {
            $this->form_validation->set_message('verify_password', $this->lang->line(APP . '_incorrect_password'));

            return false;
        }

        return true;
    }
}