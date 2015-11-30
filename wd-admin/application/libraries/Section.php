<?php

class Section {

    public function inputs() {
        $input = array();
        $input[] = ['name' => 'Text', 'realtype' => 'text'];
        $input[] = ['name' => 'Textarea', 'realtype' => 'textarea'];
        $input[] = ['name' => 'CKEditor', 'realtype' => 'textarea', 'mask' => 'ckeditor'];
        $input[] = ['name' => 'File', 'realtype' => 'file'];
        $input[] = ['name' => 'Multiple Files', 'realtype' => 'file[]', 'module' => 'multiple_files'];
        $input[] = ['name' => 'Password', 'realtype' => 'password'];
        $input[] = ['name' => 'Password', 'realtype' => 'password', 'module' => 'bcrypt'];
        $input[] = ['name' => 'Date', 'realtype' => 'text', 'module' => 'date', 'mask' => 'date'];
        $input[] = ['name' => 'Datetime', 'realtype' => 'text', 'module' => 'datetime', 'mask' => 'datetime'];
        $input[] = ['name' => 'Checkbox', 'realtype' => 'checkbox', 'module' => 'checkbox', 'mask' => 'checkbox'];
        $input[] = ['name' => 'Select', 'realtype' => 'select', 'module' => 'select', 'mask' => 'select'];
        $input[] = ['name' => 'Radio', 'realtype' => 'radio', 'module' => 'radio', 'mask' => 'radio'];
        $input[] = ['name' => 'Email', 'realtype' => 'email'];
        $input[] = ['name' => 'Color', 'realtype' => 'color'];
        $input[] = ['name' => 'Number', 'realtype' => 'number'];
        $input[] = ['name' => 'Month', 'realtype' => 'text', 'module' => 'month', 'mask' => 'month'];
        $input[] = ['name' => 'URL', 'realtype' => 'url'];
        $input[] = ['name' => 'Hidden', 'realtype' => 'hidden'];
        return $input;
    }

    public function types() {
        $input = array();
        $input[] = ['type' => 'integer', 'constraint' => 11];
        $input[] = ['type' => 'char', 'constraint' => 128];
        $input[] = ['type' => 'varchar', 'constraint' => 255];
        $input[] = ['type' => 'mediumText'];
        $input[] = ['type' => 'text'];
        $input[] = ['type' => 'longtext'];
        $input[] = ['type' => 'date'];
        $input[] = ['type' => 'datetime'];
        $input[] = ['type' => 'time'];
        $input[] = ['type' => 'year'];
        $input[] = ['type' => 'float'];
        return $input;
    }

    public function create_config_xml($fields) {
        $total = count($fields);
        if ($total) {
            $config = array();
            foreach($fields as $field){
                $name = $field['name'];
                $input = $field['input'];
                $list_reg = $field['list_reg'];
                $column = $field['column'];
                $type = $field['type'];
                $limit = $field['limit'];
                $required = $field['required'];
                $config['page']['form'][] = ['input' => ['@column' => $column]];
            }
            return arrayToXML($config);
        } else {
            return false;
        }
    }

}
