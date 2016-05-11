<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<!-- page content -->
<div class="right_col" role="main">

    <ul class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
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
                    
                    <?php echo form_open(null, ['method' => 'get', 'class' => 'form-group']); ?>
                    <div class="input-group">
                        <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar usuário" class="input-sm form-control"> 
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
                        </span>
                    </div>
                    <div class="btn-toolbar">
                        <a href="<?php echo base_url_app('create'); ?>" class="btn btn-primary"><i class="icon-plus"></i> Novo usuário</a>
                        <div class="btn-group"></div>
                    </div>
                    <?php echo form_close(); ?>
                    <?php echo form_open('users/delete') ?>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Remoção</h4>
                                </div>
                                <div class="modal-body">
                                    Deseja realmente remover este(s) registro(s)?
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" login="">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                                    <button type="submit" class="btn btn-primary">Sim</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#myModal" role="button" data-toggle="modal" class="btn btn-danger hide" id="btn-del-all"><i class="fa fa-remove remove_register"></i> Remover todos selecionados</a>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nome</th>
                                <th>Login</th>
                                <th>Email</th>
                                <th style="width: 50px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($users) {
                                foreach ($users as $user) {
                                    $root = $user['root'];
                                    ?>
                                    <tr>
                                        <td width="10"><input type="checkbox" name="del[]" <?php if ($user['id'] == '1') { ?>disabled=""<?php } ?> value="<?php echo $user['id']; ?>" class="multiple_delete"></td>
                                        <td>
                                            <?php echo $user["name"] ?> <?php echo $user["last_name"] ?>
                                        </td>
                                        <td><?php echo $user["login"] ?></td>
                                        <td><?php echo $user["email"] ?></td>
                                        <td>
                                            <?php if ($root != '1' or $user_logged['root'] == '1') { ?><a href="<?php echo base_url_app('edit/' . $user["login"]); ?>"><i class="fa fa-pencil"></i></a><?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr><td colspan="5"><strong>Foram encontrados <?php echo $total ?> registros.</strong></td></tr>
                                <?php
                            } else {
                                echo '<tr><td colspan="5">Nenhum registro encontrado.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php echo form_close(); ?>
                    <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
                </div>
            </div>
        </div>
    </div>
</div>