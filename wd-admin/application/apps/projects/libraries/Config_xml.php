<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Config_xml
{
    private $path_view_project;

    public function __construct()
    {
        $this->path_view_project = 'application/apps/projects/views/project/';
    }
    /*
     * Método pra criar arquivo config.xml
     */

    public function create_config_xml($fields)
    {
        $total = count($fields);
        if ($total <= 0) {
            return false;
        }

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
    }
    /*
     * Método para carregar o arquivo xml
     */

    public function load_config($dir_project, $dir_page, $dir_section)
    {
        $path = $this->path_view_project . $dir_project . '/' . $dir_page . '/' . $dir_section . '/config.xml';
        if (!is_file($path)) {
            return false;
        }
        $xml = simplexml_load_file($path, 'SimpleXMLElement');

        return $this->treat_config($xml);
    }
    /*
     * Método para tratar os valores do config xml
     */

    private function treat_config($xml)
    {
        if (!$xml) {
            return false;
        }

        $fields = $xml->form->input;
        if (!$fields) {
            return false;
        }

        $list = false;
        $config = array();
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
            $options = '';
            $label_options = '';
            $trigger_select = '';
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
            if (isset($field->options)) {
                $options = (string) $field->options->table;
                $label_options = (string) $field->options->options_label;
                $trigger_select = (string) $field->options->trigger_select;
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

        return $config;
    }
}