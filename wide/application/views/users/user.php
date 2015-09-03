<div class="header">
    <h1 class="page-title">Editar usuário</h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li><a href="<?php echo ROUTES::baseLink() ?>/users">Usuários</a></li>
    <li class="active">Editar usuário</li>
</ul>

<div class="container-fluid">
    <div class="row-fluid">
        <form id="tab" method="post">                   
            <div class="btn-toolbar">
                <input type="submit" value="Salvar" name="send" class="btn btn-primary">
                <div class="btn-group">
                </div>
            </div>
            <div class="well">
                <ul class="nav nav-tabs margin-bottom-15">
                    <li class="active"><a href="#home" data-toggle="tab">Perfil</a></li>
                    <li><a href="#profile" data-toggle="tab">Alterar senha</a></li>
                    <?php if ($safe_permission == 1) { ?>
                        <li><a href="#access" data-toggle="tab">Permissões de acesso</a></li>
                    <?php } ?>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="home">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="name" value="<?php echo $name ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Login</label>
                            <input type="text" name="login" value="<?php echo $login ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="root" class="form-control">
                                <option <?php if ($status == 1) { ?>selected="selected"<?php } ?> value="1">Ativado</option>
                                <option <?php if ($status == 0 && !empty($status)) { ?>selected="selected"<?php } ?> value="0">Desativado</option>
                            </select>
                        </div>
                        <?php
                        if ($profile->root == 1) {
                            ?>
                            <div class="form-group">
                                <label>Permissões</label>
                                <select name="root" class="form-control">
                                    <option <?php if ($root == 0) { ?>selected="selected"<?php } ?> value="0">Básica</option>
                                    <option <?php if ($root == 1) { ?>selected="selected"<?php } ?> value="1">Root</option>
                                </select>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label>Sobrenome</label>
                            <input type="text" name="lastname" value="<?php echo $lastname ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" value="<?php echo $email ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Observações</label>
                            <textarea name="obs" rows="3" class="form-control"><?php echo $obs ?></textarea>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile">
                        <label>Nova senha</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control input-pass"> 
                            <a href="#gerar-senha" class="generate-pass btn btn-default input-group-addon" data-toggle="modal">Gerar senha</a>
                        </div>
                    </div>
                    <?php if ($safe_permission == 1) { ?>
                        <div class="tab-pane fade" id="access">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3>Permissões de navegação administrativa</h3>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th width="50">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($navigation as $value) {
                                                $stats = $value["stats"];
                                                ?>
                                                <tr>
                                                    <td><?php echo (!empty($value["subpage"])) ? "&nbsp;&nbsp;" . $value["title"] : '<strong>'.$value["title"].'</strong>' ?></td>
                                                    <td style="text-align:center" class="stats_load">
                                                        <?php
                                                        if ($stats == 1) {
                                                            ?>
                                                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/tick.png" data-id="<?php echo $value["id"] ?>" data-type="_menu" class="modify_stats" title="Ativado" style="height:24px;">
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/cross.png" data-id="<?php echo $value["id"] ?>" data-type="_menu" class="modify_stats" title="Desativado" style="height:24px;">
                                                            <?php
                                                        }
                                                        ?>
                                                        <span class="loading"></span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <h3>Permissões de páginas do site</h3>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Página</th>
                                                <th width="50">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (count($pages) > 0) {
                                                foreach ($pages as $value) {
                                                    if($value['access_user']=='' or $value['access_user']=='1' or $profile->root=='1'){
                                                        $funct = $PAGES->list_functions(array("page" => $value["id"]));
                                                        $total_functions = $funct->rowCount();
                                                        $list_functions = $funct->fetchAll(PDO::FETCH_OBJ);
                                            ?>
                                            <tr>
                                                <td><strong><?php echo $value["name"] ?></strong></td>
                                                <td class="stats_load">
                                                    <?php
                                                        if ($value['access_user'] == '1' or $value['access_user']=='') {
                                                            ?>
                                                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/tick.png" data-id="<?php echo $value["id"] ?>" data-type="_page" class="modify_stats" title="Ativado" style="height:24px;">
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/cross.png" data-id="<?php echo $value["id"] ?>" data-type="_page" class="modify_stats" title="Desativado" style="height:24px;">
                                                            <?php
                                                        }
                                                    ?>
                                                    <span class="loading"></span>
                                                </td>
                                            </tr>
                                            <?php
                                                        if ($total_functions > 1 or $profile->root == 1) {
                                                            foreach ($list_functions as $obj) {
                                            ?>
                                            <tr>
                                                <td>&nbsp;&nbsp;<?php echo $obj->name ?></td>
                                                <td class="stats_load">
                                                    <?php
                                                        if ($obj->access_user == '1' or $obj->access_user=='') {
                                                            ?>
                                                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/tick.png" data-id="<?php echo $obj->id ?>" data-type="_function" class="modify_stats" title="Ativado" style="height:24px;">
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/cross.png" data-id="<?php echo $obj->id ?>" data-type="_function" class="modify_stats" title="Desativado" style="height:24px;">
                                                            <?php
                                                        }
                                                    ?>
                                                    <span class="loading"></span>
                                                </td>
                                            </tr>
                                            <?php 
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="gerar-senha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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

<script type="text/javascript">
    var USER = function () {
        return "<?= ROUTES::getURL(1) ?>";
    }
    var URL = function () {
        return "<?= ROUTES::baseUrl() ?>";
    }
</script>
<script type="text/javascript" src="<?= ROUTES::baseUrl() ?>/template/js/user.js"></script>