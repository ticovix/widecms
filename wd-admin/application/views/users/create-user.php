<div class="header">
    <h1 class="page-title">Criar usuário</h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li><a href="<?php echo ROUTES::baseLink() ?>/users">Usuários</a></li>
    <li class="active">Criar usuário</li>
</ul>

<div class="container-fluid">
    <div class="row-fluid">
        <form id="tab" method="post">                    
            <div class="btn-toolbar">
                <input class="btn btn-primary" value="Salvar" name="send" type="submit">
                <div class="btn-group">
                </div>
            </div>

            <div class="well">
                <ul class="nav nav-tabs margin-bottom-15">
                    <li class="active"><a href="#home" data-toggle="tab">Perfil</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="home">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="name" value="<?php echo $name ?>" class="form-control">
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
                            <label>Login</label>
                            <input type="text" name="login" value="<?php echo $login ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Senha</label>
                            <div class="input-group">
                                <input type="password" name="password" value="<?php echo $password ?>" class="form-control input-pass"> 
                                <a href="#gerar-senha" class="btn btn-default generate-pass input-group-addon" data-toggle="modal">Gerar senha</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Observações</label>
                            <textarea name="obs" rows="3" class="form-control"><?php echo $obs ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    </div>
</div>
<script type="text/javascript">
    var USER = function () {
        return "<?= ROUTES::getURL(1) ?>";
    }
    var URL = function () {
        return "<?= ROUTES::baseLink() ?>";
    }
</script>
<script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/user.js"></script>