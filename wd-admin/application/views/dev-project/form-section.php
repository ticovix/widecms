<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<div class="header">
    <h1 class="page-title"><?php echo $title ?></h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url() ?>">Home</a></li>
    <li><a href="<?php echo base_url() ?>projects">Projetos</a></li>
    <li><a href="<?php echo base_url() ?>project/<?php echo $project['slug'] ?>"><?php echo $project['name'] ?></a></li>
    <li><a href="<?php echo base_url() ?>project/<?php echo $project['slug'] ?>/<?php echo $page['slug'] ?>"><?php echo $page['name'] ?></a></li>
    <li class="active"><?php echo $title ?></li>
</ul>

<div class="container-fluid">
    <?php
    echo getErrors();
    echo form_open(null);
    ?>
    <h3>Dados básicos da seção</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Nome*</label>
                <input type="text" name="name" id="dig_name" value="<?php echo set_value('name') ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Diretório*</label>
                <input type="text" name="directory" id="dir_name" value="<?php echo set_value('directory') ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Tabela(BD)*</label>
                <input type="text" name="table" id="table_name" value="<?php echo set_value('table') ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Status*</label>
                <select name="status" class="form-control">
                    <option value="1" <?php echo set_select('status', '1') ?>>Ativado</option>
                    <option value="0" <?php echo set_select('status', '2') ?>>Desativado</option>
                </select>
            </div>
        </div>
    </div>
    <hr>
    <h3>Configurações avançadas da seção</h3>
    <p>Configuração da página de listagem, edição e inserção de registros.</p>
    <div id="fields">
        <?php
        $start_total = 3;
        $total = count($this->input->post('name_field'));
        if($total<$start_total){
            $total = $start_total;
        }
        for ($i = 0; $i < $total; $i++) {
            ?>
            <div class="field">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="name_field[]" maxlength="45" value="<?php echo set_value('name_field['.$i.']')?>" class="form-control dig_name">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Input</label>
                            <select name="input_field[]" class="form-control">
                                <option value="1" <?php echo set_select('input_field['.$i.']', '') ?>>Selecione</option>
                                <?php
                                if (count($inputs)) {
                                    foreach ($inputs as $input) {
                                        ?>
                                        <option value="<?php echo $input['name']; ?>" <?php echo set_select('input_field['.$i.']', $input['name']) ?>><?php echo $input['name'] ?></option>        
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Listagem de registros</label>
                            <select name="list_registers_field[]" class="form-control">
                                <option value="0" <?php echo set_select('list_registers_field['.$i.']', '0')?>>Não</option>
                                <option value="1" <?php echo set_select('list_registers_field['.$i.']', '1')?>>Sim</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Coluna</label>
                            <input type="text" name="column_field[]" maxlength="45" value="<?php echo set_value('column_field['.$i.']')?>" class="form-control column">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="type_field[]" class="form-control">
                                <option value="1">Selecione</option>
                                <?php
                                if (count($types)) {
                                    foreach ($types as $type) {
                                        ?>
                                        <option value="<?php echo $type['type']; ?>" <?php echo set_select('type_field['.$i.']', $type['type'])?>><?php echo $type['type'] ?></option>        
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Limite</label>
                            <input type="text" name="limit_field[]" value="<?php echo set_value('limit_field['.$i.']')?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label><input type="checkbox" value="1" name="required[]" <?php echo set_checkbox('required['.$i.']', '1')?>> Preenchimento obrigatório</label>
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
    <?php echo form_close(); ?>
</div>