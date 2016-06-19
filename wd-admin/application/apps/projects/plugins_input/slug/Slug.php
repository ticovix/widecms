<?php

class Slug {

    public function input($value, $field, $fields) {
        $CI = &get_instance();
        $field_trigger = search($fields, 'plugins', 'slug_trigger\|', true);
        if (isset($field_trigger[0])) {
            $field_trigger = $field_trigger[0];
            $value_trigger = $CI->input->post($field_trigger['column']);
            $slug = $this->set_slug($value_trigger, $field_trigger);
            return $slug;
        } else {
            return slug($value);
        }
    }

    private function set_slug($value, $field) {
        $section = get_section();
        $slug_ = slug($value);
        $CI = &get_instance();
        $id_post = $CI->uri->segment(8);
        $CI->load->model_app('crud_model');
        $x = 0;
        $exists = true;
        $column = $field['column'];
        while ($exists == true) {
            $slug = $slug_;
            if ($x > 0) {
                $slug = $slug . $x;
            }
            $exists = $CI->crud_model->read($section['table'], null, array($column => $slug, 'id!=' => $id_post))->row();
            $x++;
        }
        return $slug;
    }

}
