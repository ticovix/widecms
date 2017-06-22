<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Form
{
    private $fields = array();

    public function fields_template($fields, $post = null)
    {
        $CI = & get_instance();
        $CI->lang->load_app('posts/form', 'projects');
        $form = array();
        $this->fields = $fields;

        foreach ($fields as $field) {
            $attributes = '';
            $add_input = array();
            $data = array();
            $data['post'] = $post;

            $input = $field['input'];
            $column = $field['database']['column'];
            $label = ($input['required']) ? $input['label'] . '<span>*</span>' : $input['label'];
            $type = strtolower($field['input']['type']);

            if (isset($input['attributes']) && strpos($input['attributes'], '{') !== false) {
                $attributes = $input['attributes'];
            }

            if (isset($input['plugins'])) {
                $data['plugins'] = $this->get_plugins($input['plugins']);
            }

            $attr = $this->treat_attributes($attributes);
            $attr['name'] = $column;
            $attr['id'] = $column . '_field';
            $data['attr'] = $attr;
            $data['field'] = $field;
            $data['value'] = (!empty($post)) ? $post[$column] : '';
            if (isset($data['plugins'])) {
                $data = $this->add_plugins($data);
            }

            switch ($type) {
                case 'file':
                case 'multifile':
                    $render = $this->render_file($data);
                    break;
                case 'textarea':
                    $render = $this->render_textarea($data);
                    break;
                case 'checkbox':
                    $render = $this->render_checkbox($data);
                    break;
                case 'select':
                    $render = $this->render_select($data);
                    break;
                default:
                    $render = $this->render_default_input($data);
                    break;
            }

            $add_input['input'] = $render;
            $add_input['column'] = $column;
            if (isset($field['observation'])) {
                $add_input['observation'] = $field['observation'];
            }

            $add_input['type'] = $type;
            $add_input['label'] = $label;

            $form[] = $add_input;
        }

        return $form;
    }

    private function add_plugins($data)
    {
        $plugins = $data['plugins'];
        $attr = $data['attr'];

        if (!$plugins) {
            return $data;
        }

        $CI = & get_instance();
        foreach ($plugins as $plugin) {
            if (isset($plugin['attr'])) {
                if (isset($attr['class']) && isset($plugin['attr']['class'])) {
                    $plugin['attr']['class'] = $attr['class'] . ' ' . $plugin['attr']['class'];
                }

                $data['attr'] = array_merge($attr, $plugin['attr']);
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
                $CI->load->app()->library('../plugins_input/' . $plugin['plugin'] . '/' . $class);
                $method_exists = method_exists($class, 'output');
                if ($method_exists) {
                    $class = strtolower($class);
                    $data['value'] = $CI->$class->output($data['value'], $data['field'], $this->fields, 'form');
                }
            }
        }

        return $data;
    }

    private function treat_attributes($attributes)
    {
        $temp_attr = array();

        if (isset($attributes)) {
            $attr = (array) json_decode(str_replace('\'', '"', $attributes));
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
        if (!is_array($path)) {
            return '../plugins_input/' . $plugin['plugin'] . '/assets/' . $path;
        }

        foreach ($path as $p) {
            $new_path[] = '../plugins_input/' . $plugin['plugin'] . '/assets/' . $p;
        }

        return $new_path;
    }

    private function render_file($data)
    {
        $CI = &get_instance();
        load_gallery();
        $CI->include_components
                ->vendor_js('components/jqueryui/jquery-ui.min.js')
                ->vendor_css('components/jqueryui/themes/ui-lightness/jquery-ui.min.css')
                ->app_css('posts/css/gallery.css')
                ->app_js('posts/js/gallery.js');

        $field = $data['field'];
        $column = $field['database']['column'];
        $value = ($CI->input->post($column) !== null ? $CI->input->post($column) : $data['value']);

        $files = json_decode($value);
        $render = $this->list_files($files, null, true);

        $attr = array();
        $attr['data-field'] = $column;
        $attr['class'] = 'form-control btn-gallery ' . (isset($attr['class']) ? $attr['class'] : '');
        $attr['type'] = 'button';
        $attr['data-config'] = $this->config_upload($field);
        $render .= form_button($attr, '<span class="fa fa-file-image-o"></span> ' . $CI->lang->line('projects_label_upload_gallery'));

        $txt_extensions = 'TODAS';
        if (isset($field['extensions_allowed']) && !empty($field['extensions_allowed'])) {
            $txt_extensions = str_replace(',', ', ', $field['extensions_allowed']);
        }

        $render .= sprintf($CI->lang->line('projects_extensions_allowed'), $txt_extensions);
        $attr = $data['attr'];
        if ($field['input']['type'] == 'multifile') {
            $attr['multiple'] = "true";
        }

        $attr['name'] = $column;
        $attr['type'] = 'hidden';
        $attr['class'] = 'input-field';
        $render .= form_input($attr, set_value($column, $value, false));

        return $render;
    }

    private function config_upload($field)
    {
        $config = $field['input']['upload'];
        $config_upload = array(
            'extensions_allowed' => $config['extensions_allowed'],
            'image_resize' => $config['image_resize'],
            'image_x' => $config['image_x'],
            'image_y' => $config['image_y'],
            'image_ratio' => $config['image_ratio'],
            'image_ratio_x' => $config['image_ratio_x'],
            'image_ratio_y' => $config['image_ratio_y'],
            'image_ratio_crop' => $config['image_ratio_crop'],
            'image_ratio_fill' => $config['image_ratio_fill'],
            'image_background_color' => $config['image_background_color'],
            'image_convert' => $config['image_convert'],
            'image_text' => $config['image_text'],
            'image_text_color' => $config['image_text_color'],
            'image_text_background' => $config['image_text_background'],
            'image_text_opacity' => $config['image_text_opacity'],
            'image_text_background_opacity' => $config['image_text_background_opacity'],
            'image_text_padding' => $config['image_text_padding'],
            'image_text_position' => $config['image_text_position'],
            'image_text_direction' => $config['image_text_direction'],
            'image_text_x' => $config['image_text_x'],
            'image_text_y' => $config['image_text_y'],
            'image_thumbnails' => str_replace('\'', '"', $config['image_thumbnails']),
        );

        $config_upload = array_filter($config_upload, function($val) {
            return (!empty($val));
        });

        return str_replace('"', '\'', json_encode($config_upload));
    }

    private function render_textarea($data)
    {
        $field = $data['field'];
        $column = $field['database']['column'];
        $value = $data['value'];
        $attr = $data['attr'];
        $attr['type'] = $field['input']['type'];
        $attr['class'] = 'form-control input-field ' . (isset($attr['class']) ? $attr['class'] : '');

        return form_textarea($attr, htmlspecialchars_decode(set_value($column, $value, false), ENT_QUOTES));
    }

    private function render_checkbox($data)
    {
        $CI = &get_instance();
        $CI->load->model('posts_model');
        $CI->include_components->app_js('posts/js/events-select.js');

        $field = $data['field'];
        $column = $field['database']['column'];
        $value = $data['value'];
        $attr = $data['attr'];
        $attr['name'] = $column . '[]';
        $attr['class'] = (isset($attr['class']) ? $attr['class'] : '');

        $render = '';
        $table = $field['input']['options']['table'];
        $column = $field['input']['options']['options_label'];
        $posts = $CI->posts_model->list_posts_checkbox($table, $column);
        $opts_checked = array();
        if (!empty($value)) {
            $opts_checked = json_decode($value);
        }

        if ($posts) {
            $render = '<div>';
            foreach ($posts as $opts) {
                $label = $opts['label'];
                $value = $opts['value'];
                $checked = false;
                if (is_array($opts_checked)) {
                    $checked = (in_array($value, $opts_checked));
                }

                $render .= '<label class="option-checkbox">';
                $render .= form_checkbox($attr, $value, $checked);
                $render .= $label;
                $render .= '</label>';
            }

            $render .= '</div>';
        }

        return $render;
    }

    private function render_select($data)
    {
        $CI = &get_instance();
        $CI->include_components
                ->main_js('plugins/chosen/js/chosen.jquery.min.js')
                ->app_js('posts/js/events-select.js')
                ->main_css('plugins/chosen/css/chosen.css');

        $array_options = array('' => $CI->lang->line('projects_options_not_found'));
        $field = $data['field'];
        $column = $field['database']['column'];
        $attr = $data['attr'];
        $attr['type'] = $field['input']['type'];

        $input = $field['input'];
        if (isset($input['options']['table']) && isset($input['options']['options_label'])) {
            $data_trigger = null;
            if (isset($input['options']['trigger_select']) && !empty($input['options']['trigger_select'])) {
                $column_trigger = $input['options']['trigger_select'];
                $field_trigger = $this->search_field($column_trigger, $this->fields);
                if (count($field_trigger) > 0) {
                    $table_trigger = $field_trigger['input']['options']['table'];
                    $label_trigger = $field_trigger['input']['label'];
                    $value_trigger = $data['post'][$column_trigger];
                    $data_trigger = array(
                        'table' => $table_trigger,
                        'column' => $column_trigger,
                        'value' => $value_trigger,
                        'label' => $label_trigger
                    );

                    $attr['class'] = (isset($attr['class'])) ? $attr['class'] : '';
                    $attr['class'] .= ' trigger-' . $column_trigger;
                }
            }

            $array_options = $this->set_options($input['options']['table'], $input['options']['options_label'], $data_trigger);
        }

        $attr['id'] = $column . '_field';
        $attr['class'] = 'input-field form-control trigger-select chosen-select ' . (isset($attr['class']) ? $attr['class'] : '');
        $value = ($CI->input->post($column) !== null ? $CI->input->post($column) : $data['value']);

        return form_dropdown($column, $array_options, $value, $attr);
    }

    private function render_default_input($data)
    {
        $field = $data['field'];
        $column = $field['database']['column'];
        $value = $data['value'];
        $attr = $data['attr'];
        $attr['type'] = $field['input']['type'];
        $attr['class'] = 'form-control input-field ' . (isset($attr['class']) ? $attr['class'] : '');
        return form_input($attr, htmlspecialchars_decode(set_value($column, $value), ENT_QUOTES));
    }

    private function set_options($table, $column, $data_trigger = null)
    {
        $CI = & get_instance();
        if (is_array($data_trigger) && empty($data_trigger['value'])) {
            return array('' => sprintf($CI->lang->line('projects_label_subselect'), $data_trigger['label']));
        }

        $CI->load->model('posts_model');
        $posts = $CI->posts_model->list_posts_select($table, $column, $data_trigger);

        $options = array();
        if ($posts) {
            $options[''] = 'Selecione';
            foreach ($posts as $post) {
                $id = $post['value'];
                $value = $post['label'];
                $options[$id] = $value;
            }
        } else {
            $options[''] = $CI->lang->line('projects_options_not_found');
        }

        return $options;
    }

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
                if (!empty($file)) {
                    $ctt .= '<div class="files-list image-file">';
                    if ($edit_file) {
                        $ctt .= '<a href="' . wd_base_url('wd-content/upload/' . $file) . '" class="fancybox" rel="gallery" data-fancybox-group="gallery" data-file="' . $file . '">';
                    }

                    $ctt .= '<img src="' . base_url('apps/gallery/image/thumb?file=' . $file) . '" class="img-responsive">';
                    if ($edit_file) {
                        $ctt .= '</a>';
                        $ctt .= '<a href="javascript:void(0);" class="btn-remove-file" data-file="' . $file . '"><span class="fa fa-remove"></span></a>';
                    }

                    $ctt .= '</div>';
                }
            }
        }

        $ctt .= '</div>';
        return $ctt;
    }

    public function search_field($column, $fields)
    {
        $field_find = array();
        if (empty($fields)) {
            return $field_find;
        }

        foreach ($fields as $field) {
            if ($field['database']['column'] == $column) {
                $field_find = $field;
            }
        }


        return $field_find;
    }

    public function treat_list($posts, $section, $page = null)
    {
        if (!$posts) {
            return false;
        }

        $list = array();
        foreach ($posts as $row) {
            foreach ($row as $column => $value) {
                $field = $this->search_field($column, $section['fields']);
                if ($field) {
                    $input = $field['input'];
                    $type = strtolower($input['type']);
                    if (isset($input['plugins']) && !empty($input['plugins'])) {
                        $plugins = $input['plugins'];
                        $value = $this->plugins_output($plugins, $value, $field, $section, $page);
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

                $row[$column] = $value;
            }

            $list[] = $row;
        }

        return $list;
    }

    private function plugins_output($plugins, $value, $field, $fields, $page = null)
    {
        $type = $field['input']['type'];
        $get_plugins = $this->get_plugins($plugins);
        if ($get_plugins) {
            foreach ($get_plugins as $arr) {
                $CI = & get_instance();
                $plugin = $arr['plugin'];
                $class = ucfirst($plugin);
                $class_plugin = getcwd() . '/application/' . APP_PATH . 'plugins_input/' . $plugin . '/' . $class . '.php';
                if (is_file($class_plugin)) {
                    $CI->load->app()->library('../plugins_input/' . $plugin . '/' . $class . '.php');
                    if (method_exists($class, 'output')) {
                        $class = strtolower($class);
                        $value = $CI->$class->output($value, $field, $fields, $page);
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
            $CI = & get_instance();
            $CI->load->app('projects')->model('posts_model');
            $input = $field['input'];
            $table = (isset($input['options']['table'])) ? $input['options']['table'] : '';
            $column = (isset($input['options']['options_label'])) ? $input['options']['options_label'] : '';
            if ($table && $column) {
                $val = $CI->posts_model->get_post_selected($table, $column, $value);
                if ($val) {
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
            $files = json_decode($value);
            $value = $this->list_files($files, 1);
        }

        return $value;
    }

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

    private function set_plugin($path, $plugin)
    {
        $spyc = new Spyc();
        $config = $spyc->loadFile($path);
        if (!is_array($config)) {
            return false;
        }

        $config['plugin'] = $plugin;
        if (empty($config['name'])) {
            $config['name'] = $config['plugin'];
        }

        $config['name'] = ucfirst(strtolower($config['name']));

        return $this->list_plugins[] = $config;
    }

    public function get_plugins($plugins)
    {
        $plugins = explode("|", $plugins);
        if (!$plugins) {
            return false;
        }

        $arr_plugins = array();
        foreach ($plugins as $plugin) {
            if (!empty($plugin)) {
                if (empty($this->list_plugins)) {
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