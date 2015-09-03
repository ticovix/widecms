<div class="header">
    <h1 class="page-title">Páginas</h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li class="active">Páginas</li>
</ul>

<div class="container-fluid">
    <div class="row-fluid">
        <?php if ($safe_new_page == 1 or $safe_new_function == 1) { ?>
            <div class="btn-toolbar">
                <?php if ($safe_new_page == 1) { ?><a href="<?php echo ROUTES::baseLink() ?>/new-func" class="btn btn-primary">Nova função</a><?php } ?>
                <?php if ($safe_new_function == 1) { ?><a href="<?php echo ROUTES::baseLink() ?>/new-page" class="btn btn-primary">Nova página</a><?php } ?>
                <div class="btn-group"></div>
            </div>
        <?php } ?>
        <div class="list-pages">
            <?php
            $x = 1;
            if (count($pages) > 0) {
                foreach ($pages as $value) {
                    $funct = $PAGES->list_functions(array("page" => $value["id"], "id_user" => $profile - id));
                    $total_functions = $funct->rowCount();
                    $list_functions = $funct->fetchAll(PDO::FETCH_OBJ);
                    if (($total_functions > 0 or $profile->root == 1) && ($value['access_user'] == '' or $value['access_user'] == '1')) {
                        ?>
                        <div class="accordion-group pg-current col-sm-12 clearfix" id="accordion">
                            <div class="accordion-heading page-current row" >
                                <a class="accordion-toggle col-sm-10" <?php if ($total_functions > 1 or $profile->root == 1) { ?> data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $value["url"] ?>"<?php } else { ?>href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo $list_functions[0]->id ?>"<?php } ?>>
                                    <?php echo $value["name"] ?> <?php if ($total_functions > 1) { ?><span class="icon-caret-down"></span><?php } ?>
                                </a>
                                <div class="options-page">
                                    <?php if ($safe_config_seo == 1) { ?><a href="<?php echo ROUTES::baseLink() ?>/config-seo?page=<?php echo $value["id"] ?>" class="col-sm-1 btn_seo text-center" title="Configuração de SEO da página <?php echo $value["name"] ?>"><strong>SEO</strong></a><?php } ?>
                                    <?php if ($safe_delete_page == 1) { ?><a href="javascript:void(0);" role="button" data-toggle="modal" data-id="<?php echo $value["id"] ?>" class="col-sm-1 remove_page btn_remove pull-right" title="Remover página, funções e conteúdo"><i class="icon-remove"></i></a><?php } ?>
                                </div>
                            </div>
                            <?php
                            if ($total_functions > 1 or $profile->root == 1) {
                                ?>
                                <div id="collapse-<?php echo $value["url"]; ?>" class="accordion-body collapse">
                                    <?php
                                    foreach ($list_functions as $obj) {
                                        if ($obj->access_user != '0') {
                                            ?>
                                            <div class="accordion-inner function-current row">
                                                <a href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo $obj->id ?>" class="name-function col-sm-10">
                                                    <?php echo $obj->name ?>
                                                </a>
                                                <div class="options-page">
                                                    <?php if ($safe_edit_function == 1) { ?><a href="<?php echo ROUTES::baseLink() ?>/edit-func/<?php echo $obj->id ?>" class="col-sm-1 btn_seo text-center" title="Editar <?php echo $obj->name ?>"><i class="icon-pencil"></i></a><?php } ?>
                                                    <?php if ($safe_delete_function == 1) { ?><a href="javascript:void(0);" role="button" data-toggle="modal" data-id="<?php echo $obj->id ?>" class="col-sm-1 remove_function pull-right" title="Remover função e conteúdo"><i class="icon-remove"></i></a><?php } ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                        </div>     
                        <?php
                        $x++;
                    }
                }
            } else {
                ?>
                Nenhuma página encontrada.
            <?php } ?>
        </div>
        <form method="post">
            <div class="modal fade" id="delpage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Confirmação</h4>
                        </div>
                        <div class="modal-body">
                            <p class="error-text">
                                <i class="icon-warning-sign modal-icon"></i>Deseja realmente deletar essa página?<br>
                                <strong>Atenção:</strong> Ao remover a página, você remove todas as funções e conteúdos contidas nela.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                            <input type="hidden" name="register_id" value="" class="register_id">
                            <input type="submit" name="confirm_send_page" class="btn btn-danger btn_send" value="Deletar">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form method="post">
            <div class="modal fade" id="delfunction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Confirmação</h4>
                        </div>
                        <div class="modal-body">
                            <p class="error-text">
                                <i class="icon-warning-sign modal-icon "></i>
                                Deseja realmente deletar essa função?<br>
                                <strong>Atenção:</strong> Ao remover a função, você remove todo o conteúdo contido nela.
                            </p>

                        </div>
                        <div class="modal-footer">
                            <button class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                            <input type="hidden" name="register_id" value="" class="register_id">
                            <input type="submit" name="confirm_send_function" class="btn btn-danger btn_send" value="Deletar">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $(".remove_page").click(function () {
            var $id = $(this).attr("data-id");
            $(".register_id").val($id);
            $('#delpage').modal('toggle');
        });
        $(".remove_function").click(function () {
            var $id = $(this).attr("data-id");
            $(".register_id").val($id);
            $('#delfunction').modal('toggle');
        });
        $(".cancel").click(function () {
            $(".register_id").val("");
        });
    });
</script>