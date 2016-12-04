<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Form
{
    private $form = array();
    private $plugins;

    /*
     * Método para criação do template com todos os campos do config xml
     */

    public function fields_template($fields, $post = null)
    {
        $CI = & get_instance();
        $CI->lang->load_app('posts/form', 'projects');
        $this->fields = $fields;
        $this->post = $post;

        foreach ($fields as $field) {
            $this->field = $field;
            $type = strtolower($field['type']);

            switch ($type) {
                case 'file':
                case 'multifile':
                case 'textarea':
                case 'checkbox':
                case 'select':
                case 'hidden':
                    $this->input_template()->$type()->add();
                    break;
                default:
                    $this->input_template()->input()->add();
                    break;
            }
        }

        return $this->form;
    }

    private function input_template()
    {
        $field = $this->field;
        $post = $this->post;
        if (isset($field['attributes']) && strpos($field['attributes'], '{') !== false) {
            $this->attributes = $field['attributes'];
        }
        $this->column = $field['column'];
        $this->type = strtolower($field['type']);
        $this->plugins = $this->get_plugins($field['plugins']);
        $this->label = ($field['required']) ? $field['label'] . '<span>*</span>' : $field['label'];
        $this->value = (!empty($post)) ? $post[$this->column] : '';
        $this->attr = $this->treat_attributes();
        if ($this->plugins) {
            $this->add_plugins();
        }

        return $this;
    }

    private function add()
    {
        $field = $this->field;
        $this->new_field['column'] = $this->column;
        if (isset($field['observation'])) {
            $this->new_field['observation'] = $field['observation'];
        }

        $this->form[] = $this->new_field;
    }
    /*
     * Método para alterar valor do input, caso tenha algum método para tratar a saida do valor do banco de dados
     */

    private function add_plugins()
    {
        $plugins = $this->plugins;
        if (!$plugins) {
            return false;
        }

        $CI = & get_instance();
        foreach ($plugins as $plugin) {
            if (isset($plugin['attr'])) {
                if (isset($this->attr['class']) && $plugin['attr']['class']) {
                    $plugin['attr']['class'] = $this->attr['class'] . ' ' . $plugin['attr']['class'];
                }

                $this->attr = array_merge($this->attr, $plugin['attr']);
            }

            $js = (isset($plugin['js_form'])) ? $this->change_path($plugin['js_form'], $plugin) : false;
            $css = (isset($plugin['css_form'])) ? $this->change_path($plugin['css_form'], $plugin) : false;
            $class = ucfirst($plugin['plugin']);
            $class_plugin = getcwd() . '/application/' . APP_PATH . 'plugins_input/' . $plugin['plugin'] . '/' . $class . '.php';
            if ($js) {
                $CI->include_components->app_js($js);
            }

            if ($css) {
                $CI->include_components->app_css($css);
            }

            if (is_file($class_plugin)) {
                // Se houver um método de saida
                $CI->load->library_app('../plugins_input/' . $plugin['plugin'] . '/' . $class);
                $method_exists = method_exists($class, 'output');
                if ($method_exists) {
                    $class = strtolower($class);
                    // Se o método existir, aciona e seta o novo valor
                    $this->value = $CI->$class->output($this->value, $this->field, $this->fields);
                }
            }
        }
    }

    private function treat_attributes()
    {
        $temp_attr = array();

        if (isset($this->attributes)) {
            $attr = (array) json_decode(str_replace('\'', '"', $this->attributes));
            $arr_attr = array();
            if ($attr) {
                foreach ($attr as $obj) {
                    $arr_attr = array_merge($arr_attr, (array) $obj);
                }
            }

            if ($arr_attr) {
                $temp_attr[] = $arr_attr;
            }
        }

        $attributes = $temp_attr;
        $result_attr = array();
        if (is_array($attributes) && count($attributes) > 0) {
            foreach ($attributes as $attr) {
                if (isset($attr["class"]) && isset($result_attr["class"])) {
                    $result_attr["class"] .= " " . $attr["class"];
                }
                $result_attr = array_merge($attr, $result_attr);
            }
        }

        return $result_attr;
    }

    private function change_path($path, $plugin)
    {
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

    private function multifile()
    {
        $CI = &get_instance();
        $field = $this->field;
        load_gallery();

        $CI->include_components
                ->vendor_js('components/jqueryui/jquery-ui.min.js')
                ->vendor_css('components/jqueryui/themes/ui-lightness/jquery-ui.min.css')
                ->app_css('posts/css/gallery.css')
                ->app_js('posts/js/gallery.js');

        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $value = ($CI->input->post($this->column) !== null ? $CI->input->post($this->column) : $this->value);
        $files = json_decode($value);
        $txt_extensions = 'TODAS';
        $this->attr['data-field'] = $this->column;
        $this->attr['class'] = 'form-control btn-gallery ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $this->attr['type'] = 'button';
        $this->attr['data-config'] = $this->config_upload();
        $new_field['input'] = $this->list_files($files, null, true);
        $new_field['input'] .= form_button($this->attr, '<span class="fa fa-file-image-o"></span> ' . $CI->lang->line('projects_label_upload_gallery'));
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
        $this->new_field = $new_field;

        return $this;
    }

    private function file()
    {
        $this->multifile();
        return $this;
    }
    /*
     * Método para criar atributo de configuração do campo upload
     */

    private function config_upload()
    {
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

    private function textarea()
    {
        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $this->attr['id'] = $this->column . '_field';
        $this->attr['name'] = $this->column;
        $this->attr['class'] = 'form-control input-field ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $new_field['input'] = form_textarea($this->attr, htmlspecialchars_decode(set_value($this->column, $this->value, false), ENT_QUOTES));
        $this->new_field = $new_field;

        return $this;
    }
    /*
     * Método para criar template do checkbox
     */

    private function checkbox()
    {
        $CI = &get_instance();
        $CI->include_components->app_js('posts/js/events-select.js');

        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $this->attr['id'] = $this->column . '_field';
        $this->attr['name'] = $this->column . '[]';
        $this->attr['class'] = (isset($this->attr['class']) ? $this->attr['class'] : '');
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

        $this->new_field = $new_field;

        return $this;
    }
    /*
     * Método para criar template do select
     */

    private function select()
    {
        $CI = &get_instance();
        $CI->include_components
                ->main_js('plugins/chosen/js/chosen.jquery.min.js')
                ->app_js('posts/js/events-select.js')
                ->main_css('plugins/chosen/css/chosen.css');

        $array_options = array('' => $CI->lang->line('projects_options_not_found'));
        if (isset($this->field['options_table']) && isset($this->field['options_label'])) {
            $data_trigger = null;
            if (isset($this->field['options_trigger_select'])) {
                $column_trigger = $this->field['options_trigger_select'];
                $field_trigger = search($this->fields, 'column', $column_trigger);
                if (count($field_trigger) > 0) {
                    $field_trigger = $field_trigger[0];
                    $table_trigger = $field_trigger['options_table'];
                    $label_trigger = $field_trigger['label'];
                    $value_trigger = $this->post[$column_trigger];
                    $data_trigger = array(
                        'table' => $table_trigger,
                        'column' => $column_trigger,
                        'value' => $value_trigger,
                        'label' => $label_trigger
                    );

                    $this->attr['class'] = (isset($this->attr['class'])) ? $this->attr['class'] : '';
                    $this->attr['class'] .= ' trigger-' . $column_trigger;
                }
            }
            $array_options = $this->set_options($this->field['options_table'], $this->field['options_label'], $data_trigger);
        }

        $this->attr['id'] = $this->column . '_field';
        $this->attr['class'] = 'form-control input-field trigger-select chosen-select ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $value = ($CI->input->post($this->column) !== null ? $CI->input->post($this->column) : $this->value);
        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $new_field['input'] = form_dropdown($this->column, $array_options, $value, $this->attr);
        $this->new_field = $new_field;

        return $this;
    }
    /*
     * Método para criar template do input hidden
     */

    private function hidden()
    {
        $new_field = array();
        $this->attr['id'] = $this->column . '_field';
        $this->attr['name'] = $this->column;
        $new_field['type'] = 'hidden';
        $this->attr['class'] = 'form-control input-field ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $new_field['input'] = form_input($this->attr, set_value($this->column, $this->value));
        $this->new_field = $new_field;

        return $this;
    }
    /*
     * Método para criar template do input
     */

    private function input()
    {
        $new_field = array();
        $new_field['type'] = $this->type;
        $new_field['label'] = $this->label;
        $this->attr['name'] = $this->column;
        $this->attr['type'] = $this->type;
        $this->attr['id'] = $this->column . '_field';
        $this->attr['class'] = 'form-control input-field ' . (isset($this->attr['class']) ? $this->attr['class'] : '');
        $new_field['input'] = form_input($this->attr, htmlspecialchars_decode(set_value($this->column, $this->value), ENT_QUOTES));
        $this->new_field = $new_field;

        return $this;
    }
    /*
     * Método para listar as opções de um select
     */

    private function set_options($table, $column, $data_trigger = null)
    {
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

    private function list_files($files, $cols = 2, $edit_file = false)
    {
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
                    if ($edit_file) {
                        $ctt .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-edit" title="' . $title . '" data-file="' . $file_ . '" class="btn-edit-file">';
                    }
                    $ctt .= '<img src="' . base_url('apps/gallery/image/thumb/' . $file_) . '" class="img-responsive">';
                    if ($edit_file) {
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

    public function treat_list($posts, $data)
    {
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

    private function plugins_output($plugins, $value, $field, $fields)
    {
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

    private function treat_options($value, $field)
    {
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

    private function treat_value_json($value, $field)
    {
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

    public function list_plugins()
    {
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

    private function set_plugin($path, $plugin)
    {
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

    public function get_plugins($plugins)
    {
        $plugins = explode("|", $plugins);
        if (!$plugins) {
            return false;
        }

        $arr_plugins = array();
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

        return $arr_plugins;
    }
}