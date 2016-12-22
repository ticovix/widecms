<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app() ?>"><?php echo $name_app ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $project['directory']) ?>"><?php echo $project['name'] ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $project['directory'] . '/' . $page['directory']) ?>"><?php echo $page['name'] ?></a></li>
    <li class="active"><?php echo $title ?></li>
</ul>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php
                echo getErrors();
                if ($this->uri->segment('4') != 'edit-section' or $this->uri->segment('4') == 'edit-section' && $fields) {
                    echo form_open(current_url() . '?' . $this->input->server('QUERY_STRING'));
                    ?>
                    <h3><?php echo $this->lang->line(APP . '_subtitle_data_basic') ?></h3>
                    <p><?php echo $this->lang->line(APP . '_text_data_basic') ?></p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP . '_label_name') ?>*</label>
                                <input type="text" name="name" id="dig_name" value="<?php echo set_value('name', $name) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP . '_label_directory') ?>*</label>
                                <input type="text" name="directory" id="dir_name" value="<?php echo set_value('directory', $directory) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP . '_label_table') ?>*</label>
                                <?php if (!empty($preffix)) { ?>
                                    <div class="input-group">
                                        <div class="input-group-addon"><?php echo $preffix ?></div>
                                    <?php } ?>
                                    <input type="text" name="table" id="table_name" value="<?php echo set_value('table', $table) ?>" class="form-control">
                                    <?php if (!empty($preffix)) { ?></div><?php } ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP . '_label_status') ?>*</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo set_select('status', '1', ($status == '1')) ?>>Ativado</option>
                                    <option value="0" <?php echo set_select('status', '0', ($status == '0')) ?>>Desativado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3><?php echo $this->lang->line(APP . '_subtitle_config_advanced') ?></h3>
                    <p><?php echo $this->lang->line(APP . '_text_data_advanced') ?></p>
                    <button type="button" class="btn btn-default btn-primary" id="btn-add-field" data-toggle="modal" data-target="#modal-new-field"> <span class="fa fa-plus"></span> <?php echo $this->lang->line(APP . '_btn_add_field') ?></button>
                    <table class="table table-striped table-responsive table-bordered" id="fields">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line(APP . '_label_name') ?></th>
                                <th><?php echo $this->lang->line(APP . '_label_input') ?></th>
                                <th><?php echo $this->lang->line(APP . '_label_column') ?></th>
                                <th><?php echo $this->lang->line(APP . '_label_type') ?></th>
                                <th width="30"><?php echo $this->lang->line(APP . '_label_list') ?></th>
                                <th width="30"><?php echo $this->lang->line(APP . '_label_delete') ?></th>
                            </tr>
                        </thead>
                        <tbody id="sortable">
                            <?php
                            $start_total = 3;
                            $total = count($this->input->post('name_field'));
                            if ($total == 0 && is_array($fields)) {
                                $total = count($fields);
                            }
                            for ($i = 0; $i < $total; $i++) {
                                $input = (isset($fields[$i]['input'])) ? $fields[$i]['input'] : '';
                                $database = (isset($fields[$i]['database'])) ? $fields[$i]['database'] : '';

                                $name_field = (isset($input['label'])) ? $input['label'] : '';
                                $type_field = (isset($input['type'])) ? strtolower($input['type']) : '';
                                $list_registers = (isset($input['list_registers'])) ? $input['list_registers'] : '';
                                $column = (isset($database['column'])) ? $database['column'] : '';
                                $type_column = (isset($database['type_column'])) ? $database['type_column'] : '';
                                $limit = (isset($database['limit'])) ? $database['limit'] : '';
                                $required = (isset($database['required'])) ? $database['required'] : '';
                                $unique = (isset($database['unique'])) ? $database['unique'] : '';
                                $default = (isset($database['default'])) ? $database['default'] : '';
                                $comment = (isset($database['comment'])) ? $database['comment'] : '';
                                $remove = (isset($input['remove'])) ? $input['remove'] : '';
                                $plugin_field = (isset($input['plugins'])) ? $input['plugins'] : '';
                                $observation = (isset($input['observation'])) ? $input['observation'] : '';
                                $attributes = (isset($input['attributes'])) ? $input['attributes'] : '';
                                /*
                                 * Vars of config to select
                                 */
                                $options_field = (isset($input['options_table'])) ? $input['options_table'] : '';
                                $select_trigger_field = (isset($input['options_trigger_select'])) ? $input['options_trigger_select'] : '';
                                $label_options_field = (isset($input['options_label'])) ? $input['options_label'] : '';
                                $label_options = (isset($input['label_options_'])) ? $input['label_options_'] : array();
                                /*
                                 * Vars of config to upload
                                 */
                                $extensions_allowed = (isset($input['extensions_allowed'])) ? $input['extensions_allowed'] : '';
                                $image_resize = (isset($input['image_resize'])) ? $input['image_resize'] : '';
                                $image_x = (isset($input['image_x'])) ? $input['image_x'] : '';
                                $image_y = (isset($input['image_y'])) ? $input['image_y'] : '';
                                $image_ratio = (isset($input['image_ratio'])) ? $input['image_ratio'] : '';
                                $image_ratio_x = (isset($input['image_ratio_x'])) ? $input['image_ratio_x'] : '';
                                $image_ratio_y = (isset($input['image_ratio_y'])) ? $input['image_ratio_y'] : '';
                                $image_ratio_crop = (isset($input['image_ratio_crop'])) ? $input['image_ratio_crop'] : '';
                                $image_ratio_fill = (isset($input['image_ratio_fill'])) ? $input['image_ratio_fill'] : '';
                                $image_background_color = (isset($input['image_background_color'])) ? $input['image_background_color'] : '';
                                $image_convert = (isset($input['image_convert'])) ? $input['image_convert'] : '';
                                $image_text = (isset($input['image_text'])) ? $input['image_text'] : '';
                                $image_text_color = (isset($input['image_text_color'])) ? $input['image_text_color'] : '';
                                $image_text_background = (isset($input['image_text_background'])) ? $input['image_text_background'] : '';
                                $image_text_opacity = (isset($input['image_text_opacity'])) ? $input['image_text_opacity'] : '';
                                $image_text_background_opacity = (isset($input['image_text_background_opacity'])) ? $input['image_text_background_opacity'] : '';
                                $image_text_padding = (isset($input['image_text_padding'])) ? $input['image_text_padding'] : '';
                                $image_text_position = (isset($input['image_text_position'])) ? $input['image_text_position'] : '';
                                $image_text_direction = (isset($input['image_text_direction'])) ? $input['image_text_direction'] : '';
                                $image_text_x = (isset($input['image_text_x'])) ? $input['image_text_x'] : '';
                                $image_text_y = (isset($input['image_text_y'])) ? $input['image_text_y'] : '';
                                $image_thumbnails = (isset($input['image_thumbnails'])) ? $input['image_thumbnails'] : '';
                                ?>
                                <tr class="field-current btn-edit" data-index="<?php echo $i ?>">
                                    <td>
                                        <input type="hidden" class="name-val" name="name_field[<?php echo $i ?>]" value="<?php echo set_value('name_field[' . $i . ']', $name_field) ?>">
                                        <input type="hidden" class="input-val" name="input_field[<?php echo $i ?>]" value="<?php echo set_value('input_field[' . $i . ']', $type_field) ?>">
                                        <input type="hidden" class="list-registers-val" name="list_registers_field[<?php echo $i ?>]" value="<?php echo set_value('list_registers_field[' . $i . ']', $list_registers) ?>">
                                        <input type="hidden" class="column-val" name="column_field[<?php echo $i ?>]" value="<?php echo set_value('column_field[' . $i . ']', $column) ?>">
                                        <input type="hidden" class="type-val" name="type_field[<?php echo $i ?>]" value="<?php echo set_value('type_field[' . $i . ']', $type_column) ?>">
                                        <input type="hidden" class="plugins-val" name="plugins_field[<?php echo $i ?>]" value="<?php echo set_value('plugin_field[' . $i . ']', $plugin_field) ?>">
                                        <input type="hidden" class="require-val" name="required_field[<?php echo $i ?>]" value="<?php echo set_value('required_field[' . $i . ']', $required) ?>">
                                        <input type="hidden" class="observation-val" name="observation_field[<?php echo $i ?>]" value="<?php echo set_value('observation_field[' . $i . ']', $observation) ?>">
                                        <input type="hidden" class="attributes-val" name="attributes_field[<?php echo $i ?>]" value='<?php echo set_value('attributes_field[' . $i . ']', $attributes) ?>'>
                                        <input type="hidden" class="unique-val" name="unique_field[<?php echo $i ?>]" value="<?php echo set_value('unique_field[' . $i . ']', $unique) ?>">
                                        <input type="hidden" class="limit-val" name="limit_field[<?php echo $i ?>]" value="<?php echo set_value('limit_field[' . $i . ']', $limit) ?>">
                                        <input type="hidden" class="comment-val" name="comment_field[<?php echo $i ?>]" value="<?php echo set_value('comment_field[' . $i . ']', $comment) ?>">
                                        <input type="hidden" class="default-val" name="default_field[<?php echo $i ?>]" value="<?php echo set_value('default_field[' . $i . ']', $default) ?>">
                                        <input type="hidden" class="position-val" name="position[<?php echo $i ?>]" value='<?php echo set_value('position[' . $i . ']', $i) ?>'>
                                        <!--Inputs of config to select and checkbox-->
                                        <input type="hidden" class="options-val" name="options_field[<?php echo $i ?>]" value="<?php echo set_value('options_field[' . $i . ']', $options_field) ?>">
                                        <input type="hidden" class="label-options-val" name="label_options_field[<?php echo $i ?>]" value="<?php echo set_value('label_options_field[' . $i . ']', $label_options_field) ?>">
                                        <input type="hidden" class="trigger-select-val" name="trigger_select_field[<?php echo $i ?>]" value="<?php echo set_value('trigger_select_field[' . $i . ']', $select_trigger_field) ?>">
                                        <input type="hidden" class="options-selected" name="options_selected[<?php echo $i ?>]" value="<?php echo set_value('options_selected[' . $i . ']', json_encode($label_options)) ?>">
                                        <!--Inputs of config to upload-->
                                        <input type="hidden" class="extensions-allowed-val" name="extensions_allowed[<?php echo $i ?>]" value="<?php echo set_value('extensions_allowed[' . $i . ']', $extensions_allowed) ?>">
                                        <input type="hidden" class="image-resize-val" name="image_resize[<?php echo $i ?>]" value="<?php echo set_value('image_resize[' . $i . ']', $image_resize) ?>">
                                        <input type="hidden" class="image-x-val" name="image_x[<?php echo $i ?>]" value="<?php echo set_value('image_x[' . $i . ']', $image_x) ?>">
                                        <input type="hidden" class="image-y-val" name="image_y[<?php echo $i ?>]" value="<?php echo set_value('image_y[' . $i . ']', $image_y) ?>">
                                        <input type="hidden" class="image-ratio-val" name="image_ratio[<?php echo $i ?>]" value="<?php echo set_value('image_ratio[' . $i . ']', $image_ratio) ?>">
                                        <input type="hidden" class="image-ratio-x-val" name="image_ratio_x[<?php echo $i ?>]" value="<?php echo set_value('image_ratio_x[' . $i . ']', $image_ratio_x) ?>">
                                        <input type="hidden" class="image-ratio-y-val" name="image_ratio_y[<?php echo $i ?>]" value="<?php echo set_value('image_ratio_y[' . $i . ']', $image_ratio_y) ?>">
                                        <input type="hidden" class="image-ratio-crop-val" name="image_ratio_crop[<?php echo $i ?>]" value="<?php echo set_value('image_ratio_crop[' . $i . ']', $image_ratio_crop) ?>">
                                        <input type="hidden" class="image-ratio-fill-val" name="image_ratio_fill[<?php echo $i ?>]" value="<?php echo set_value('image_ratio_fill[' . $i . ']', $image_ratio_fill) ?>">
                                        <input type="hidden" class="image-background-color-val" name="image_background_color[<?php echo $i ?>]" value="<?php echo set_value('image_background_color[' . $i . ']', $image_background_color) ?>">
                                        <input type="hidden" class="image-convert-val" name="image_convert[<?php echo $i ?>]" value="<?php echo set_value('image_convert[' . $i . ']', $image_convert) ?>">
                                        <input type="hidden" class="image-text-val" name="image_text[<?php echo $i ?>]" value="<?php echo set_value('image_text[' . $i . ']', $image_text) ?>">
                                        <input type="hidden" class="image-text-color-val" name="image_text_color[<?php echo $i ?>]" value="<?php echo set_value('image_text_color[' . $i . ']', $image_text_color) ?>">
                                        <input type="hidden" class="image-text-background-val" name="image_text_background[<?php echo $i ?>]" value="<?php echo set_value('image_text_background[' . $i . ']', $image_text_background) ?>">
                                        <input type="hidden" class="image-text-opacity-val" name="image_text_opacity[<?php echo $i ?>]" value="<?php echo set_value('image_text_opacity[' . $i . ']', $image_text_opacity) ?>">
                                        <input type="hidden" class="image-text-background-opacity-val" name="image_text_background_opacity[<?php echo $i ?>]" value="<?php echo set_value('image_text_background_opacity[' . $i . ']', $image_text_background_opacity) ?>">
                                        <input type="hidden" class="image-text-padding-val" name="image_text_padding[<?php echo $i ?>]" value="<?php echo set_value('image_text_padding[' . $i . ']', $image_text_padding) ?>">
                                        <input type="hidden" class="image-text-position-val" name="image_text_position[<?php echo $i ?>]" value="<?php echo set_value('image_text_position[' . $i . ']', $image_text_position) ?>">
                                        <input type="hidden" class="image-text-direction-val" name="image_text_direction[<?php echo $i ?>]" value="<?php echo set_value('image_text_direction[' . $i . ']', $image_text_direction) ?>">
                                        <input type="hidden" class="image-text-x-val" name="image_text_x[<?php echo $i ?>]" value="<?php echo set_value('image_text_x[' . $i . ']', $image_text_x) ?>">
                                        <input type="hidden" class="image-text-y-val" name="image_text_y[<?php echo $i ?>]" value="<?php echo set_value('image_text_y[' . $i . ']', $image_text_y) ?>">
                                        <input type="hidden" class="image-thumbnails-val" name="image_thumbnails[<?php echo $i ?>]" value="<?php echo set_value('image_thumbnails[' . $i . ']', $image_thumbnails) ?>">

                                        <?php echo set_value('name_field[' . $i . ']', $name_field) ?>
                                    </td>
                                    <td><?php echo set_value('input_field[' . $i . ']', $type_field) ?></td>
                                    <td><?php echo set_value('column_field[' . $i . ']', $column) ?></td>
                                    <td><?php echo set_value('type_field[' . $i . ']', $type_column) ?></td>
                                    <td align="center"><i class=" fa fa-<?php
                                        if (set_value('list_registers_field[' . $i . ']', $list_registers)) {
                                            echo 'check';
                                        } else {
                                            echo 'remove';
                                        }
                                        ?>"></i>
                                    </td>
                                    <td align="center"><input type="checkbox" class="check-remove" name="remove_field[<?php echo $i ?>]" title="<?php echo $this->lang->line(APP . '_title_delete') ?>"> </td>
                                </tr>
                                <?php
                            }
                            if (!isset($name_field)) {
                                echo '<tr class="msg-is-empty"><td colspan="6">' . $this->lang->line(APP . '_fields_not_found') . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group text-right">
                                <input class="btn btn-primary" value="<?php echo $this->lang->line(APP . '_btn_save') ?>" name="send" type="submit">
                            </div>
                        </div>
                    </div>
                    <?php
                    echo form_close();
                }
                ?>
                <!-- Modal add/change field -->
                <div class="modal fade" id="modal-new-field" data-index="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line(APP . '_title_config_field') ?></h4>
                            </div>
                            <div class="modal-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="nav-tab active"><a href="#html" aria-controls="html" role="tab" data-toggle="tab"><?php echo $this->lang->line(APP . '_tab_html') ?></a></li>
                                    <li role="presentation" class="nav-tab"><a href="#database" aria-controls="database" role="tab" data-toggle="tab"><?php echo $this->lang->line(APP . '_tab_database') ?></a></li>
                                    <li role="presentation" class="nav-tab"><a href="#plugins" aria-controls="plugins" role="tab" data-toggle="tab"><?php echo $this->lang->line(APP . '_tab_plugins') ?></a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="html">
                                        <h4><?php echo $this->lang->line(APP . '_html_subtitle_config') ?></h4>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_html_label_name') ?>*</label>
                                                    <input type="text" id="name_field" maxlength="45" class="form-control dig_name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_html_label_input') ?>*</label>
                                                    <div id="content-field">
                                                        <select id="input_field" class="form-control select-input">
                                                            <option value=""><?php echo $this->lang->line(APP . '_option_select') ?></option>
                                                            <?php
                                                            if (count($inputs)) {
                                                                foreach ($inputs as $input) {
                                                                    ?>
                                                                    <option value="<?php echo $input['value']; ?>"><?php echo $input['name'] ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <a href="#modal" class="input-group-addon hide" id="btn-config" role="button" data-toggle="modal"><span class="fa fa-cog"></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_html_label_list') ?></label>
                                                    <select id="list_registers_field" class="form-control">
                                                        <option value="0"><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                        <option value="1"><?php echo $this->lang->line(APP . '_option_yes') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_html_label_required') ?></label>
                                                    <select id="required_field" class="form-control">
                                                        <option value="0"><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                        <option value="1"><?php echo $this->lang->line(APP . '_option_yes') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h4><?php echo $this->lang->line(APP . '_html_subtitle_additional_attributes') ?></h4>
                                                <div id="attributes">
                                                    <div class="row attr-current">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control param_attr_field" placeholder="<?php echo $this->lang->line(APP . '_label_attribute') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control value_attr_field" placeholder="<?php echo $this->lang->line(APP . '_label_value') ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" id="add-attr" class="btn"><i class="fa fa-fw fa-plus"></i> <?php echo $this->lang->line(APP . '_html_btn_plus') ?></button>
                                            </div>
                                            <div class="col-sm-6">
                                                <h4><?php echo $this->lang->line(APP . '_html_subtitle_others') ?></h4>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label><?php echo $this->lang->line(APP . '_html_label_observation') ?></label>
                                                            <textarea class="form-control" id="observation_field"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="database">
                                        <h4><?php echo $this->lang->line(APP . '_db_subtitle_config') ?></h4>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_db_label_column') ?>*</label>
                                                    <input type="text" id="column_field" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_db_label_type') ?>*</label>
                                                    <select id="type_field" class="form-control">
                                                        <option value=""><?php echo $this->lang->line(APP . '_option_select') ?></option>
                                                        <?php
                                                        if (count($types)) {
                                                            foreach ($types as $type) {
                                                                ?>
                                                                <option value="<?php echo $type['type']; ?>"><?php echo $type['type'] ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_db_label_limit') ?></label>
                                                    <input type="text" id="limit_column_field" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_db_label_unique') ?></label>
                                                    <select id="unique_field" class="form-control">
                                                        <option value="0"><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                        <option value="1"><?php echo $this->lang->line(APP . '_option_yes') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_db_label_default') ?></label>
                                                    <input type="text" id="default_field" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line(APP . '_db_label_comment') ?></label>
                                                    <input type="text" id="comment_field" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="plugins">
                                        <h4><?php echo $this->lang->line(APP . '_plugin_subtitle_config') ?></h4>
                                        <div class="list-group">
                                            <?php
                                            if (count($plugins_input)) {
                                                foreach ($plugins_input as $plugin) {
                                                    ?>
                                                    <div class="list-group-item">
                                                        <label>
                                                            <input type="checkbox" class="plugin_field" value="<?php echo $plugin['plugin']; ?>"> <?php echo $plugin['name'] ?>
                                                        </label>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div id="msg-modal" class="hide alert alert-danger"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line(APP . '_btn_close') ?></button>
                                <button type="button" class="btn btn-primary" id="btn-save"><?php echo $this->lang->line(APP . '_btn_save') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Modal Select-->
                <div id="modal-select" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4><?php echo $this->lang->line(APP . '_title_config_select') ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4 options-field hide">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line(APP . '_label_options') ?></label>
                                            <select id="options_field" class="form-control select-options">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_select') ?></option>
                                                <?php
                                                if ($sections) {
                                                    foreach ($sections as $arr) {
                                                        ?>
                                                        <option value="<?php echo $arr['table'] ?>"><?php echo $arr['name'] ?> (<?php echo $arr['table'] ?>)</option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 label-options-field hide">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line(APP . '_label_label') ?></label>
                                            <select id="label_options_field" class="form-control select-label">
                                                <?php
                                                if ($label_options) {
                                                    ?>
                                                    <option value=""><?php echo $this->lang->line(APP . '_option_select') ?></option>
                                                    <?php
                                                    foreach ($label_options as $col) {
                                                        ?>
                                                        <option value="<?php echo $col ?>"><?php echo $col ?></option>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value=""><?php echo $this->lang->line(APP . '_option_select') ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 trigger-field hide">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line(APP . '_label_select_trigger') ?></label>
                                            <select id="trigger_select_field" class="form-control">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_select') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line(APP . '_btn_close') ?></button>
                                <button class="btn btn-primary" id="btn-save-select" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line(APP . '_btn_save') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Modal Upload-->
                <div id="modal-upload" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4><?php echo $this->lang->line(APP . '_title_config_upload') ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <label title=""><?php echo $this->lang->line(APP . '_label_extensions') ?></label>
                                        <input type="text" class="input-large form-control" id="extensions_allowed" placeholder="<?php echo $this->lang->line(APP . '_placeholder_extensions') ?>">
                                    </div>
                                </div>
                                <div id="config-upload-image">
                                    <hr>
                                    <h4 class="text-center"><?php echo $this->lang->line(APP . '_label_resize') ?></h4>
                                    <div class="row form-group">
                                        <div class="col-sm-12">
                                            <select class="input-large form-control" id="input_image_resize">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                <option value="true"><?php echo $this->lang->line(APP . '_option_yes') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_width') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_x">

                                        </div>
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_height') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_y">

                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_auto_resize') ?></label>
                                            <select class="input-large form-control" id="input_image_ratio">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                <option value="true"><?php echo $this->lang->line(APP . '_option_yes') ?></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_auto_resize_width') ?></label>
                                            <select class="input-large form-control" id="input_image_ratio_x">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                <option value="true"><?php echo $this->lang->line(APP . '_option_yes') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_auto_resize_height') ?></label>
                                            <select class="input-large form-control" id="input_image_ratio_y">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                <option value="true"><?php echo $this->lang->line(APP . '_option_yes') ?></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_crop') ?></label>
                                            <select class="input-large form-control" id="input_image_ratio_crop">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                <option value="true"><?php echo $this->lang->line(APP . '_crop_option_align_center') ?></option>
                                                <option value="L"><?php echo $this->lang->line(APP . '_crop_option_align_left') ?></option>
                                                <option value="R"><?php echo $this->lang->line(APP . '_crop_option_align_right') ?></option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_fill') ?></label>
                                            <select class="input-large form-control" id="input_image_ratio_fill">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                <option value="true"><?php echo $this->lang->line(APP . '_fill_option_align_center') ?></option>
                                                <option value="L"><?php echo $this->lang->line(APP . '_fill_option_align_left') ?></option>
                                                <option value="R"><?php echo $this->lang->line(APP . '_fill_option_align_right') ?></option>
                                            </select>

                                        </div>
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_background_color') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_background_color">

                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-center"><?php echo $this->lang->line(APP . '_subtitle_convert') ?></h4>
                                    <div class="row form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line(APP . '_label_convert') ?></label>
                                            <select class="input-large form-control" id="input_image_convert">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                <option value="jpg">JPG/JPEG</option>
                                                <option value="png">PNG</option>
                                                <option value="gif">GIF</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-center"><?php echo $this->lang->line(APP . '_subtitle_water_mark') ?></h4>
                                    <div class="row form-group">
                                        <div class="col-sm-4">
                                            <label title=""><?php echo $this->lang->line(APP . '_label_text') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_text">
                                        </div>
                                        <div class="col-sm-4">
                                            <label><?php echo $this->lang->line(APP . '_label_text_color') ?></label>
                                            <input type="color" class="input-large form-control" id="input_image_text_color">
                                        </div>
                                        <div class="col-sm-4">
                                            <label><?php echo $this->lang->line(APP . '_label_background_text') ?></label>
                                            <input type="color" class="input-large form-control" id="input_image_text_background">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-4">
                                            <label><?php echo $this->lang->line(APP . '_label_opacity_text') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_text_opacity">
                                        </div>
                                        <div class="col-sm-4">
                                            <label><?php echo $this->lang->line(APP . '_label_opacity_background') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_text_background_opacity">
                                        </div>
                                        <div class="col-sm-4">
                                            <label ><?php echo $this->lang->line(APP . '_label_padding') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_text_padding">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_position') ?></label>
                                            <select class="input-large form-control" id="input_image_text_position">
                                                <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                <option value="TL"><?php echo $this->lang->line(APP . '_position_option_top_left') ?></option>
                                                <option value="TR"><?php echo $this->lang->line(APP . '_position_option_top_right') ?></option>
                                                <option value="L"><?php echo $this->lang->line(APP . '_position_option_center_left') ?></option>
                                                <option value="R"><?php echo $this->lang->line(APP . '_position_option_center_right') ?></option>
                                                <option value="BL"><?php echo $this->lang->line(APP . '_position_option_bottom_left') ?></option>
                                                <option value="BR"><?php echo $this->lang->line(APP . '_position_option_bottom_right') ?></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_orientation') ?></label>
                                            <select class="input-large form-control" id="input_image_text_direction">
                                                <option value=""><?php echo $this->lang->line(APP . '_orientation_option_horizontal') ?></option>
                                                <option value="v"><?php echo $this->lang->line(APP . '_orientation_option_vertical') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_position_x') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_text_x">
                                        </div>
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line(APP . '_label_position_y') ?></label>
                                            <input type="text" class="input-large form-control" id="input_image_text_y">
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-center"><?php echo $this->lang->line(APP . '_subtitle_thumbnails') ?></h4>
                                    <div class="thumbnails">
                                        <div class="row form-group">
                                            <div class="col-sm-3">
                                                <label><?php echo $this->lang->line(APP . '_label_preffix') ?>*</label>
                                                <input type="text" class="input-large form-control image_thumb_preffix">
                                            </div>
                                            <div class="col-sm-3">
                                                <label><?php echo $this->lang->line(APP . '_label_width') ?>*</label>
                                                <input type="text" class="input-large form-control image_thumb_width">
                                            </div>
                                            <div class="col-sm-3">
                                                <label><?php echo $this->lang->line(APP . '_label_height') ?></label>
                                                <input type="text" class="input-large form-control image_thumb_height">
                                            </div>
                                            <div class="col-sm-3">
                                                <label><?php echo $this->lang->line(APP . '_label_crop') ?></label>
                                                <select class="input-large form-control image_thumb_ratio_crop">
                                                    <option value=""><?php echo $this->lang->line(APP . '_option_no') ?></option>
                                                    <option value="true"><?php echo $this->lang->line(APP . '_crop_option_align_center') ?></option>
                                                    <option value="L"><?php echo $this->lang->line(APP . '_crop_option_align_left') ?></option>
                                                    <option value="R"><?php echo $this->lang->line(APP . '_crop_option_align_right') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="btn btn-default" id="btn-add-thumbnail"><span class="fa fa-plus"></span> <?php echo $this->lang->line(APP . '_btn_thumbnail') ?></a><br>
                                    <br><br>
                                    <a class="btn btn-default btn-block" id="btn_refresh_image"><?php echo $this->lang->line(APP . '_btn_update_image') ?></a>
                                    <strong><?php echo $this->lang->line(APP . '_label_pre_visualization') ?></strong><br>
                                    <img id="image_example" src="<?php echo base_url_app('sections/image-example'); ?>" style="background-image: url('<?php echo base_url(APP_ASSETS . 'images/transp.gif'); ?>') !important; max-width:100%;">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line(APP . '_btn_close') ?></button>
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line(APP . '_btn_save') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var LANG = {
        label_loading: '<?php echo $this->lang->line(APP . '_loading') ?>',
        label_preffix: '<?php echo $this->lang->line(APP . '_label_preffix') ?>',
        label_width: '<?php echo $this->lang->line(APP . '_label_width') ?>',
        label_height: '<?php echo $this->lang->line(APP . '_label_height') ?>',
        label_crop: '<?php echo $this->lang->line(APP . '_label_crop') ?>',
        option_crop_center: '<?php echo $this->lang->line(APP . '_crop_option_align_center') ?>',
        option_crop_left: '<?php echo $this->lang->line(APP . '_crop_option_align_left') ?>',
        option_crop_right: '<?php echo $this->lang->line(APP . '_crop_option_align_right') ?>',
        option_no: '<?php echo $this->lang->line(APP . '_option_no') ?>',
        option_select: '<?php echo $this->lang->line(APP . '_option_select') ?>',
        label_attribute: '<?php echo $this->lang->line(APP . '_html_label_attribute') ?>',
        label_value: '<?php echo $this->lang->line(APP . '_html_label_value') ?>',
        error_all_fields_required: '<?php echo $this->lang->line(APP . '_all_fields_required') ?>',
        error_name_exists: '<?php echo $this->lang->line(APP . '_name_exists') ?>',
        error_column_exists: '<?php echo $this->lang->line(APP . '_column_name_exists') ?>',
        error_column_equals_table: '<?php echo $this->lang->line(APP . '_column_equals_table') ?>',
    };
</script>