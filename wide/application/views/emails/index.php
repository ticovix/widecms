<div class="header">
    <div class="pull-right" align="center">
        <a href="http://widedevelop.com/webmail" target="_blank">
            <small>Acessar</small><br>
            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/webmail.png" style="height:30px;">
        </a>
    </div>
    <h1 class="page-title">E-mails</h1>
    <p> Nesta área você pode gerenciar as contas de e-mail associadas aos seus domínios. </p>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li class="active">E-mails</li>
</ul>

<div class="container-fluid">
    <div class="row-fluid">
        <?php if ($create_email) { ?>
            <form class="well" method="POST">
                <table class="table">
                    <tr>
                        <th colspan="2">
                            Adicionar novo e-mail
                        </th>
                    </tr>
                    <tr>
                        <td width="200">E-mail:</td>
                        <td class="input-group">
                            <input type="text" class="form-control" name="name" value="<?php echo $name ?>"> 
                            <div class="input-group-addon">@</div>
                            <select class="form-control" name="domain">
                                <?php foreach ($domains as $obj) {
                                    ?>
                                    <option <?php if ($domain == $obj->domain) { ?>selected="selected"<?php } ?>><?php echo $obj->domain ?></option>
                                <?php }
                                ?>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Senha:</td>
                        <td class="input-group">
                            <input type="password" name="pass" class="form-control input-pass" value="<?php echo $pass ?>"> 
                            <a href="#gerar-senha" class="btn btn-default generate-pass input-group-addon" data-toggle="modal">Gerar senha</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Senha (repetir):</td>
                        <td>
                            <input type="password" name="re_pass" class="form-control re-input-pass" value="<?php echo $re_pass ?>">
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle">Quota da Caixa do Correio:</td>
                        <td>
                            <div class="input-group">
                                <label class="input-group-addon">
                                    <input type="radio" checked="checked" name="quota" value="1" <?php if ($quota == '1') { ?>checked="checked"<?php } ?>> 
                                </label>
                                <input type="text" class="form-control input_quota" value="<?php echo $input_quota ?>" name="input_quota">
                                <div class="input-group-addon">
                                    <small>MB (Máximo de 2048 MB)</small>
                                </div>
                            </div>
                            <input type="radio" name="quota" value="0" <?php if ($quota == '0') { ?>checked="checked"<?php } ?>> Ilimitado
                        </td>
                    </tr>
                </table>
                <div class="text-right">
                    <input class="btn btn-primary" value="Salvar" name="save" type="submit">
                </div>
            </form>
        <?php } ?>
        <div class="well">
            <table class="table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Uso / Quota / %</th>
                        <th colspan="3"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($data) > 0) {
                        foreach ($data as $obj) {
                            ?>
                            <tr>
                                <td class="notranslate">
                                    <?php echo $obj->email ?>
                                </td>
                                <td>
                                    <?php echo ceil($obj->diskused) ?> / <?php echo ($obj->diskquota > 0) ? ceil($obj->diskquota) : '∞'; ?> <small>MB</small><br>
                                    <div class="diskquota"><div class="diskused" style="width:<?php echo $obj->diskusedpercent ?>%; <?php if ($obj->diskusedpercent == '100') { ?>background-color:red;<?php } ?>"></div></div>
                                </td>
                                <?php if ($safe_edit_pass) { ?><td width="30"><a href="javascript:void(0);" class="bt_change_pass">Alterar senha</a></td><?php } ?>
                                <?php if ($safe_edit_quota) { ?><td width="30"><a href="javascript:void(0);" class="bt_change_quota">Alterar quota</a></td><?php } ?>
                                <?php if ($safe_delete_email) { ?><td width="30" valign="middle"><a href="?delete=true&domain=<?php echo $obj->domain ?>&user=<?php echo $obj->user ?>" class="bt_delete">Excluir</a></td><?php } ?>
                            </tr>
                            <?php if ($safe_edit_quota) { ?>
                                <tr style="display:none; background-color:#eee;" class="change_quota well">
                                    <td colspan="6" align="center">
                                        <form method="POST">
                                            <input type="hidden" name="user" value="<?php echo $obj->user ?>">
                                            <input type="hidden" name="domain" value="<?php echo $obj->domain ?>">
                                            <table class="no-border align-center">
                                                <tr>
                                                    <td class="text-right" valign="middle">Quota da Caixa do Correio:</td>
                                                    <td>
                                                        <div class="input-group">
                                                            <label class="input-group-addon">
                                                                <input type="radio" checked="checked" name="quota" value="1"> 
                                                            </label>
                                                            <input type="text" class="form-control input_quota" name="input_quota"> 
                                                            <div class="input-group-addon">
                                                                <small>MB (Máximo de 2048 MB)</small>
                                                            </div>
                                                        </div>
                                                        <input type="radio" name="quota" value="0"> Ilimitado
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">&nbsp;</td>
                                                    <td class="text-right"><button class="btn bt_cancel">Cancelar</button> <input type="submit" value="Alterar Quota" name="quota_change" class="btn btn-primary"></td>
                                                </tr>
                                            </table>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($safe_edit_pass) { ?>
                                <tr style="display:none; background-color:#eee;" class="change_pass well">
                                    <td colspan="6" align="center">
                                        <form method="post">
                                            <input type="hidden" name="user" value="<?php echo $obj->user ?>">
                                            <input type="hidden" name="domain" value="<?php echo $obj->domain ?>">
                                            <table class="no-border align-center" cellpadding="5">
                                                <tr>
                                                    <td class="text-right">Senha:</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input type="password" class="form-control input-pass" name="pass">
                                                                <a href="#gerar-senha" class="btn btn-default generate-pass input-group-addon" data-toggle="modal">Gerar senha</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" valign="top">Senha(repetir):</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="password" class="form-control re-input-pass" name="re_pass">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">&nbsp;</td>
                                                    <td class="text-right">
                                                        <button class="btn bt_cancel">Cancelar</button> 
                                                        <input type="submit" value="Alterar Senha" class="btn btn-primary" name="pass_change">
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php
                        }
                    } else {
                        ?>
                        <tr><td>Nenhum e-mail cadastrado.</td></tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
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
        return "<?= ROUTES::baseLink() ?>";
    }
</script>
<script type="text/javascript" src="<?= ROUTES::baseUrl() ?>/template/js/user.js"></script>
<script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/jquery.meio.js"></script>
<script type="text/javascript">
    $(function () {
        $('.input_quota').setMask({mask: '9999'});
        $('.bt_change_pass').click(function () {
            var position = $('.bt_change_pass').index(this);
            $(".change_quota").hide();
            $(".change_pass").hide();
            $(".change_pass").eq(position).show();
        });
        $('.bt_change_quota').click(function () {
            var position = $('.bt_change_quota').index(this);
            $(".change_quota").hide();
            $(".change_pass").hide();
            $(".change_quota").eq(position).show();
        });
        $('.bt_cancel').click(function () {
            $(".change_quota").hide();
            $(".change_pass").hide();
        });
        $(".bt_delete").click(function (e) {
            if (!confirm("Deseja realmente deletar esse e-mail?")) {
                return false;
            }
        });

    });
</script>

