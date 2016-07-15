<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url() ?>">Home</a></li>
    <li><a href="<?php echo base_url_app() ?>">Projetos</a></li>
    <li><a href="<?php echo base_url_app('project/' . $project['slug']) ?>"><?php echo $project['name'] ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $project['slug'] . '/' . $page['slug']) ?>"><?php echo $page['name'] ?></a></li>
    <li class="active"><?php echo $title ?></li>
</ul>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <?php
                if ($this->uri->segment('6') == 'create') {
                    ?>
                    <a href="#modal-import" class="btn btn-primary pull-right" data-toggle="modal"><span class="fa fa-fw fa-arrow-down"></span> Importar tabela</a>
                    <?php
                }
                ?>
                <h2><?php echo $title ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php
                echo getErrors();
                if ($this->uri->segment('4') != 'edit-section' or $this->uri->segment('4') == 'edit-section' && $fields) {
                    echo form_open(null);
                    ?>
                    <input type="hidden" name="import" id="import-value" value="false">
                    <h3>Dados básicos da seção</h3>
                    <p>Dados para configuração da página e tabela do banco de dados.</p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome*</label>
                                <input type="text" name="name" id="dig_name" value="<?php echo set_value('name', $name) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Diretório* <span class="fa fa-question-circle fa-fw" title="Diretório de configuração <?php echo APP_PATH; ?>views/project/<?php echo $project['slug'] ?>/<?php echo $page['slug'] ?>/[diretório]"></span></label>
                                <input type="text" name="directory" id="dir_name" value="<?php echo set_value('directory', $directory) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tabela* <span class="fa fa-question-circle fa-fw" title="Nome da tabela no banco de dados"></span></label>
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
                                <label>Status* <span class="fa fa-question-circle fa-fw" title="Se desativado, a página não será exibida no modo cliente"></span></label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo set_select('status', '1', ($status == '1')) ?>>Ativado</option>
                                    <option value="0" <?php echo set_select('status', '0', ($status == '0')) ?>>Desativado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3>Configurações avançadas da seção</h3>
                    <p>Configuração da página de listagem, edição, inserção de registros e colunas do banco de dados.</p>
                    <button type="button" class="btn btn-default btn-primary" id="btn-add-field" data-toggle="modal" data-target="#modal-new-field"> <span class="fa fa-plus"></span> Adicionar novo campo</button>
                    <table class="table table-striped table-responsive table-bordered" id="fields">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Input</th>
                                <th>Listagem</th>
                                <th>Coluna</th>
                                <th>Tipo</th>
                                <th width="30">Del</th>
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

                                $name_field = (isset($fields[$i]['label'])) ? $fields[$i]['label'] : '';
                                $type_field = (isset($fields[$i]['type'])) ? strtolower($fields[$i]['type']) : '';
                                $list_registers = (isset($fields[$i]['list_registers'])) ? $fields[$i]['list_registers'] : '';
                                $column = (isset($fields[$i]['column'])) ? $fields[$i]['column'] : '';
                                $type_column = (isset($fields[$i]['type_column'])) ? $fields[$i]['type_column'] : '';
                                $limit = (isset($fields[$i]['limit'])) ? $fields[$i]['limit'] : '';
                                $required = (isset($fields[$i]['required'])) ? $fields[$i]['required'] : '';
                                $unique = (isset($fields[$i]['unique'])) ? $fields[$i]['unique'] : '';
                                $default = (isset($fields[$i]['default'])) ? $fields[$i]['default'] : '';
                                $comment = (isset($fields[$i]['comment'])) ? $fields[$i]['comment'] : '';
                                $remove = (isset($fields[$i]['remove'])) ? $fields[$i]['remove'] : '';
                                $plugin_field = (isset($fields[$i]['plugins'])) ? $fields[$i]['plugins'] : '';
                                $observation = (isset($fields[$i]['observation'])) ? $fields[$i]['observation'] : '';
                                $attributes = (isset($fields[$i]['attributes'])) ? $fields[$i]['attributes'] : '';
                                /*
                                 * Vars of config to select
                                 */
                                $options_field = (isset($fields[$i]['options_table'])) ? $fields[$i]['options_table'] : '';
                                $select_trigger_field = (isset($fields[$i]['options_trigger_select'])) ? $fields[$i]['options_trigger_select'] : '';
                                $label_options_field = (isset($fields[$i]['options_label'])) ? $fields[$i]['options_label'] : '';
                                $label_options = (isset($fields[$i]['label_options_'])) ? $fields[$i]['label_options_'] : array();
                                /*
                                 * Vars of config to upload
                                 */
                                $extensions_allowed = (isset($fields[$i]['extensions_allowed'])) ? $fields[$i]['extensions_allowed'] : '';
                                $image_resize = (isset($fields[$i]['image_resize'])) ? $fields[$i]['image_resize'] : '';
                                $image_x = (isset($fields[$i]['image_x'])) ? $fields[$i]['image_x'] : '';
                                $image_y = (isset($fields[$i]['image_y'])) ? $fields[$i]['image_y'] : '';
                                $image_ratio = (isset($fields[$i]['image_ratio'])) ? $fields[$i]['image_ratio'] : '';
                                $image_ratio_x = (isset($fields[$i]['image_ratio_x'])) ? $fields[$i]['image_ratio_x'] : '';
                                $image_ratio_y = (isset($fields[$i]['image_ratio_y'])) ? $fields[$i]['image_ratio_y'] : '';
                                $image_ratio_crop = (isset($fields[$i]['image_ratio_crop'])) ? $fields[$i]['image_ratio_crop'] : '';
                                $image_ratio_fill = (isset($fields[$i]['image_ratio_fill'])) ? $fields[$i]['image_ratio_fill'] : '';
                                $image_background_color = (isset($fields[$i]['image_background_color'])) ? $fields[$i]['image_background_color'] : '';
                                $image_convert = (isset($fields[$i]['image_convert'])) ? $fields[$i]['image_convert'] : '';
                                $image_text = (isset($fields[$i]['image_text'])) ? $fields[$i]['image_text'] : '';
                                $image_text_color = (isset($fields[$i]['image_text_color'])) ? $fields[$i]['image_text_color'] : '';
                                $image_text_background = (isset($fields[$i]['image_text_background'])) ? $fields[$i]['image_text_background'] : '';
                                $image_text_opacity = (isset($fields[$i]['image_text_opacity'])) ? $fields[$i]['image_text_opacity'] : '';
                                $image_text_background_opacity = (isset($fields[$i]['image_text_background_opacity'])) ? $fields[$i]['image_text_background_opacity'] : '';
                                $image_text_padding = (isset($fields[$i]['image_text_padding'])) ? $fields[$i]['image_text_padding'] : '';
                                $image_text_position = (isset($fields[$i]['image_text_position'])) ? $fields[$i]['image_text_position'] : '';
                                $image_text_direction = (isset($fields[$i]['image_text_direction'])) ? $fields[$i]['image_text_direction'] : '';
                                $image_text_x = (isset($fields[$i]['image_text_x'])) ? $fields[$i]['image_text_x'] : '';
                                $image_text_y = (isset($fields[$i]['image_text_y'])) ? $fields[$i]['image_text_y'] : '';
                                $image_thumbnails = (isset($fields[$i]['image_thumbnails'])) ? $fields[$i]['image_thumbnails'] : '';
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
                                    <td><i class=" fa fa-<?php
                                        if (set_value('list_registers_field[' . $i . ']', $list_registers)) {
                                            echo 'check';
                                        } else {
                                            echo 'remove';
                                        }
                                        ?>"></i>
                                    </td>
                                    <td><?php echo set_value('column_field[' . $i . ']', $column) ?></td>
                                    <td><?php echo set_value('type_field[' . $i . ']', $type_column) ?></td>
                                    <td align="center"><input type="checkbox" class="check-remove" name="remove_field[<?php echo $i ?>]" title="Remover campo"> </td>
                                </tr>
                                <?php
                            }
                            if (!isset($name_field)) {
                                echo '<tr class="msg-is-empty"><td colspan="6">Nenhum campo adicionado.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group text-right">
                                <input class="btn btn-primary" value="Salvar" name="send" type="submit">
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
                                <h4 class="modal-title" id="myModalLabel">Configurar campo</h4>
                            </div>
                            <div class="modal-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="nav-tab active"><a href="#html" aria-controls="html" role="tab" data-toggle="tab">HTML</a></li>
                                    <li role="presentation" class="nav-tab"><a href="#database" aria-controls="database" role="tab" data-toggle="tab">Banco de dados</a></li>
                                    <li role="presentation" class="nav-tab"><a href="#plugins" aria-controls="plugins" role="tab" data-toggle="tab">Plugins</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="html">
                                        <h4>Configurações html do input</h4>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Nome*</label>
                                                    <input type="text" id="name_field" maxlength="45" class="form-control dig_name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Input*</label>
                                                    <div id="content-field">
                                                        <select id="input_field" class="form-control select-input">
                                                            <option value="">Selecione</option>
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
                                                    <label>Listagem</label>
                                                    <select id="list_registers_field" class="form-control">
                                                        <option value="0">Não</option>
                                                        <option value="1">Sim</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Obrigatório</label>
                                                    <select id="required_field" class="form-control">
                                                        <option value="0">Não</option>
                                                        <option value="1">Sim</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h4>Atributos adicionais do input</h4>
                                                <div id="attributes">
                                                    <div class="row attr-current">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control param_attr_field" placeholder="Atributo">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <input type="text" value="" class="form-control value_attr_field" placeholder="Valor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" id="add-attr" class="btn"><i class="fa fa-fw fa-plus"></i> Mais</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <h4>Adicionais</h4>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Observações</label>
                                                            <textarea class="form-control" id="observation_field"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="database">
                                        <h4>Configurações do campo no banco de dados</h4>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Coluna*</label>
                                                    <input type="text" id="column_field" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Tipo*</label>
                                                    <select id="type_field" class="form-control">
                                                        <option value="">Selecione</option>
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
                                                    <label>Limite</label>
                                                    <input type="text" id="limit_column_field" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Valor único</label>
                                                    <select id="unique_field" class="form-control">
                                                        <option value="0">Não</option>
                                                        <option value="1">Sim</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Padrão</label>
                                                    <input type="text" id="default_field" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Comentário</label>
                                                    <input type="text" id="comment_field" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="plugins">
                                        <h4>Plugins</h4>
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
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary" id="btn-save">Salvar</button>
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
                                <h4>Configurar Select</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4 options-field hide">
                                        <div class="form-group">
                                            <label>Opções <span class="fa fa-question-circle fa-fw" title="Opções para seleção"></span></label>
                                            <select id="options_field" class="form-control select-options">
                                                <option value="">Selecione</option>
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
                                            <label>Rótulo <span class="fa fa-question-circle fa-fw" title="Nome da coluna onde estão localizado os labels para exibir no campo de seleção"></span></label>
                                            <select id="label_options_field" class="form-control select-label">
                                                <?php
                                                if ($label_options) {
                                                    ?>
                                                    <option value="">Selecione</option>    
                                                    <?php
                                                    foreach ($label_options as $col) {
                                                        ?>
                                                        <option value="<?php echo $col ?>"><?php echo $col ?></option>    
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="">Selecione a opção</option>    
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 trigger-field hide">
                                        <div class="form-group">
                                            <label>Select Gatilho <span class="fa fa-question-circle fa-fw" title="Serão listado as opções quanto o select indicado for selecionado."></span></label>
                                            <select id="trigger_select_field" class="form-control">
                                                <option value="">Selecione</option>
                                                <?php
                                                if ($selects) {
                                                    foreach ($selects as $select) {
                                                        $col = $select['column'];
                                                        $label = $select['label'];
                                                        ?>
                                                        <option value="<?php echo $col ?>" <?php echo set_select('select_trigger_field[' . $i . ']', $col, ($select_trigger_field == $col)) ?>><?php echo $label ?> (<?php echo $col ?>)</option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
                                <button class="btn btn-primary" id="btn-save-select" data-dismiss="modal" aria-hidden="true">Salvar</button>
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
                                <h4>Configurar envio</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <label title="">Extensões permitidas</label>
                                        <input type="text" class="input-large form-control" id="extensions_allowed" placeholder="Formato de arquivos separado por virgula (Opcional)">
                                    </div>
                                </div>
                                <div id="config-upload-image">
                                    <hr>
                                    <h4 class="text-center">Redimensionamento</h4>
                                    <div class="row form-group">
                                        <div class="col-sm-12">
                                            <label>Redimensionar imagem ?</label>
                                            <select class="input-large form-control" id="input_image_resize">
                                                <option value="">Não</option>
                                                <option value="true">Sim</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label title="Difinir a largura da imagem">Largura</label>
                                            <input type="text" class="input-large form-control" id="input_image_x">

                                        </div>
                                        <div class="col-sm-6">
                                            <label title="Definir a altura da imagem">Altura</label>
                                            <input type="text" class="input-large form-control" id="input_image_y">

                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label title="Redimensionar largura e altura da imagem proporcionalmente">Redim. Altura</label>
                                            <select class="input-large form-control" id="input_image_ratio">
                                                <option value="">Não</option>
                                                <option value="true">Sim</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label title="Redimensionar largura da imagem proporcionalmente">Redim. Largura</label>
                                            <select class="input-large form-control" id="input_image_ratio_x">
                                                <option value="">Não</option>
                                                <option value="true">Sim</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label  title="Redimensionar altura da imagem proporcionalmente">Redim. proporcional</label>
                                            <select class="input-large form-control" id="input_image_ratio_y">
                                                <option value="">Não</option>
                                                <option value="true">Sim</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label title="Cortar imagem por tamanho definido">Cortar imagem</label>
                                            <select class="input-large form-control" id="input_image_ratio_crop">
                                                <option value="">Não</option>
                                                <option value="true">Sim, alinhar corte no centro</option>
                                                <option value="L">Sim, alinhar corte na esquerda</option>
                                                <option value="R">Sim, alinhar corte na direita</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label title="Preencher imagem com tamanho fixo">Preencher imagem</label>
                                            <select class="input-large form-control" id="input_image_ratio_fill">
                                                <option value="">Não</option>
                                                <option value="true">Sim, preencher alinhando imagem no centro</option>
                                                <option value="L">Sim, preencher alinhando imagem na esquerda</option>
                                                <option value="R">Sim, preencher alinhando imagem na direita</option>
                                            </select>

                                        </div>
                                        <div class="col-sm-6">
                                            <label title="Definir a cor de fundo do preenchimento da imagem">Cor de fundo</label>
                                            <input type="text" placeholder="Ex: #999999" class="input-large form-control" id="input_image_background_color">

                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-center">Conversão</h4>
                                    <div class="row form-group">
                                        <div class="col-sm-12">
                                            <label title="Coverter imagens">Converter imagem</label>
                                            <select class="input-large form-control" id="input_image_convert">
                                                <option value="">Não</option>
                                                <option value="jpg">JPG/JPEG</option>
                                                <option value="png">PNG</option>
                                                <option value="gif">GIF</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-center">Marca d'agua</h4>
                                    <div class="row form-group">
                                        <div class="col-sm-4">
                                            <label title="">Texto</label>
                                            <input type="text" class="input-large form-control" id="input_image_text">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Cor do texto</label>
                                            <input type="color" placeholder="Ex: #999999" class="input-large form-control" id="input_image_text_color">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Cor de fundo do texto</label>
                                            <input type="color" class="input-large form-control" id="input_image_text_background">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-4">
                                            <label>Opacidade do texto (0/100)</label>
                                            <input type="text" class="input-large form-control" id="input_image_text_opacity">
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Opacidade do fundo(0/100)</label>
                                            <input type="text" class="input-large form-control" id="input_image_text_background_opacity">
                                        </div>
                                        <div class="col-sm-4">
                                            <label >Espaço interno (px)</label>
                                            <input type="text" class="input-large form-control" id="input_image_text_padding">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label>Posição</label>
                                            <select class="input-large form-control" id="input_image_text_position">
                                                <option value="">Não</option>
                                                <option value="TL">Topo, Esquerda</option>
                                                <option value="TR">Topo, Direita</option>
                                                <option value="L">Centro, Esquerda</option>
                                                <option value="R">Centro, Direita</option>
                                                <option value="BL">Rodapé, Esquerda</option>
                                                <option value="BR">Rodapé, Direita</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Orientação</label>
                                            <select class="input-large form-control" id="input_image_text_direction">
                                                <option value="">Horizontal (-)</option>
                                                <option value="v">Vertical (|)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-6">
                                            <label>Posição - X (px)</label>
                                            <input type="text" class="input-large form-control" id="input_image_text_x">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Posição - Y (px)</label>
                                            <input type="text" class="input-large form-control" id="input_image_text_y">
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-center">Miniaturas</h4>
                                    <div class="thumbnails">
                                        <div class="row form-group">
                                            <div class="col-sm-3">
                                                <label title="Difinir altura do efeito reflexo">Prefixo*</label>
                                                <input type="text" class="input-large form-control image_thumb_preffix">
                                            </div>
                                            <div class="col-sm-3">
                                                <label title="Adicionar opacidade de 0 a 100 no reflexo">Largura (px)*</label>
                                                <input type="text" class="input-large form-control image_thumb_width">
                                            </div>
                                            <div class="col-sm-3">
                                                <label title="Difinir altura do efeito reflexo">Altura (px)</label>
                                                <input type="text" class="input-large form-control image_thumb_height">
                                            </div>
                                            <div class="col-sm-3">
                                                <label title="Cortar imagem por tamanho definido">Cortar imagem</label>
                                                <select class="input-large form-control image_thumb_ratio_crop">
                                                    <option value="">Não</option>
                                                    <option value="true">Sim, alinhar corte no centro</option>
                                                    <option value="L">Sim, alinhar corte na esquerda</option>
                                                    <option value="R">Sim, alinhar corte na direita</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="btn btn-default" id="btn-add-thumbnail"><span class="fa fa-plus"></span> Miniatura</a><br>
                                    <br><br>
                                    <a class="btn btn-default btn-block" id="btn_refresh_image">Atualizar visualização</a>
                                    <strong>Pré-visualização</strong><br>
                                    <img id="image_example" src="<?php echo base_url_app('sections/image-example'); ?>" style="background-image: url('<?php echo base_url(APP_ASSETS . 'images/transp.gif'); ?>') !important; max-width:100%;">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if ($this->uri->segment('6') == 'create') {
                    ?>
                    <!-- Modal -->
                    <div class="modal fade" id="modal-import" data-index="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <?php echo form_open(null, array('id' => 'form-import')); ?>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Importar tabela</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-warning">Atenção: Ao importar uma tabela, todos os campos criados anteriormente serão apagados.</div>
                                    <div class="form-group">
                                        <label for="table">Selecione a tabela</label>
                                        <select name="table" id="table-value" class="form-control">
                                            <option value="">Selecione</option>
                                            <?php
                                            if (count($tables_import) > 0) {
                                                foreach ($tables_import as $arr) {
                                                    ?>
                                                    <option class="<?php echo $arr['TABLE_NAME'] ?>"><?php echo $arr['TABLE_NAME'] ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="msg-import"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-primary" id="btn-save-import">Selecionar</button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>