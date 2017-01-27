<?php

class History_model extends CI_Model
{

    private function dynamic_query($data)
    {
        if (isset($data['where'])) {
            $where = $data['where'];
            foreach ($where as $column => $value) {
                if (strpos('or ', $column)) {
                    $column = str_replace('or ', '', $column);
                    $this->db->or_where($column, $value);
                } else {
                    $this->db->where($column, $value);
                }
            }
        }
        $this->db->join('wd_users', 'wd_users.id=wd_history.fk_user');
    }

    public function read($data)
    {
        $this->dynamic_query($data);
        if (isset($data['limit']) && isset($data['offset'])) {
            $this->db->limit($data['limit'], $data['offset']);
        } elseif (isset($data['limit'])) {
            $this->db->limit($data['limit']);
        } else {
            $this->db->limit(30);
        }
        if (isset($data['order_by'])) {
            $this->db->order_by($data['order_by']);
        }
        $this->db->select('wd_history.*, wd_users.name, wd_users.last_name, wd_users.image, wd_users.login');
        $result = $this->db->get('wd_history')->result_array();
        $total = $this->total_history($data);
        $aux = array();
        if ($result) {
            foreach ($result as $history) {
                if (is_file('../wd-content/upload/' . $history['image'])) {
                    $history['profile_image'] = wd_base_url('wd-content/upload/' . $history['image']);
                } else {
                    $history['profile_image'] = base_url('assets/images/user.png');
                }
                $history['date'] = diff_date_today($history['date']);
                $aux[] = $history;
            }
        }

        return array('result' => $aux, 'total' => $total);
    }

    public function total_history($data)
    {
        $this->dynamic_query($data);
        $this->db->select('count(wd_history.id) total');
        $total = $this->db->get('wd_history')->row();
        if ($total) {
            return $total->total;
        } else {
            return 0;
        }
    }

    public function add($data)
    {
        return $this->db->insert('wd_history', $data);
    }

    public function remove($id)
    {
        $where = array(
            'id' => $id
        );
        return $this->db->delete('wd_history', $where);
    }
}