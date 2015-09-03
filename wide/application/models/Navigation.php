<?php

class Navigation_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    public function listNav($id_user){
        $this->db->select('menu.*, menu_access.status');
        $this->db->join('menu_access','menu_access.id_menu=menu.id');
        $this->db->group_by('menu.id');
        $stmt = $this->db->get_where('menu',['menu_access.id_user'=>$id_user,'menu.status'=>1]);

        $count = 0;
        foreach ($stmt->result_array() as $line) {
            $lin[$count] = $line;
            $this->db->select('menu.slug');
            $this->db->join('menu_access','menu_access.id_menu=menu.id','left_join');
            $this->db->group_by('menu.id');
            $this->db->get_where('menu', ['menu.subpage'=>$line['id'],'menu_access.id_user'=>$id_user]);
            
            foreach ($sub_stmt->result_array() as $arr) {
                $lin[$count]["sub_url"][] = $arr["slug"];
            }
            $count++;
        }
        return $lin;
    }
}