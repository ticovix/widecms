<?php

class History_model extends CI_Model {
    public function read($data){
        if(isset($data['limit']) && isset($data['offset'])){
            $this->db->limit($data['limit'], $data['offset']);
        }elseif(isset($data['limit'])){
            $this->db->limit($data['limit']);
        }else{
            $this->db->limit(30);
        }
        if(isset($data['order_by'])){
            $this->db->order_by($data['order_by']);
        }
        return $this->db->get('wd_history', $data);
    }
    public function add($data){
        return $this->db->insert('wd_history', $data);
    }
    public function remove($id){
        $where = array(
            'id' => $id
        );
        return $this->db->delete('wd_history', $where);
    }
    
}