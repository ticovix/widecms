<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Users_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function list_user_email($email)
    {
        return $this->db->get_where('wd_users', ['email' => $email])->row_array();
    }

    public function user_exists($user)
    {
        $stmt = $this->db->get_where('wd_users', ['login' => $user, 'status' => '1']);
        if ($stmt->num_rows() > 0) {
            return $stmt->row_array();
        } else {
            return false;
        }
    }

    public function user_recovery($user)
    {
        $this->db->where('limit_recovery_token>=now()');
        return $this->db->get_where('wd_users', ['login' => $user, 'status' => '1'])->row_array();
    }

    private function treat_user($user)
    {
        if (is_file('../wd-content/upload/' . $user['image'])) {
            $user['profile_image'] = wd_base_url('wd-content/upload/' . $user['image']);
        } else {
            $user['profile_image'] = base_url('assets/images/user.png');
        }

        return $user;
    }

    public function get_user($id)
    {
        $user = $this->db->get_where('wd_users', ['id' => $id, 'status' => '1'])->row_array();
        return $this->treat_user($user);
    }

    public function get_user_edit($login)
    {
        $user = $this->db->get_where('wd_users', ['login' => $login])->row_array();
        return $this->treat_user($user);
    }

    public function search($keyword = null, $total = null, $offset = null)
    {
        $this->db->like('name', $keyword);
        $this->db->or_like('login', $keyword);
        $this->db->or_like('email', $keyword);
        $this->db->limit($total, $offset);
        return $this->db->get('wd_users')->result_array();
    }

    public function search_total_rows($keyword = null, $turma = null, $evento = null)
    {
        $this->db->select('count(id) total');
        $this->db->like('name', $keyword);
        $this->db->or_like('login', $keyword);
        $this->db->or_like('email', $keyword);
        return $this->db->get('wd_users')->row()->total;
    }

    public function create($data)
    {
        $data = [
            'name' => $data['name'],
            'last_name' => $data['lastname'],
            'login' => $data['login'],
            'email' => $data['email'],
            'about' => $data['about'],
            'password' => $data['password'],
            'status' => $data['status'],
            'allow_dev' => $data['allow_dev'],
            'root' => $data['root']
        ];
        $create = $this->db->insert('wd_users', $data);
        if ($create) {
            return $this->db->insert_id();
        }
    }

    public function create_permissions($data)
    {
        return $this->db->insert_batch('wd_users_perm', $data);
    }

    public function delete_permissions($id_user)
    {
        return $this->db->delete('wd_users_perm', array('fk_user' => $id_user));
    }

    public function update($data)
    {
        $set = [
            'name' => $data['name'],
            'last_name' => $data['lastname'],
            'password' => $data['password'],
            'status' => $data['status'],
            'allow_dev' => $data['allow_dev'],
            'email' => $data['email'],
            'login' => $data['login'],
            'about' => $data['about'],
            'root' => $data['root']
        ];
        $where = ['login' => $data['login_old']];
        return $this->db->update('wd_users', $set, $where);
    }

    public function delete($users)
    {
        $this->db->where_in('id', $users);
        return $this->db->delete('wd_users');
    }

    public function change_mode($data)
    {
        return $this->db->update('wd_users', ['dev_mode' => $data['dev']], ['id' => $data['id_user']]);
    }

    public function change_recovery_token($token, $id_user)
    {
        $today = date('Y-m-d H:i:s');
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $dt->add(new DateInterval('P7D'));
        $date = $dt->format('Y-m-d H:i:s');
        return $this->db->update('wd_users', ['recovery_token' => $token, 'limit_recovery_token' => $date], ['id' => $id_user]);
    }

    public function change_pass_user($pass, $login)
    {
        return $this->db->update('wd_users', ['password' => $pass, 'recovery_token' => '', 'limit_recovery_token' => ''], ['login' => $login]);
    }
}