<div class="header">
    <h1 class="page-title">Minha conta</h1>
</div>
<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li><a href="<?php echo ROUTES::baseLink() ?>/users">Usuários</a></li>
    <li class="active">Minha conta</li>
</ul>

<div class="container-fluid">
    <div class="row-fluid">
        <form id="tab" method="post" enctype="multipart/form-data">                   
            <div class="btn-toolbar">
                <input type="submit" value="Salvar" name="send" class="btn btn-primary">
                <div class="btn-group">
                </div>
            </div>
            <div class="well">
                <ul class="nav nav-tabs margin-bottom-15">
                    <li class="active"><a href="#home" data-toggle="tab">Perfil</a></li>
                    <li><a href="#profile" data-toggle="tab">Alterar senha</a></li>
                    <li><a href="#system" data-toggle="tab">Sistema</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="home">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="name" value="<?php echo $user_list["name"] ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Sobrenome</label>
                            <input type="text" name="lastname" value="<?php echo $user_list["surname"] ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" readonly value="<?php echo $user_list["mail"] ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Observações</label>
                            <textarea name="obs" rows="3" class="form-control"><?php echo $user_list["observation"] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" name="image" class="form-control">
                            <?php if ($user_list["image"]) { ?>
                                <br>
                                <div class="show_image">
                                    <img src="<?php echo $VERIFY_ACCESS->get_data_client()->address ?>/website/application/template/upload/<?php echo $user_list["image"] ?>">
                                    <br>
                                    <a href="javascript:void(0);" class="delete_file" data-file="<?php echo base64_encode($user_list["image"]) ?>">Remover imagem</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile">
                        <div class="form-group">
                            <label>Nova senha</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control input-pass"> <a href="#gerar-senha" class="btn btn-default generate-pass input-group-addon" data-toggle="modal">Gerar senha</a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="system">
                        <div class="form-group">
                            <label>Idioma</label>
                            <select name="language" class="form-control">
                                <option <?php if ($user_list["language"] == 'de') { ?>selected="selected"<?php } ?> value="de">Alemão</option>
                                <option <?php if ($user_list["language"] == 'ca') { ?>selected="selected"<?php } ?> value="ca">Catalão</option>
                                <option <?php if ($user_list["language"] == 'zh-CHT') { ?>selected="selected"<?php } ?> value="zh-CHT">Chinês tradicional</option>
                                <option <?php if ($user_list["language"] == 'ko') { ?>selected="selected"<?php } ?> value="ko">Coreano</option>
                                <option <?php if ($user_list["language"] == 'es') { ?>selected="selected"<?php } ?> value="es">Espanhol</option>
                                <option <?php if ($user_list["language"] == 'fr') { ?>selected="selected"<?php } ?> value="fr">Francês</option>
                                <option <?php if ($user_list["language"] == 'el') { ?>selected="selected"<?php } ?> value="el">Grego</option>
                                <option <?php if ($user_list["language"] == 'nl') { ?>selected="selected"<?php } ?> value="nl">Holandês</option>
                                <option <?php if ($user_list["language"] == 'en') { ?>selected="selected"<?php } ?> value="en">Inglês</option>
                                <option <?php if ($user_list["language"] == 'it') { ?>selected="selected"<?php } ?> value="it">Italiano</option>
                                <option <?php if ($user_list["language"] == 'ja') { ?>selected="selected"<?php } ?> value="ja">Japonês</option>
                                <option <?php if (empty($user_list["language"]) or $user_list["language"] == 'pt') { ?>selected="selected"<?php } ?> value="pt">Português</option>
                                <option <?php if ($user_list["language"] == 'ru') { ?>selected="selected"<?php } ?> value="ru">Russo</option>
                                <option <?php if ($user_list["language"] == 'tr') { ?>selected="selected"<?php } ?> value="tr">Turco</option>
                                <option <?php if ($user_list["language"] == 'uk') { ?>selected="selected"<?php } ?> value="uk">Ucraniano</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tema</label>
                            <select name="theme" class="form-control">
                                <option <?php if (empty($user_list["theme"])) { ?>selected="selected"<?php } ?> value="">Padrão (Claro)</option>
                                <option <?php if ($user_list["theme"] == 'dark') { ?>selected="selected"<?php } ?> value="dark">Escuro</option>
                            </select>
                        </div>
                    </div>
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
        return "<?php echo ROUTES::getURL(1) ?>";
    }
    var URL = function () {
        return "<?php echo ROUTES::baseUrl() ?>";
    }
</script>
<script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/user.js"></script>
<script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/file.js"></script>
<script type="text/javascript">
    $(function () {
        $('.delete_file').click(function (e) {
            var position = $('.delete_file').index(this);
            var file = $('.delete_file').eq(position).attr("data-file");

            if (confirm("Deseja realmente deletar essa imagem?")) {
                e.preventDefault();
            } else {
                return false;
            }

            delete_file({
                table: "<?php echo base64_encode('user') ?>",
                target_class_del: "show_image",
                column_list: "<?php echo base64_encode('id'); ?>",
                column_del: "<?php echo base64_encode('image') ?>",
                file: file,
                id_register: "<?php echo base64_encode($user_list['id']); ?>",
                position: position,
                url: "<?= ROUTES::baseUrl() ?>"
            });
        });
    });
</script>