<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posts_model extends CI_Model
{

    private function filter_search($form_search, $data, $keyword = null)
    {
        if (count($form_search) > 0) {
            foreach ($form_search as $field) {
                $column = $field['column'];
                $value = $field['value'];
                $type_column = $field['type_column'];
                $type = $field['type'];
                if (!empty($value)) {
                    if ($type == 'select') {
                        $this->db->where($column, $value);
                    } elseif ($type == 'checkbox') {
                        $this->db->like($column, '"' . $value . '"');
                    } elseif ($type_column == 'date' || $type_column == 'datetime') {
                        $type_date = $field['type_date'];

                        if ($type_date == 'of') {
                            $this->db->where($column . ' >=', $value);
                        } elseif ($type_date == 'until') {
                            $this->db->where($column . ' <=', $value);
                        }
                    } else {
                        $value_type = $field['value_type'];
                        switch ($value_type) {
                            case 'equals':
                                $this->db->where($column, $value);
                                break;
                            case 'greater':
                                $this->db->where($column . ' >=', $value);
                                break;
                            case 'smaller':
                                $this->db->where($column . ' <=', $value);
                                break;
                            case 'before':
                            case 'after':
                                $this->db->like($column, $value, $value_type);
                                break;
                            default:
                                $this->db->like($column, $value);
                                break;
                        }
                    }
                }
            }
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            foreach ($data['fields'] as $arr) {
                $col = $arr['column'];
                $this->db->or_like($col, $keyword);
            }
            $this->db->group_end();
        }
    }

    public function search($form_search, $data, $section, $keyword = null, $total = null, $offset = null)
    {
        $get = array();
        $select = implode(',', $data['select_query']);
        $this->filter_search($form_search, $data, $keyword);
        $this->db->select('id,' . $select);
        $this->db->limit($total, $offset);
        $this->db->order_by('id DESC');
        $get['rows'] = $this->db->get($section['table'])->result_array();
        if ($get) {
            $this->filter_search($form_search, $data, $keyword);
            $get['total'] = $this->db->count_all_results($section['table']);
        }
        return $get;
    }

    public function list_posts_select($table, $column, $data_trigger = null)
    {
        $this->db->select('t1.id value, t1.' . $column . ' label');
        if ($data_trigger) {
            $table_trigger = $data_trigger['table'];
            $column_trigger = $data_trigger['column'];
            $value = $data_trigger['value'];
            $this->db->join($table_trigger . ' t2', 't2.id=t1.' . $column_trigger);
            $this->db->where('t1.' . $column_trigger, $value);
        }
        return $this->db->get($table . ' t1')->result_array();
    }

    public function list_posts_checkbox($table, $column)
    {
        $this->db->select($table . '.id value, ' . $table . '.' . $column . ' label');
        return $this->db->get($table)->result_array();
    }

    public function list_options_checked($table, $column, $value)
    {
        $this->db->select($column . ' value');
        $this->db->where_in('id', $value);
        return $this->db->get($table)->result_array();
    }

    public function get_post_selected($table, $column, $id)
    {
        $this->db->select('id,' . $column);
        $this->db->where('id', $id);
        return $this->db->get($table)->row_array();
    }

    public function get_post($section, $id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        $get = $this->db->get($section['table'])->row_array();
        return $get;
    }

    public function get_posts_remove($data, $table, $posts)
    {
        $select = implode(',', $data['select_query']);
        $this->db->select('id,' . $select);
        $this->db->where_in('id', $posts);
        $get = $this->db->get($table)->result_array();
        return $get;
    }

    public function create($data, $section)
    {
        if ($data) {
            $set = $data;
        } else {
            $set = array('id' => 'auto_increment');
        }
        $insert = $this->db->insert($section['table'], $set);
        return $insert;
    }

    public function edit($set, $post, $section)
    {
        $where = array('id' => $post['id']);
        $update = $this->db->update($section['table'], $set, $where);
        return $update;
    }

    public function remove($table, $posts)
    {
        $this->db->where_in('id', $posts);
        $delete = $this->db->delete($table);
        return $delete;
    }
}