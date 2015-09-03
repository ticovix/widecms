<div class="header">
    <h1 class="page-title">Nova página</h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li><a href="<?php echo ROUTES::baseLink() ?>/pages">Páginas</a></li>
    <li class="active">Nova página</li>
</ul>
<div class="container-fluid">
    <div class="row-fluid">

        <div class="btn-toolbar">
            <div class="btn-group">
            </div>
        </div>
        <div class="well">
            <div class="tab-content">
                <div id="new-function" class="tab-pane active">
                    <form id="tab" method="post">
                        <div class="tab-pane active in" id="home">
                            <input type="submit" name="enviar" class="btn btn-primary" value="Salvar">
                            <h3>Configurar página</h3>
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" name="name" value="<?php echo $name ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>URL</label>
                                <input type="text" name="url_name" value="<?php echo $url_name ?>" class="form-control"> * Se vazio, insere o nome da página separado por "-"
                            </div>
                            <p>
                                <label>Página oculta ?</label><br>
                                <input type="radio" name="hidden" value="0"> Sim<br>
                                <input type="radio" name="hidden" value="1" checked> Não
                            </p>

                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/load.gif" class="loading_mod" style="display:none;">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
