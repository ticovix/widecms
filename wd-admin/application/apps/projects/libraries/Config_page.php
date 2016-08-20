<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Config_page {

    private $plugins;
    private $path_view_project;

    public function __construct() {
        $this->path_view_project = 'application/apps/projects/views/project/';
    }

    /*
     * Método com lista de inputs disponíveis
     */

    public function inputs() {
        $input = array();
        $input[] = ['name' => 'Text', 'value' => 'text'];
        $input[] = ['name' => 'Textarea', 'value' => 'textarea'];
        $input[] = ['name' => 'File', 'value' => 'file'];
        $input[] = ['name' => 'Multifile', 'value' => 'multifile'];
        $input[] = ['name' => 'Password', 'value' => 'password'];
        $input[] = ['name' => 'Checkbox', 'value' => 'checkbox'];
        $input[] = ['name' => 'Select', 'value' => 'select'];
        $input[] = ['name' => 'Hidden', 'value' => 'hidden'];
        return $input;
    }

    /*
     * Método com lista de tipos de colunas do banco de dados
     */

    public function types() {
        $input = array();
        $input[] = ['type' => 'integer', 'constraint' => 11];
        $input[] = ['type' => 'char', 'constraint' => 255];
        $input[] = ['type' => 'varchar', 'constraint' => 4000];
        $input[] = ['type' => 'tinytext'];
        $input[] = ['type' => 'text'];
        $input[] = ['type' => 'mediumText'];
        $input[] = ['type' => 'longtext'];
        $input[] = ['type' => 'date', 'constraint' => ''];
        $input[] = ['type' => 'datetime', 'constraint' => ''];
        $input[] = ['type' => 'year', 'constraint' => ''];
        $input[] = ['type' => 'time', 'constraint' => ''];
        $input[] = ['type' => 'timestamp', 'constraint' => ''];
        $input[] = ['type' => 'float', 'constraint' => ''];
        $input[] = ['type' => 'double', 'constraint' => ''];
        return $input;
    }

    /*
     * Método pra criar arquivo config xml
     */

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
                $plugins = $field['plugins'];
                $observation = $field['observation'];
                $attributes = $field['attributes'];
                $required = $field['required'];
                $unique = $field['unique'];
                $default = $field['default'];
                $comment = $field['comment'];

                $new_field = array();
                $attr = array();
                $attr['@type'] = $input;
                $attr['@required'] = $required;
                if (!empty($observation)) {
                    $attr['@observation'] = $observation;
                }
                $new_field['input']["label"] = $name;
                $new_field['input']["list_registers"] = $list_reg;
                if (!empty($plugins)) {
                    $new_field['input']["plugins"] = $plugins;
                }
                if (!empty($attributes)) {
                    $new_field['input']["attributes"] = str_replace('"', '\'', $attributes);
                }
                $new_field['input']["database"] = array(
                    'column' => $column,
                    'type_column' => $type,
                    'unique' => $unique,
                    'default' => $default,
                    'limit' => $limit,
                    'comment' => $comment,
                );
                if ($input == 'select' or $input == 'checkbox') {
                    $options_table = $field['options_table'];
                    $options_label = $field['options_label'];
                    $options_trigger_select = $field['options_trigger_select'];
                    if (!empty($options_table)) {
                        $new_field['input']['options']["table"] = $options_table;
                    }
                    if (!empty($options_label)) {
                        $new_field['input']['options']["options_label"] = $options_label;
                    }
                    if (!empty($options_trigger_select)) {
                        $new_field['input']['options']["trigger_select"] = $options_trigger_select;
                    }
                } elseif ($input == 'file' or $input == 'multifile') {
                    // Campos de upload
                    $extensions_allowed = $field['extensions_allowed'];
                    $image_resize = $field['image_resize'];
                    $image_x = $field['image_x'];
                    $image_y = $field['image_y'];
                    $image_ratio = $field['image_ratio'];
                    $image_ratio_x = $field['image_ratio_x'];
                    $image_ratio_y = $field['image_ratio_y'];
                    $image_ratio_crop = $field['image_ratio_crop'];
                    $image_ratio_fill = $field['image_ratio_fill'];
                    $image_background_color = $field['image_background_color'];
                    $image_convert = $field['image_convert'];
                    $image_text = $field['image_text'];
                    $image_text_color = $field['image_text_color'];
                    $image_text_background = $field['image_text_background'];
                    $image_text_opacity = $field['image_text_opacity'];
                    $image_text_background_opacity = $field['image_text_background_opacity'];
                    $image_text_padding = $field['image_text_padding'];
                    $image_text_position = $field['image_text_position'];
                    $image_text_direction = $field['image_text_direction'];
                    $image_text_x = $field['image_text_x'];
                    $image_text_y = $field['image_text_y'];
                    $image_thumbnails = $field['image_thumbnails'];
                    $new_field['input']['upload'] = array(
                        'extensions_allowed' => $extensions_allowed,
                        'image_resize' => $image_resize,
                        'image_x' => $image_x,
                        'image_y' => $image_y,
                        'image_ratio' => $image_ratio,
                        'image_ratio_x' => $image_ratio_x,
                        'image_ratio_y' => $image_ratio_y,
                        'image_ratio_crop' => $image_ratio_crop,
                        'image_ratio_fill' => $image_ratio_fill,
                        'image_background_color' => $image_background_color,
                        'image_convert' => $image_convert,
                        'image_text' => $image_text,
                        'image_text_color' => $image_text_color,
                        'image_text_background' => $image_text_background,
                        'image_text_opacity' => $image_text_opacity,
                        'image_text_background_opacity' => $image_text_background_opacity,
                        'image_text_padding' => $image_text_padding,
                        'image_text_position' => $image_text_position,
                        'image_text_direction' => $image_text_direction,
                        'image_text_x' => $image_text_x,
                        'image_text_y' => $image_text_y,
                        'image_thumbnails' => $image_thumbnails,
                    );
                }
                $config['page']['form'][] = array_merge($attr, $new_field);
            }
            $xml = arrayToXML($config);
            return format_xml_string($xml);
        } else {
            return false;
        }
    }

    /*
     * Método para carregar o arquivo xml
     */

    public function load_config($dir_project, $dir_page, $dir_section) {
        $path = $this->path_view_project . $dir_project . '/' . $dir_page . '/' . $dir_section . '/config.xml';
        if (is_file($path)) {
            $xml = simplexml_load_file($path, 'SimpleXMLElement');
            return $this->treat_config($xml);
        }
    }

    /*
     * Método para tratar os valores do config xml
     */

    private function treat_config($xml) {
        if ($xml) {
            $fields = $xml->form->input;
            $list = false;
            $config = array();
            if ($fields) {
                foreach ($fields as $field) {
                    $attr = $field->attributes();
                    $label = (string) $field->label;
                    $type = (string) $attr->type;
                    $required = (string) $attr->required;
                    $observation = (string) $attr->observation;
                    $column = (string) $field->database->column;
                    $type_column = (string) $field->database->type_column;
                    $list_registers = (string) $field->list_registers;
                    $unique = (string) $field->database->unique;
                    $default = (string) $field->database->default;
                    $comment = (string) $field->database->comment;
                    $limit = (string) $field->database->limit;
                    $plugins = (string) $field->plugins;
                    $attributes = (string) $field->attributes;
                    if (isset($field->options)) {
                        $options = (string) $field->options->table;
                        $label_options = (string) $field->options->options_label;
                        $trigger_select = (string) $field->options->trigger_select;
                    } else {
                        $options = '';
                        $label_options = '';
                        $trigger_select = '';
                    }

                    if (isset($field->upload)) {
                        $extensions_allowed = (string) $field->upload->extensions_allowed;
                        $image_resize = (string) $field->upload->image_resize;
                        $image_x = (string) $field->upload->image_x;
                        $image_y = (string) $field->upload->image_y;
                        $image_ratio = (string) $field->upload->image_ratio;
                        $image_ratio_x = (string) $field->upload->image_ratio_x;
                        $image_ratio_y = (string) $field->upload->image_ratio_y;
                        $image_ratio_crop = (string) $field->upload->image_ratio_crop;
                        $image_ratio_fill = (string) $field->upload->image_ratio_fill;
                        $image_background_color = (string) $field->upload->image_background_color;
                        $image_convert = (string) $field->upload->image_convert;
                        $image_text = (string) $field->upload->image_text;
                        $image_text_color = (string) $field->upload->image_text_color;
                        $image_text_background = (string) $field->upload->image_text_background;
                        $image_text_opacity = (string) $field->upload->image_text_opacity;
                        $image_text_background_opacity = (string) $field->upload->image_text_background_opacity;
                        $image_text_padding = (string) $field->upload->image_text_padding;
                        $image_text_position = (string) $field->upload->image_text_position;
                        $image_text_direction = (string) $field->upload->image_text_direction;
                        $image_text_x = (string) $field->upload->image_text_x;
                        $image_text_y = (string) $field->upload->image_text_y;
                        $image_thumbnails = (string) $field->upload->image_thumbnails;
                    } else {
                        $extensions_allowed = '';
                        $image_resize = '';
                        $image_x = '';
                        $image_y = '';
                        $image_ratio = '';
                        $image_ratio_x = '';
                        $image_ratio_y = '';
                        $image_ratio_crop = '';
                        $image_ratio_fill = '';
                        $image_background_color = '';
                        $image_convert = '';
                        $image_text = '';
                        $image_text_color = '';
                        $image_text_background = '';
                        $image_text_opacity = '';
                        $image_text_background_opacity = '';
                        $image_text_padding = '';
                        $image_text_position = '';
                        $image_text_direction = '';
                        $image_text_x = '';
                        $image_text_y = '';
                        $image_thumbnails = '';
                    }

                    if ($list_registers == '1') {
                        $config['select_query'][] = $column;
                        $config['list'][] = array(
                            'label' => $label,
                            'type' => $type,
                            'column' => $column,
                            'type_column' => $type_column,
                            'list_registers' => $list_registers,
                            'options' => $options,
                            'label_options' => $label_options,
                        );
                    }
                    $config['fields'][] = array(
                        'label' => $label,
                        'type' => $type,
                        'column' => $column,
                        'type_column' => $type_column,
                        'list_registers' => $list_registers,
                        'required' => $required,
                        'unique' => $unique,
                        'plugins' => $plugins,
                        'observation' => $observation,
                        'attributes' => $attributes,
                        'limit' => $limit,
                        'default' => $default,
                        'comment' => $comment,
                        // Campos de configuração do select
                        'options_table' => $options,
                        'options_label' => $label_options,
                        'options_trigger_select' => $trigger_select,
                        // Campos de upload
                        'extensions_allowed' => $extensions_allowed,
                        'image_resize' => $image_resize,
                        'image_x' => $image_x,
                        'image_y' => $image_y,
                        'image_ratio' => $image_ratio,
                        'image_ratio_x' => $image_ratio_x,
                        'image_ratio_y' => $image_ratio_y,
                        'image_ratio_crop' => $image_ratio_crop,
                        'image_ratio_fill' => $image_ratio_fill,
                        'image_background_color' => $image_background_color,
                        'image_convert' => $image_convert,
                        'image_text' => $image_text,
                        'image_text_color' => $image_text_color,
                        'image_text_background' => $image_text_background,
                        'image_text_opacity' => $image_text_opacity,
                        'image_text_background_opacity' => $image_text_background_opacity,
                        'image_text_padding' => $image_text_padding,
                        'image_text_position' => $image_text_position,
                        'image_text_direction' => $image_text_direction,
                        'image_text_x' => $image_text_x,
                        'image_text_y' => $image_text_y,
                        'image_thumbnails' => $image_thumbnails,
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

    /*
     * Método para criação do template com todos os campos do config xml
     */

    public function fields_template($fields, $post = null) {
        $CI = & get_instance();
        $CI->lang->load_app('posts/form','projects');
        $this->fields = $fields;
        
        foreach ($fields as $field) {

            // Lista os campos do formulário
            $this->attr = array();
            $this->field = $field;
            if (isset($field['attributes']) && strpos($field['attributes'], '{') !== false) {
                $this->attributes = $field['attributes'];
            }
            $this->type = strtolower($field['type']);
            $this->column = $field['column'];
            $this->required = $field['required'];
            // Recebe os dados da máscara do formulário
            $this->plugins = $this->get_plugins($field['plugins']);
            // Seta o label do arquivo, se for obrigatóro insere asterísco
            $this->label = ($this->required) ? $field['label'] . '<span>*</span>' : $field['label'];
            $this->value = (!empty($post)) ? $post[$this->column] : '';
            $this->post = $post;

            if ($this->plugins) {
                $this->add_plugins();
            }
            $this->treat_attributes();
            switch ($this->type) {
                case 'file':
                case 'multifile':
                    $new_field = $this->template_input_file();
                    break;
                case 'textarea':
                    $new_field = $this->template_textarea();
                    break;
                case 'checkbox':
                    $new_field = $this->template_checkbox();
                    break;
                case 'select':
                    $new_field = $this->template_select();
                    break;
                case 'hidden':
                    $new_field = $this->template_input_hidden();
                    break;
                default:
                    $new_field = $this->template_input();
                    break;
            }
            $new_field['column'] = $this->column;
            if (isset($field['observation'])) {
                $new_field['observation'] = $field['observation'];
            }
            $form[] = $new_field;
        }
        return $form;
    }

    /*
     * Método para alterar valor do input, caso tenha algum método para tratar a saida do valor do banco de dados
     */

    private function add_plugins() {
        $plugins = $this->plugins;
        if ($plugins) {
            $this->attr = array();
            foreach ($plugins as $plugin) {
                //Se o campo possuir mascara, seta as chaves que existem no array
                if (isset($plugin['attr'])) {
                    $this->attr[] = $plugin['attr'];
                }
                $js = (isset($plugin['js_form'])) ? $this->change_path($plugin['js_form'], $plugin) : false;
                $css = (isset($plugin['css_form'])) ? $this->change_path($plugin['css_form'], $plugin) : false;
                $class = ucfirst($plugin['plugin']);
                $class_plugin = getcwd() . '/application/' . APP_PATH . 'plugins_input/' . $plugin['plugin'] . '/' . $class . '.php';
                if ($js) {
                    add_js($js);
                }
                if ($css) {
                    add_css($css);
                }
                if (is_file($class_plugin)) {
                    // Se houver um método de saida
                    $CI = & get_instance();
                    $CI->load->library_app('../plugins_input/' . $plugin['plugin'] . '/' . $class);
                    if (method_exists($class, 'output')) {
                        $class = strtolower($class);
                        // Se o método existir, aciona e seta o novo valor
                        $this->value = $CI->$class->output($this->value, $this->field, $this->fields);
                    }
                }
            }
        }
    }

    private function treat_attributes() {
        if (isset($this->attributes)) {
            $attr = (array) json_decode(str_replace('\'', '"', $this->attributes));
            $arr_attr = array();
            if ($attr) {
                foreach ($attr as $obj) {
                    $arr_attr = array_merge($arr_attr, (array) $obj);
                }
            }
            if ($arr_attr) {
                $this->attr[] = $arr_attr;
            }
        }
        $attributes = $this->attr;
        $result_attr = array();
        if (is_array($attributes) && count($attributes) > 0) {
            foreach ($attributes as $attr) {
                if (isset($attr["class"]) && isset($result_attr["class"])) {
                    $result_attr["class"] .= " " . $attr["class"];
                }
                $result_attr = array_merge($attr, $result_attr);
            }
        }
        $this->attr = $result_attr;
    }

    private function change_path($path, $plugin) {
        if (is_array($path)) {
            foreach ($path as $p) {
                $new_path[] = '../plugins_input/' . $plugin['plugin'] . '/assets/' . $p;
            }
            return $new_path;
        } else {
            return '../plugins_input/' . $plugin['plugin'] . '/assets/' . $path;
        }
    }

    /*
     * Método para criar template do input file
     */

    private function template_input_file() {
        load_gallery();
        add_css(array(
            '/plugins/jquery-ui/jquery-ui.css',
            'posts/css/gallery.css'
        ));
        add_js(array(
            '/plugins/jquery-ui/jquery-ui.min.js',
            'posts/js/gallery.js'
        ));
        
        $field = $this->field;
        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $CI = &get_instance();
        $value = ($CI->input->post($this->column) !== null ? $CI->input->post($this->column) : $this->value);
        $files = json_decode($value);
        $txt_extensions = 'TODAS';
        $this->attr['data-field'] = $this->column;
        $this->attr['class'] = 'form-control btn-gallery ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        //$this->attr['data-toggle'] = 'modal';
        //$this->attr['data-target'] = '#gallery';
        $this->attr['type'] = 'button';
        $this->attr['data-config'] = $this->config_upload();
        $new_field['input'] = $this->list_files($files, null, true);
        $new_field['input'] .= form_button($this->attr, '<span class="fa fa-file-image-o"></span> '.$CI->lang->line('projects_label_upload_gallery'));
        if (isset($field['extensions_allowed']) && !empty($field['extensions_allowed'])) {
            $txt_extensions = str_replace(',', ', ', $field['extensions_allowed']);
        }
        $new_field['input'] .= sprintf($CI->lang->line('projects_extensions_allowed'), $txt_extensions);
        $attr = array();
        if ($this->type == 'multifile') {
            $attr['multiple'] = "true";
        }
        $attr['id'] = $this->column . '_field';
        $attr['name'] = $this->column;
        $attr['type'] = 'hidden';
        $attr['class'] = 'input-field';
        $new_field['input'] .= form_input($attr, set_value($this->column, $this->value, false));
        return $new_field;
    }

    /*
     * Método para criar atributo de configuração do campo upload
     */

    private function config_upload() {
        $field = $this->field;
        $config_upload = array(
            'extensions_allowed' => $field['extensions_allowed'],
            'image_resize' => $field['image_resize'],
            'image_x' => $field['image_x'],
            'image_y' => $field['image_y'],
            'image_ratio' => $field['image_ratio'],
            'image_ratio_x' => $field['image_ratio_x'],
            'image_ratio_y' => $field['image_ratio_y'],
            'image_ratio_crop' => $field['image_ratio_crop'],
            'image_ratio_fill' => $field['image_ratio_fill'],
            'image_background_color' => $field['image_background_color'],
            'image_convert' => $field['image_convert'],
            'image_text' => $field['image_text'],
            'image_text_color' => $field['image_text_color'],
            'image_text_background' => $field['image_text_background'],
            'image_text_opacity' => $field['image_text_opacity'],
            'image_text_background_opacity' => $field['image_text_background_opacity'],
            'image_text_padding' => $field['image_text_padding'],
            'image_text_position' => $field['image_text_position'],
            'image_text_direction' => $field['image_text_direction'],
            'image_text_x' => $field['image_text_x'],
            'image_text_y' => $field['image_text_y'],
            'image_thumbnails' => str_replace('\'', '"', $field['image_thumbnails']),
        );
        $config_upload = array_filter($config_upload, function($val) {
            return (!empty($val));
        });
        return str_replace('"', '\'', json_encode($config_upload));
    }

    /*
     * Método para criar template do textarea
     */

    private function template_textarea() {
        add_css(array(
            'posts/css/gallery.css'
        ));
        add_js(array(
            'posts/js/gallery.js'
        ));
        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $this->attr['id'] = $this->column . '_field';
        $this->attr['name'] = $this->column;
        $this->attr['class'] = 'form-control input-field ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $new_field['input'] = form_textarea($this->attr, htmlspecialchars_decode(set_value($this->column, $this->value, false), ENT_QUOTES));
        return $new_field;
    }

    /*
     * Método para criar template do checkbox
     */

    private function template_checkbox() {
        add_js(array(
            'posts/js/events-select.js'
        ));
        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $this->attr['id'] = $this->column . '_field';
        $this->attr['name'] = $this->column . '[]';
        $this->attr['class'] = (isset($this->attr['class']) ? $this->attr['class'] : '');
        $CI = &get_instance();
        // Lista registros para o select
        $CI->load->model('posts_model');
        $table = $this->field['options_table'];
        $column = $this->field['options_label'];
        $posts = $CI->posts_model->list_posts_checkbox($table, $column);
        $opts_checked = array();
        if (!empty($this->value)) {
            $opts_checked = json_decode($this->value);
        }
        if ($posts) {
            $new_field['input'] = '<div>';
            foreach ($posts as $opts) {
                $label = $opts['label'];
                $value = $opts['value'];
                $checked = false;
                if (is_array($opts_checked)) {
                    $checked = (in_array($value, $opts_checked));
                }
                $new_field['input'] .= '<label class="option-checkbox">';
                $new_field['input'] .= form_checkbox($this->attr, $value, $checked);
                $new_field['input'] .= $label;
                $new_field['input'] .= '</label>';
            }
            $new_field['input'] .= '</div>';
        }

        return $new_field;
    }

    /*
     * Método para criar template do select
     */

    private function template_select() {
        add_js(array(
            '/plugins/chosen/js/chosen.jquery.min.js',
            'posts/js/events-select.js',
        ));
        add_css(array(
            '/plugins/chosen/css/chosen.css'
        ));
        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        if (isset($this->field['options_table']) && isset($this->field['options_label'])) {
            $column_trigger = (isset($this->field['options_trigger_select'])) ? $this->field['options_trigger_select'] : '';
            $data_trigger = null;
            if ($column_trigger) {
                $field_trigger = search($this->fields, 'column', $column_trigger);
                if (count($field_trigger) > 0) {

                    $field_trigger = $field_trigger[0];
                    $table_trigger = $field_trigger['options_table'];
                    $label_trigger = $field_trigger['label'];
                    $value_trigger = $this->post[$column_trigger];
                    $this->attr['class'] = (isset($this->attr['class'])) ? $this->attr['class'] : '';
                    $this->attr['class'] .= ' trigger-' . $column_trigger;
                    $data_trigger = array(
                        'table' => $table_trigger,
                        'column' => $column_trigger,
                        'value' => $value_trigger,
                        'label' => $label_trigger
                    );
                } else {
                    $field_trigger = null;
                }
            }
            // Lista as opções do select
            $array_options = $this->set_options($this->field['options_table'], $this->field['options_label'], $data_trigger);
        } else {
            $array_options = array('' => $CI->lang->line('projects_options_not_found'));
        }
        $this->attr['id'] = $this->column . '_field';
        $this->attr['class'] = 'form-control input-field trigger-select chosen-select ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $CI = &get_instance();
        $value = ($CI->input->post($this->column) !== null ? $CI->input->post($this->column) : $this->value);
        $new_field['input'] = form_dropdown($this->column, $array_options, $value, $this->attr);
        return $new_field;
    }

    /*
     * Método para criar template do input hidden
     */

    private function template_input_hidden() {
        $new_field = array();
        $this->attr['id'] = $this->column . '_field';
        $this->attr['name'] = $this->column;
        $new_field['type'] = 'hidden';
        $this->attr['class'] = 'form-control input-field ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $new_field['input'] = form_input($this->attr, set_value($this->column, $this->value));
        return $new_field;
    }

    /*
     * Método para criar template do input
     */

    private function template_input() {
        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $this->attr['name'] = $this->column;
        $this->attr['type'] = $this->type;
        $this->attr['id'] = $this->column . '_field';
        $this->attr['class'] = 'form-control input-field ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $new_field['input'] = form_input($this->attr, htmlspecialchars_decode(set_value($this->column, $this->value), ENT_QUOTES));
        return $new_field;
    }

    /*
     * Método para listar as opções de um select
     */

    private function set_options($table, $column, $data_trigger = null) {
        $CI = & get_instance();
        if (is_array($data_trigger) && empty($data_trigger['value'])) {
            return array('' => sprintf($CI->lang->line('projects_label_subselect'), $data_trigger['label']));
        }
        // Lista registros para o select
        $CI->load->model('posts_model');
        $posts = $CI->posts_model->list_posts_select($table, $column, $data_trigger);

        $options = array();
        if ($posts) {
            // Se for encontrado registros
            $options[''] = 'Selecione';
            foreach ($posts as $post) {
                $id = $post['value'];
                $value = $post['label'];
                // Seta os options
                $options[$id] = $value;
            }
        } else {
            $options[''] = $CI->lang->line('projects_options_not_found');
        }
        return $options;
    }

    /*
     * Método para montar template da listagem de arquivos
     */

    private function list_files($files, $cols = 2, $edit_file=false) {
        $files = (array) $files;
        $ctt = '<div class="content-files">';
        if ($files) {
            $path = PATH_UPLOAD;
            if (isset($files['file'])) {
                $files = array($files);
            }
            foreach ($files as $file) {
                if (!is_object($file)) {
                    $file = (object) $file;
                }
                $file_ = $file->file;
                $title = $file->title;
                $checked = $file->checked;
                if (!empty($file)) {
                    if ($checked == true) {
                        $active = 'active';
                    } else {
                        $active = '';
                    }
                    $ctt .= '<div class="files-list thumbnail ' . $active . '">';
                    if($edit_file){
                        $ctt .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-edit" title="'.$title.'" data-file="'.$file_.'" class="btn-edit-file">';
                    }
                    $ctt .= '<img src="' . base_url('apps/gallery/image/thumb/' . $file_) . '" class="img-responsive">';
                    if($edit_file){
                        $ctt .= '</a>';
                    }
                    $ctt .= '</div>';
                }
            }
        }
        $ctt .= '</div>';
        return $ctt;
    }

    /*
     * Método para tratar a listagem de registros
     * Param posts - contém os registros do banco de dados
     * Param data - contém os dados dos campos de listagem do config xml
     */

    public function treat_list($posts, $data) {
        $list = array();
        // Lista os registros do banco de dados
        foreach ($posts as $row) {
            foreach ($row as $key => $value) {
                // Busca a coluna nos campos do config xml
                $field = search($data['fields'], 'column', $key);
                if (isset($field[0])) {
                    $field = $field[0];
                    // Se o campo for encontrada, faz diversas filtragens no valor da coluna
                    $type = strtolower($field['type']);
                    if (isset($field['plugins']) && !empty($field['plugins'])) {
                        // Se houver um parametro mask setado
                        $plugins = $field['plugins'];
                        $value = $this->plugins_output($plugins, $value, $field, $data);
                    }
                    switch ($type) {
                        case 'select':
                        case 'radio':
                            $value = $this->treat_options($value, $field);
                            break;
                        case 'file':
                        case 'multifile':
                            $value = $this->treat_value_json($value, $field);
                            break;
                        default:
                            break;
                    }
                }
                $row[$key] = $value;
            }
            $list[] = $row;
        }
        return $list;
    }

    private function plugins_output($plugins, $value, $field, $fields) {
        $type = $field['type'];
        $plugins = $this->get_plugins($plugins);
        if ($plugins) {
            foreach ($plugins as $arr) {
                $CI = & get_instance();
                $plugin = $arr['plugin'];
                $class = ucfirst($plugin);
                $class_plugin = getcwd() . '/application/' . APP_PATH . 'plugins_input/' . $plugin . '/' . $class . '.php';
                if (is_file($class_plugin)) {
                    $CI->load->library_app('../plugins_input/' . $plugin . '/' . $class . '.php');
                    // Verifica se possui um método de saida
                    if (method_exists($class, 'output')) {
                        $class = strtolower($class);
                        // Se o método de saída existir, aciona
                        $value = $CI->$class->output($value, $field, $fields);
                    }
                }
            }
        }
        switch ($type) {
            case 'checkbox':
                if (!empty($value)) {
                    $table = (isset($field['options_table'])) ? $field['options_table'] : '';
                    $column = (isset($field['options_label'])) ? $field['options_label'] : '';
                    $opts = json_decode($value);
                    $opts_checked = $CI->posts_model->list_options_checked($table, $column, $opts);
                    if ($opts_checked) {
                        foreach ($opts_checked as $opt) {
                            $val[] = $opt['value'];
                        }
                        $value = implode(', ', $val);
                    }
                }
                break;

            default:
                break;
        }

        return $value;
    }

    private function treat_options($value, $field) {
        if (!empty($value) && $value > 0) {
            // Se o valor da coluna não estiver vazio
            $CI = & get_instance();
            $CI->load->model('posts_model');
            $field = $field;
            $table = (isset($field['options_table'])) ? $field['options_table'] : '';
            $column = (isset($field['options_label'])) ? $field['options_label'] : '';
            if ($table && $column) {
                // Se tabela e coluna existir, lista o valor selecionado pelo campo
                $val = $CI->posts_model->get_post_selected($table, $column, $value);
                if ($val) {
                    // Se um valor for encontrado, seta value com o valor encontrado
                    $value = $val[$column];
                }
            }
        }
        if ($value == '0') {
            $value = '';
        }
        return $value;
    }

    private function treat_value_json($value, $field) {
        if (!empty($value)) {
            // Se o valor da coluna não estiver vazio
            // Decodifica o json
            $files = json_decode($value);
            // Monta o template com as imagens selecionadas e seta no value
            $value = $this->list_files($files, 1);
        }
        return $value;
    }

    /*
     * Método para listar os plugins para usar nos inputs
     * return Array
     */

    public function list_plugins() {
        $path_apps = getcwd() . '/application/' . APP_PATH . 'plugins_input/';
        $opendir = \opendir($path_apps);
        while (false !== ($plugin = readdir($opendir))) {
            if ($plugin != '.' && $plugin != '..') {
                if (is_dir($path_apps . $plugin)) {
                    if (is_file($path_apps . $plugin . '/plugin.yml')) {
                        $this->set_plugin($path_apps . $plugin . '/plugin.yml', $plugin);
                    }
                }
            }
        }
        closedir($opendir);
        asort($this->list_plugins);
        return $this->list_plugins;
    }

    /*
     * Método para buscar o arquivo yml e setar o plugin
     * return Array
     */

    private function set_plugin($path, $plugin) {
        $CI = &get_instance();
        $CI->load->library('spyc');
        $config = $CI->spyc->loadFile($path);
        if (is_array($config)) {
            $config['plugin'] = $plugin;
            if (empty($config['name'])) {
                $config['name'] = $config['plugin'];
            }
            $config['name'] = ucfirst(strtolower($config['name']));
            return $this->list_plugins[] = $config;
        }
    }

    /*
     * Método para buscar um plugin
     * return String
     */

    public function get_plugins($plugins) {
        $plugins = explode("|", $plugins);
        $arr_plugins = array();
        if ($plugins) {
            foreach ($plugins as $plugin) {
                if (!empty($plugin)) {
                    if (!isset($this->list_plugins)) {
                        $plugins = $this->list_plugins();
                    } else {
                        $plugins = $this->list_plugins;
                    }
                    if ($plugins) {
                        $plugin = search($plugins, 'plugin', $plugin);
                        if (isset($plugin[0])) {
                            $arr_plugins[] = $plugin[0];
                        }
                    }
                }
            }
        }
        return $arr_plugins;
    }

}
