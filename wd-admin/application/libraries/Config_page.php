<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Config_page {

    public function inputs() {
        $input = array();
        $input[] = ['name' => 'Text', 'value' => 'text'];
        $input[] = ['name' => 'Textarea', 'value' => 'textarea'];
        $input[] = ['name' => 'File', 'value' => 'file'];
        $input[] = ['name' => 'Multifile', 'value' => 'multifile'];
        $input[] = ['name' => 'Password', 'value' => 'password'];
        $input[] = ['name' => 'Checkbox', 'value' => 'checkbox'];
        $input[] = ['name' => 'Select', 'value' => 'select'];
        $input[] = ['name' => 'Radio', 'value' => 'radio'];
        $input[] = ['name' => 'Hidden', 'value' => 'hidden'];
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
        $input[] = ['type' => 'date', 'constraint' => ''];
        $input[] = ['type' => 'datetime', 'constraint' => ''];
        $input[] = ['type' => 'time', 'constraint' => ''];
        $input[] = ['type' => 'year', 'constraint' => ''];
        $input[] = ['type' => 'float', 'constraint' => ''];
        return $input;
    }

    public function masks_input() {
        $js_default = array(
            'plugins/masks/js/jquery.meio.js',
            'plugins/masks/js/masks.js'
        );
        $masks = array(
            'date' => array(
                'label' => 'Data',
                'js' => array_merge($js_default, array(
                    'plugins/jquery-ui/jquery-ui.min.js',
                    'plugins/masks/js/datetimepicker.js',
                )),
                'css' => array(
                    'plugins/jquery-ui/jquery-ui.css'
                ),
                'callback_input' => 'mask_input_date',
                'callback_output' => 'mask_output_date',
                'attr' => array(
                    'alt' => 'date',
                    'class' => 'add-mask date-mod'
                )
            ),
            'datetime' => array(
                'label' => 'Data e hora',
                'js' => array_merge($js_default, array(
                    'plugins/jquery-ui/jquery-ui.min.js',
                    'plugins/masks/js/datetimepicker.js',
                )),
                'css' => array(
                    'plugins/jquery-ui/jquery-ui.css'
                ),
                'callback_input' => 'mask_input_date',
                'callback_output' => 'mask_output_date',
                'attr' => array(
                    'alt' => 'datetime',
                    'class' => 'add-mask datetime-mod'
                )
            ),
            'time' => array(
                'label' => 'Hora',
                'js' => array_merge($js_default, array(
                    'plugins/jquery-ui/jquery-ui.min.js',
                    'plugins/masks/js/datetimepicker.js',
                )),
                'css' => array(
                    'plugins/jquery-ui/jquery-ui.css'
                ),
                'attr' => array(
                    'alt' => 'time',
                    'class' => 'add-mask time-mod'
                )
            ),
            'real' => array(
                'label' => 'Real (R$)',
                'js' => $js_default,
                'callback_input' => 'mask_input_real',
                'callback_output' => 'mask_output_real',
                'attr' => array(
                    'alt' => 'real',
                    'class' => 'add-mask'
                )
            ),
            'cpf' => array(
                'label' => 'CPF',
                'js' => $js_default,
                'attr' => array(
                    'alt' => 'cpf',
                    'class' => 'add-mask'
                )
            ),
            'cnpj' => array(
                'label' => 'CNPJ',
                'js' => $js_default,
                'attr' => array(
                    'alt' => 'cnpj',
                    'class' => 'add-mask'
                )
            ),
            'cep' => array(
                'label' => 'CEP',
                'js' => $js_default,
                'attr' => array(
                    'alt' => 'cep',
                    'class' => 'add-mask'
                )
            ),
            'cc' => array(
                'label' => 'Cartão de crédito',
                'js' => $js_default,
                'attr' => array(
                    'alt' => 'cc',
                    'class' => 'add-mask'
                )
            ),
            'phone' => array(
                'label' => 'Telefone',
                'js' => $js_default,
                'attr' => array(
                    'alt' => 'phone',
                    'class' => 'add-mask'
                )
            ),
            'cellphone' => array(
                'label' => 'Celular',
                'js' => $js_default,
                'attr' => array(
                    'alt' => 'cellphone',
                    'class' => 'add-mask'
                )
            ),
            'ckeditor' => array(
                'label' => 'CKEditor',
                'js' => array(
                    'plugins/ckeditor/ckeditor.js'
                ),
                'attr' => array(
                    'alt' => 'ckeditor',
                    'class' => 'ckeditor'
                )
            )
        );
        return $masks;
    }

    public function get_mask($mask) {
        $masks = $this->masks_input();
        if (isset($masks[$mask])) {
            return $masks[$mask];
        }
    }

    public function create_config_xml($fields) {
        $total = count($fields);
        if ($total) {
            $config = array();
            foreach ($fields as $field) {
                $name = $field['name'];
                $input = $field['input'];
                $list_reg = $field['list_reg'];
                $column = $field['column'];
                $type = $field['type'];
                $limit = $field['limit'];
                $mask = $field['mask'];
                $required = $field['required'];
                $options = $field['options'];
                $label_options = $field['label_options'];
                $trigger_select = $field['trigger_select'];
                $new_field = array();
                $attr = array();
                $attr['@type'] = $input;
                $attr['@type_column'] = $type;
                $attr['@list_registers'] = $list_reg;
                $attr['@required'] = $required;
                $attr['@limit'] = $limit;
                $attr['@column'] = $column;
                if (!empty($mask)) {
                    $attr['@mask'] = $mask;
                }
                if(!empty($options)){
                    $attr['@options'] = $options;
                }
                if(!empty($label_options)){
                    $attr['@label_options'] = $label_options;
                }
                if(!empty($trigger_select)){ 
                    $attr['@trigger_select'] = $trigger_select;
                }
                $new_field['input'] = $name;
                $config['page']['form'][] = array_merge($attr, $new_field);
            }
            $xml = arrayToXML($config);
            return $xml;
        } else {
            return false;
        }
    }

    public function load_config($project, $page, $section) {
        $dir_project = $project;
        $dir_page = $page;
        $dir_section = $section;
        $path = getcwd() . '/application/views/project/' . $dir_project . '/' . $dir_page . '/' . $dir_section . '/config.xml';
        if (is_file($path)) {
            $xml = simplexml_load_file($path, 'SimpleXMLElement');
            return $this->treat_config($xml);
        }
    }

    private function treat_config($xml) {
        if ($xml) {
            $fields = $xml->form->input;
            $list = false;
            $config = array();
            if ($fields) {
                foreach ($fields as $field) {
                    $attr = $field->attributes();
                    $label = (string) $field;
                    $type = (string) $attr->type;
                    $column = (string) $attr->column;
                    $type_column = (string) $attr->type_column;
                    $list_registers = (string) $attr->list_registers;
                    $required = (string) $attr->required;
                    $limit = (string) $attr->limit;
                    $mask = (string) $attr->mask;
                    $options = (string) $attr->options;
                    $label_options = (string) $attr->label_options;
                    $trigger_select = (string) $attr->trigger_select;
                    if ($list_registers == '1') {
                        $config['select_query'][] = $column;
                        $config['list'][] = array(
                            'label' => $label,
                            'type' => $type,
                            'column' => $column,
                            'type_column' => $type_column,
                            'list_registers' => $list_registers,
                            'required' => $required,
                            'options' => $options,
                            'label_options' => $label_options,
                            'mask' => $mask,
                            'limit' => $limit
                        );
                    }
                    $config['fields'][] = array(
                        'label' => $label,
                        'type' => $type,
                        'column' => $column,
                        'type_column' => $type_column,
                        'list_registers' => $list_registers,
                        'required' => $required,
                        'mask' => $mask,
                        'options' => $options,
                        'label_options' => $label_options,
                        'trigger_select' => $trigger_select,
                        'limit' => $limit
                    );
                }
            } else {
                return false;
            }
            return $config;
        } else {
            return false;
        }
    }

}
