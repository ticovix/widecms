<?php

class Crud_model extends CI_Model {

    public function create($table, $set) {
        return $this->db->insert($table, $set);
    }

    public function dynamic_query($where = null, $join = null) {

        if (is_array($join) && count($join) > 0) {
            foreach ($join as $table => $where) {
                if (is_array($where)) {
                    $this->db->join($table, $where[0], $where[1]);
                } else {
                    $this->db->join($table, $where);
                }
            }
        }
        if (is_array($where) && count($where) > 0) {
            foreach ($where as $column => $value) {
                if (strpos('or ', $column)) {
                    $column = str_replace('or ', '', $column);
                    $this->db->or_where($column, $value);
                } else {
                    $this->db->where($column, $value);
                }
            }
        }
    }

    public function read($table, $select = null, $where = null, $order = null, $join = null, $limit = null, $offset = 0) {
        $this->dynamic_query($where, $join);
        if ($select) {
            $this->db->select($select);
        }
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        if ($order) {
            $this->db->order_by($order);
        }
        $get = $this->db->get($table);

        $this->dynamic_query($where, $join);
        $this->get_total_read($table);

        return $get;
    }

    public function get_total_read($table) {
        $this->db->select('count(id) as total');
        $row = $this->db->get($table)->row();
        if ($row) {
            $total = $row->total;
        } else {
            $total = 0;
        }
        return $total;
    }

    public function update($table, $set, $where) {
        return $this->db->update($table, $set, $where);
    }

    public function delete($table, $where) {
        return $this->db->remove($table, $where);
    }

}
