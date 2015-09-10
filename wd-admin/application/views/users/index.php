<div class="header">
    <h1 class="page-title">Usuários</h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li class="active">Usuários</li>
</ul>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="btn-toolbar">
            <a href="<?php echo ROUTES::baseLink() ?>/create-user" class="btn btn-primary"><i class="icon-plus"></i> Novo usuário</a>
            <div class="btn-group"></div>
        </div>
        <div class="well">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Email</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($user_list as $value) {
                        ?>
                        <tr>
                            <td width="5"><img src="<?php if (!empty($value["image"])) { ?><?php echo $VERIFY_ACCESS->get_data_client()->address ?>/website/application/template/upload/<?php echo $value["image"] ?><?php } else { ?><?php echo ROUTES::baseUrl() ?>/template/images/no_image.gif<?php } ?>" width="15"></td>
                            <td class="notranslate">
                                <?php echo $value["name"] ?> <?php echo $value["surname"] ?>
                            </td>
                            <td class="notranslate"><?php echo $value["login"] ?></td>
                            <td class="notranslate"><?php echo $value["mail"] ?></td>
                            <td>
                                <?php if ($safe_edit == 1) { ?><a href="<?php echo ROUTES::baseLink() ?>/user/<?php echo $value["id"] ?>"><i class="icon-pencil"></i></a><?php } ?>
                                <?php if ($safe_delete == 1) { ?><a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove remove_register" data-id="<?php echo $value["id"] ?>"></i></a><?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php if ($safe_delete == 1) { ?>
            <form method="post">
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Confirmação para deletar</h4>
                            </div>
                            <div class="modal-body">

                                <p class="error-text"><i class="icon-warning-sign modal-icon"></i>Deseja realmente deletar esse usuário ?<br> <strong>Atenção:</strong> Ao confirmar você irá remover todos os registros do usuário.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                                <input type="hidden" name="register_id" value="" class="register_id">
                                <input type="submit" name="confirm" class="btn btn-danger btn_send" value="Deletar">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
</div>
<?php if ($safe_delete == 1) { ?>
    <script type="text/javascript">
        $(function () {
            $(".remove_register").click(function () {
                var $id = $(this).attr("data-id");
                $(".register_id").val($id);
            });
            $(".cancel").click(function () {
                $(".register_id").val("");
            });
        });
    </script>
    <?php
}?>