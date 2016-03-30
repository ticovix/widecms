<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('pages_model');
    }

    public function index() {
        $project = $this->get_project();
        $dev_mode = $this->data_user['dev_mode'];
        /* Form search */
        $this->form_validation->set_rules('search', 'Pesquisa', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $this->form_validation->run();
        $limit = 10;
        $pages = $this->pages_model->search($project['id'], $dev_mode, $keyword, $limit, $perPage);
        $total_rows = $this->pages_model->searchTotalRows($project['id'], $dev_mode, $keyword);
        /* End form search */

        /* Pagination */
        $this->load->library('pagination');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $limit;
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
        $config['first_tag_open'] = '</li>';
        $config['first_url'] = '?per_page=0';

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        /* End Pagination */

        add_js([
            'view/project/js/list-pages.js'
        ]);
        $vars = [
            'title' => $project['name'],
            'pages' => $this->includeSections($pages),
            'pagination' => $pagination,
            'total' => $total_rows,
            'project' => $project
        ];
        if ($dev_mode) {
            $this->load->template('dev-project/pages', $vars);
        } else {
            $this->load->template('project/index', $vars);
        }
    }

    protected function includeSections($pages) {
        $this->load->model('sections_model');
        if (count($pages)) {
            foreach ($pages as $page) {
                $page['sections'] = $this->sections_model->listSections($page['id']);
                $arr[] = $page;
            }
            return $arr;
        }
    }

    public function create_page($project) {
        $this->func_only_dev();
        $project = $this->get_project();
        $this->form_validation->set_rules('name', 'Nome', 'required|is_unique[wd_pages.name]');
        $this->form_validation->set_rules('status', 'Status', 'required|integer');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $slug = slug($name);
            if (@mkdir(getcwd() . '/application/views/project/' . $project['slug'] . '/' . $slug)) {
                $id_user = $this->data_user['id'];
                $data = [
                    'name' => $name,
                    'status' => $status,
                    'slug' => $slug,
                    'directory' => $slug,
                    'id_project' => $project['id'],
                    'id_user' => $id_user
                ];
                $this->pages_model->createPage($data);
                redirect('project/' . $project['slug']);
            } else {
                setError('createPage', 'Não foi possível criar o diretório, você não possui privilégios suficiente.');
            }
        } else {
            setError('createPage', validation_errors());
        }

        $vars = [
            'title' => 'Nova página',
            'project' => $project,
            'name' => '',
            'status' => ''
        ];
        $this->load->template('dev-project/form-page', $vars);
    }

    public function edit_page($project, $page) {
        $this->func_only_dev();
        $project = $this->get_project();
        $page = $this->pages_model->getPage($page);
        if (!$project or ! $page) {
            redirect('project/' . $project['slug']);
        }

        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|integer');
        if ($project['name'] != $this->input->post('name')) {
            $this->form_validation->set_rules('name', 'Nome', 'required|is_unique[wd_pages.name]');
        }
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $status = $this->input->post('status');
            $slug = slug($name);
            $dir_project = getcwd() . '/application/views/project/' . $project['slug'] . '/';
            if ($slug != $project['slug'] && !is_dir($dir_project . $slug)) {
                if (!@rename($dir_project . $project['slug'], $dir_project . $slug)) {
                    setError('editPage', 'Não foi possível renomear a página, esse nome já existe ou você não possui privilégios suficiente.');
                    $name = $page['name'];
                }
            }

            $id_user = $this->data_user['id'];
            $data = [
                'name' => $name,
                'status' => $status,
                'slug' => $slug,
                'id_project' => $project['id'],
                'id_user' => $id_user
            ];
            $this->pages_model->createPage($data);
            redirect('project/' . $project['slug'] . '/' . $page['slug']);
        } else {
            setError('editPage', validation_errors());
        }

        $vars = [
            'title' => 'Editar página',
            'project' => $project,
            'name' => $page['name'],
            'status' => $page['status']
        ];
        $this->load->template('dev-project/form-page', $vars);
    }

    public function delete_page($slug_project, $slug_page) {
        $this->func_only_dev();
        $project = $this->get_project();
        $page = $this->pages_model->getPage($slug_page);
        if ($project && $page) {
            $dir_project = $project['directory'];
            $database = $project['database'];
            $dir_page = $page['directory'];
            $id_page = $page['id'];
            if ($this->pages_model->removePage($id_page, $database)) {
                forceRemoveDir(getcwd() . '/application/views/project/' . $dir_project . '/' . $dir_page);
            }
            redirect('project/' . $slug_project);
        } else {
            redirect('project/' . $slug_project . '/' . $slug_page);
        }
    }

}
