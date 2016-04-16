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
    <li><a href="<?php echo base_url() ?>users">Usuários</a></li>
    <li class="active"><?php echo $title ?></li>
</ul>

<div class="container-fluid">
    <div class="row-fluid">
        <?php echo form_open(); ?>
        <div class="btn-toolbar">

        </div>
        <?php
        echo validation_errors('<p class="alert alert-danger">', '</p>');
        echo form_open(null, ['class' => 'form-horizontal']);
        ?>
        <div class="tab-pane active in" id="home">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nome*</label>
                        <input type="text" name="name" value="<?php echo set_value('name', $name) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sobrenome</label>
                        <input type="text" name="lastname" value="<?php echo set_value('lastname', $last_name) ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email*</label>
                        <input type="email" name="email" value="<?php echo set_value('email', $email) ?>" <?php if ($this->uri->segment(2) == 'edit') { ?>disabled=""<?php } ?> class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Login*</label>
                        <input type="text" name="login" value="<?php echo set_value('login', $login) ?>" <?php if ($this->uri->segment(2) == 'edit') { ?>disabled=""<?php } ?> class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Senha*</label>
                        <div class="input-group">
                            <input type="password" name="password" value="<?php echo set_value('password') ?>" class="form-control input-pass"> 
                            <a href="#gerar-senha" class="btn btn-default generate-pass input-group-addon" data-toggle="modal">Gerar senha</a>
                        </div>
                        <?php if ($this->uri->segment(2) == 'edit') { ?><small>* Preencha somente para alterar</small><?php } ?>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="<?php if($user_logged['root']==1){ echo 'col-md-4';}else{ echo 'col-md-12';}?>">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" <?php echo set_select('status', '1', ($status == '1')) ?>>Ativado</option>
                            <option value="0" <?php echo set_select('status', '0', ($status == '0')) ?>>Desativado</option>
                        </select>
                    </div>
                </div>
                <?php
                if($user_logged['root']==1){
                ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Modo desenvolvedor</label>
                        <select name="allow_dev" class="form-control">
                            <option value="1" <?php echo set_select('allow_dev', '1', ($allow_dev == '1')) ?>>Permitir</option>
                            <option value="0" <?php echo set_select('allow_dev', '0', ($allow_dev == '0')) ?>>Não permitir</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Permissões ROOT</label>
                        <select name="root" class="form-control">
                            <option value="1" <?php echo set_select('root', '1', ($root == '1')) ?>>Sim</option>
                            <option value="0" <?php echo set_select('root', '0', ($root == '0')) ?>>Não</option>
                        </select>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>

            <div class="form-group text-right">
                <input class="btn btn-primary" value="Salvar" name="send" type="submit">
            </div>
        </div>
        <?php echo form_close(); ?>
        <div class="modal fade" id="gerar-senha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Senha gerada</h4>
                    </div>
                    <div class="modal-body">
                        <h2 class="get-password"></h2>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        <button class="btn btn-primary bt-ok" data-dismiss="modal">Copiado</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>