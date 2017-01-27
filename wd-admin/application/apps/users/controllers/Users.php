<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Users extends MY_Controller
{
    public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->app()->model('users_model');
        $this->app_data = $this->apps->data_app();
    }

    public function index()
    {
        $this->lang->load_app(APP);
        $search = $this->form_search();
        $users = $search['users'];
        $total_rows = $search['total_rows'];
        $pagination = $this->pagination($total_rows);
        $this->data = array_merge($this->data, array(
            'title' => $this->app_data['name'],
            'users' => $users,
            'pagination' => $pagination,
            'total' => $total_rows,
            'search' => $this->input->get('search'),
            'check_create' => check_method('create'),
            'check_delete' => check_method('delete'),
            'check_edit' => check_method('edit'),
        ));
        $this->include_components->app_js('js/index.js');

        echo $this->load->app()->render('users.twig', $this->data);
    }

    private function form_search()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('search', 'Search', 'trim|required');
        $keyword = $this->input->get('search');
        $perPage = $this->input->get('per_page');
        $limit = $this->limit;
        $this->form_validation->run();
        $users = $this->users_model->search($keyword, $limit, $perPage);
        $total_rows = $this->users_model->search_total_rows($keyword);

        return array(
            'users' => $users,
            'total_rows' => $total_rows
        );
    }

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
        $config['first_tag_open'] = '</li>';
        $config['first_url'] = '?per_page=0';

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    public function create()
    {
        $this->lang->load_app('form');
        $permissions = $this->apps->list_apps_permissions();
        $this->form_create($permissions);
        $this->load->library('apps');
        $this->include_components->main_js('plugins/switchery/js/switchery.js')
                ->app_js('js/form.js')
                ->main_css('plugins/switchery/css/switchery.css');

        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_add_user'),
            'name_app' => $this->app_data['name'],
            'user_logged' => $this->user_data,
            'permissions' => $permissions
        ));

        echo $this->load->app()->render('form.twig', $this->data);
    }

    private function form_create($permissions)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'trim|required');
        $this->form_validation->set_rules('email', $this->lang->line(APP . '_label_email'), 'trim|required|is_unique[wd_users.email]|valid_email');
        $this->form_validation->set_rules('login', $this->lang->line(APP . '_label_login'), 'trim|required|is_unique[wd_users.login]|min_length[3]|alpha_numeric');
        $this->form_validation->set_rules('password', $this->lang->line(APP . '_label_password'), 'trim|required');

        if ($this->form_validation->run()) {
            $this->load->helper('passwordhash');
            $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $root = $this->input->post('root');
            $allow_dev = $this->input->post('allow_dev');
            if ($this->user_data['root'] != '1') {
                $root = 0;
                $allow_dev = 0;
            }

            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'login' => $this->input->post('login'),
                'password' => $PasswordHash->HashPassword($this->input->post('password')),
                'lastname' => $this->input->post('lastname'),
                'status' => $this->input->post('status'),
                'about' => $this->input->post('about'),
                'root' => $root,
                'allow_dev' => $allow_dev
            );
            $user = $this->users_model->create($data);
            if ($user) {
                $this->create_permissions($permissions, $user);
            }

            add_history($this->lang->line(APP . '_profile_create') . ' ' . $this->input->post('name') . ' ' . $this->input->post('lastname'));

            app_redirect('users');
        }
    }

    private function create_permissions($permissions, $id_user)
    {
        if (!$permissions) {
            return false;
        }

        $data = array();
        foreach ($permissions as $app) {
            $name = $app['name'];
            $dir_app = $app['app'];
            $is_check = (int) $this->input->post($dir_app);
            $permissions_app = (isset($app['permissions']) ? $app['permissions'] : array());
            $data[] = array(
                'fk_user' => $id_user,
                'app' => $dir_app,
                'page' => '',
                'method' => '',
                'status' => $is_check
            );
            foreach ($permissions_app as $page => $arr) {
                foreach ($arr as $key => $value) {
                    $method = $key;
                    $label = $value;
                    if (!is_array($label)) {
                        $is_check = (int) $this->input->post($dir_app . '-' . $method);
                        if ($page == '/') {
                            $page = '';
                        }

                        $data[] = array(
                            'fk_user' => $id_user,
                            'app' => $dir_app,
                            'page' => $page,
                            'method' => $method,
                            'status' => $is_check
                        );
                    } else {
                        foreach ($value as $method => $label) {
                            $page = $key;
                            $is_check = (int) $this->input->post($dir_app . '-' . $method);
                            $data[] = array(
                                'fk_user' => $id_user,
                                'app' => $dir_app,
                                'page' => $page,
                                'method' => $method,
                                'status' => $is_check
                            );
                        }
                    }
                }
            }
        }

        $this->users_model->create_permissions($data);
    }

    public function edit($login)
    {
        $this->load->library('form_validation');
        $this->lang->load_app('form');
        $user = $this->users_model->get_user_edit($login);
        if (!$user) {
            app_redirect('users');
        }

        $this->include_components->app_js('js/form.js');
        $permissions = $this->apps->list_apps_permissions();
        $this->form_edit($user, $permissions);

        $this->data = array_merge($this->data, array(
            'title' => $this->lang->line(APP . '_title_edit_user'),
            'name_app' => $this->app_data['name'],
            'id_user' => $user['id'],
            'name' => $user['name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'login' => $user['login'],
            'status' => $user['status'],
            'root' => $user['root'],
            'allow_dev' => $user['allow_dev'],
            'about' => $user['about'],
            'permissions' => $permissions
        ));

        echo $this->load->app()->render('form.twig', $this->data);
    }

    private function form_edit($user, $permissions)
    {
        $this->form_validation->set_rules('name', $this->lang->line(APP . '_label_name'), 'trim|required');
        if ($this->input->post('email') != $user['email']) {
            $this->form_validation->set_rules('email', $this->lang->line(APP . '_label_email'), 'trim|required|is_unique[wd_users.email]|valid_email');
        }

        if ($this->input->post('login') != $user['login']) {
            $this->form_validation->set_rules('login', $this->lang->line(APP . '_label_login'), 'trim|required|is_unique[wd_users.login]|min_length[3]|alpha_numeric');
        }

        if ($this->form_validation->run()) {
            $root = $user['root'];
            $allow_dev = $user['allow_dev'];
            if ($root) {
                $root = $this->input->post('root');
                $allow_dev = $this->input->post('allow_dev');
            }

            $data = array(
                'login_old' => $user['login'],
                'name' => $this->input->post('name'),
                'lastname' => $this->input->post('lastname'),
                'status' => $this->input->post('status'),
                'email' => $this->input->post('email'),
                'login' => $this->input->post('login'),
                'about' => $this->input->post('about'),
                'root' => $root,
                'allow_dev' => $allow_dev,
            );
            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->load->helper('passwordhash');
                $PasswordHash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $data['password'] = $PasswordHash->HashPassword($password);
            } else {
                $data['password'] = $user['password'];
            }

            $this->users_model->update($data);
            $this->users_model->delete_permissions($user['id']);
            $this->create_permissions($permissions, $user['id']);
            add_history($this->lang->line(APP . '_profile_update') . ' ' . $this->input->post('name'));

            app_redirect('users');
        }
    }

    public function delete()
    {
        $del = $this->input->post('del');
        if ($del > 1) {
            $this->users_model->delete($del);
        }

        app_redirect('users');
    }

    public function dev_mode()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('dev', 'Dev', 'required');
        if ($this->form_validation->run()) {
            $dev = $this->input->post('dev');
            $this->users_model->change_mode(['dev' => $dev, 'id_user' => $this->user_data['id']]);
        }
    }

    public function profile($login)
    {
        $this->load->library('form_validation');
        $this->lang->load_app('profile');
        $user = $this->users_model->get_user_edit($login);
        if (!$user) {
            redirect();
        }

        $offset = (int) $this->input->get('per_page');
        $this->include_components->app_css('css/profile.css');
        $history = read_history(array(
            'limit' => 10,
            'offset' => $offset,
            'order_by' => 'id DESC',
            'where' => array(
                'wd_users.login' => $login
            )
        ));

        $this->data = array_merge($this->data, array(
            'title' => $user['name'] . ' ' . $user['last_name'],
            'name_app' => $this->app_data['name'],
            'name' => $user['name'],
            'login' => $user['login'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'profile_image' => $user['profile_image'],
            'about' => $user['about'],
            'history' => $history,
            'total_history' => $history['total'],
            'pagination' => $this->pagination_history($history['total'])
        ));

        echo $this->load->app()->render('profile.twig', $this->data);
    }

    private function pagination_history($total_rows)
    {
        $this->load->library('pagination');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
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

        return $this->pagination->create_links();
    }
}