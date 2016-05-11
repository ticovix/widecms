<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<!-- page content -->
<div class="right_col" role="main">

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
                    <h2><?php echo $title ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php
                    echo getErrors();
                    if ($this->uri->segment('4') != 'edit-section' or $this->uri->segment('4') == 'edit-section' && $fields) {
                        echo form_open(null);
                        ?>
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
                                    <label>Diretório* <span class="fa fa-question-circle fa-fw" title="Diretório em /application/views/project/[diretório]"></span></label>
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
                        <div id="fields">
                            <?php
                            $start_total = 3;
                            $total = count($this->input->post('name_field'));
                            if ($total > 0 && $total < $start_total) {
                                $total = $start_total;
                            } elseif ($total <= 0 && $fields) {
                                $total = count($fields);
                                if ($total < $start_total) {
                                    $total = $start_total;
                                }
                            } elseif ($total <= 0 && !$fields) {
                                $total = $start_total;
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
                                $remove = (isset($fields[$i]['remove'])) ? $fields[$i]['remove'] : '';
                                $plugin_field = (isset($fields[$i]['plugin'])) ? $fields[$i]['plugin'] : '';
                                $options_field = (isset($fields[$i]['options'])) ? $fields[$i]['options'] : '';
                                $select_trigger_field = (isset($fields[$i]['trigger_select'])) ? $fields[$i]['trigger_select'] : '';
                                $label_options_field = (isset($fields[$i]['label_options'])) ? $fields[$i]['label_options'] : '';
                                $label_options = (isset($fields[$i]['label_options_'])) ? $fields[$i]['label_options_'] : '';
                                ?>
                                <div class="field">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Nome</label>
                                                <input type="text" name="name_field[]" maxlength="45" value="<?php echo set_value('name_field[' . $i . ']', $name_field) ?>" class="form-control dig_name">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Input</label>
                                                <select name="input_field[]" class="form-control select-input">
                                                    <option value="1" <?php echo set_select('input_field[' . $i . ']', '') ?>>Selecione</option>
                                                    <?php
                                                    if (count($inputs)) {
                                                        foreach ($inputs as $input) {
                                                            ?>
                                                            <option value="<?php echo $input['value']; ?>" <?php echo set_select('input_field[' . $i . ']', $input['value'], ($type_field == $input['value'])) ?>><?php echo $input['name'] ?></option>        
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 options-field <?php if ($type_field != 'select' && $type_field != 'checkbox' && $type_field != 'radio') { ?>hide<?php } ?>">
                                            <div class="form-group">
                                                <label>Opções <span class="fa fa-question-circle fa-fw" title="Opções para seleção"></span></label>
                                                <select name="options_field[]" class="form-control select-options">
                                                    <option value="">Selecione</option>
                                                    <?php
                                                    if ($sections) {
                                                        foreach ($sections as $arr) {
                                                            ?>
                                                            <option value="<?php echo $arr['table'] ?>" <?php echo set_select('options_field[' . $i . ']', $arr['id'], ($options_field == $arr['table'])) ?>><?php echo $arr['name'] ?> (<?php echo $arr['table'] ?>)</option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 label-options-field <?php if ($type_field != 'select' && $type_field != 'checkbox' && $type_field != 'radio') { ?>hide<?php } ?>">
                                            <div class="form-group">
                                                <label>Label Opções <span class="fa fa-question-circle fa-fw" title="Nome da coluna onde estão localizado os labels para exibir no campo de seleção"></span></label>
                                                <select name="label_options_field[]" class="form-control select-label">
                                                    <?php
                                                    if ($label_options) {
                                                        ?>
                                                        <option value="">Selecione</option>    
                                                        <?php
                                                        foreach ($label_options as $col) {
                                                            ?>
                                                            <option value="<?php echo $col ?>" <?php echo set_select('label_options_field[' . $i . ']', $col, ($col == $label_options_field)) ?>><?php echo $col ?></option>    
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
                                        <div class="col-md-2 trigger-field <?php if ($type_field != 'select') { ?>hide<?php } ?>">
                                            <div class="form-group">
                                                <label>Select Gatilho <span class="fa fa-question-circle fa-fw" title="Serão listado as opções quanto o select indicado for selecionado."></span></label>
                                                <select name="trigger_select_field[]" class="form-control">
                                                    <option value="">Selecione</option>
                                                    <?php
                                                    if ($selects) {
                                                        foreach ($selects as $select) {
                                                            $col = $select['column'];
                                                            $label = $select['label'];
                                                            if ($col != $column) {
                                                                ?>
                                                                <option value="<?php echo $col ?>" <?php echo set_select('select_trigger_field[' . $i . ']', $col, ($select_trigger_field == $col)) ?>><?php echo $label ?> (<?php echo $col ?>)</option>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Listagem <span class="fa fa-question-circle fa-fw" title="Exibir campo na listagem de registros"></span></label>
                                                <select name="list_registers_field[]" class="form-control">
                                                    <option value="0" <?php echo set_select('list_registers_field[' . $i . ']', '0', ($list_registers == '0')) ?>>Não</option>
                                                    <option value="1" <?php echo set_select('list_registers_field[' . $i . ']', '1', ($list_registers == '1')) ?>>Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Coluna <span class="fa fa-question-circle fa-fw" title="Nome da coluna no banco de dados"></span></label>
                                                <input type="text" name="column_field[]" maxlength="45" value="<?php echo set_value('column_field[' . $i . ']', $column) ?>" class="form-control column">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Tipo</label>
                                                <select name="type_field[]" class="form-control">
                                                    <option value="">Selecione</option>
                                                    <?php
                                                    if (count($types)) {
                                                        foreach ($types as $type) {
                                                            ?>
                                                            <option value="<?php echo $type['type']; ?>" <?php echo set_select('type_field[' . $i . ']', $type['type'], ($type_column == $type['type'])) ?>><?php echo $type['type'] ?></option>        
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Limite <span class="fa fa-question-circle fa-fw" title="Limite da coluna no banco de dados"></span></label>
                                                <input type="text" name="limit_field[]" value="<?php echo set_value('limit_field[' . $i . ']', $limit) ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Plugin</label>
                                                <select name="plugin_field[]" class="form-control">
                                                    <option value="">Selecione</option>
                                                    <?php
                                                    if (count($plugins_input)) {
                                                        foreach ($plugins_input as $plugin) {
                                                            ?>
                                                            <option value="<?php echo $plugin['plugin']; ?>" <?php echo set_select('plugin_field[' . $i . ']', $plugin['plugin'], ($plugin_field == $plugin['plugin'])) ?>><?php echo $plugin['name'] ?></option>        
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label title="Campo de preenchimento obrigatório no formulário e coluna do tipo NOT NULL no banco de dados">Obrigatório</label>
                                                <select name="required_field[]" class="form-control">
                                                    <option value="0" <?php echo set_select('required_field[' . $i . ']', '0', ($required == '0')) ?>>Não</option>
                                                    <option value="1" <?php echo set_select('required_field[' . $i . ']', '1', ($required == '1')) ?>>Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label title="Campo de preenchimento obrigatório no formulário e coluna do tipo NOT NULL no banco de dados">Valor único</label>
                                                <select name="unique_field[]" class="form-control">
                                                    <option value="0" <?php echo set_select('unique_field[' . $i . ']', '0', ($unique == '0')) ?>>Não</option>
                                                    <option value="1" <?php echo set_select('unique_field[' . $i . ']', '1', ($unique == '1')) ?>>Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <?php
                                            if ($fields && !empty($name_field)) {
                                                ?>
                                                <label class="remove-field">
                                                    Remover campo<br>
                                                    <input type="checkbox" name="remove_field[]" value="<?php echo $i ?>" <?php echo set_checkbox('remove_field[' . $i . ']', true, ($remove === true)) ?>>
                                                </label>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default" id="addField"> <span class="fa fa-plus"></span> Adicionar novo campo</button>
                                <div class="form-group text-right">
                                    <input class="btn btn-primary" value="Salvar" name="send" type="submit">
                                </div>
                            </div>
                        </div>
                        <?php
                        echo form_close();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>