<div class="header">
    <h1 class="page-title">Configurações</h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?= ROUTES::baseLink() ?>/home">Home</a></li>
    <li class="active">Configurações</li>
</ul>
<div class="container-fluid">
    <div class="row-fluid">
        <form id="tab" method="post" enctype="multipart/form-data">                
            <div class="btn-toolbar">
                <input type="submit" class="btn btn-primary" name="send" value="Salvar">
            </div>
            <div class="well">
                <ul class="nav nav-tabs margin-bottom-15">
                    <li class="active"><a href="#home" data-toggle="tab">Configurar Site</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="home">
                        <div class="form-group">
                            <label>Nome da empresa</label>
                            <input type="text" name="name" value="<?php echo $name_conf ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>E-mails de recebimento <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="Adicione mais de um e-mail apertando ENTER"></span></label> 
                            <textarea type="text" name="email" class="form-control"><?php echo $email_conf ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" name="phone" value="<?php echo $phone_conf ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Endereço</label>
                            <textarea type="text" name="address" class="form-control"><?php echo $address_conf ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Favicon <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="A imagem inserida será redimensionada para 20x20px"></span></label>
                            <input type="file" name="favicon" class="form-control"><br>
                            <small>Extensões aceitas: jpg, jpeg, png, gif</small><br>
                            <?php if (!empty($last_favicon)) { ?>
                                <div class="show_image">
                                    Icone atual (<a href="javascript:void(0);" class="delete_file">Deletar</a>):<br>
                                    <img src="<?php echo $data_client->address ?>/<?php echo $data_client->path_upload ?>/<?php echo $last_favicon; ?>"><br>
                                </div>
                            <?php } ?>
                        </div>
                        <h3>Otimização geral para motores de busca</h3>
                        <div class="form-group">
                            <label>Título da página <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="Digite no máximo 60 caracteres"></span></label>
                            <input type="text" name="title" value="<?php echo $title_conf ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Descrição <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="Digite no máximo 230 caracteres"></span></label>
                            <textarea type="text" name="description" class="form-control" max-lenght="230"><?php echo $description_conf ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/file.js"></script>

<script type="text/javascript">
    $(function () {
        $('.question').tooltip('hide');
    })
    var USER = function () {
        return "<?= ROUTES::getURL(1) ?>";
    }
    var URL = function () {
        return "<?= ROUTES::baseLink() ?>";
    }
    $(function () {
        $('.delete_file').click(function (e) {
            var position = $('.delete_file').index(this);

            if (confirm("Deseja realmente deletar essa imagem?")) {
                e.preventDefault();
            } else {
                return false;
            }

            delete_file({
                table: "<?php echo base64_encode('config') ?>",
                target_class_del: "show_image",
                column_list: "<?php echo base64_encode('favicon'); ?>",
                column_del: "<?php echo base64_encode('favicon') ?>",
                file: "<?php echo base64_encode($last_favicon) ?>",
                id_register: "<?php echo base64_encode($last_favicon); ?>",
                position: position,
                url: "<?= ROUTES::baseLink() ?>"
            });
        });
    });
</script>