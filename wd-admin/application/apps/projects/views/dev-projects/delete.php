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
    <li><a href="<?php echo base_url_app() ?>">Projetos</a></li>
    <li class="active"><?php echo $title ?></li>
</ul>

<div class="container-fluid">
    <?php echo form_open(); ?>
    <div class="btn-toolbar">

    </div>
    <?php
    echo validation_errors('<p class="alert alert-danger">', '</p>');
    echo form_open(null, ['class' => 'form-horizontal']);
    ?>
    <input type="hidden" name="project" value="<?php echo $project['id'] ?>">
    <div class="alert alert-danger">
        <h4>Deseja realmente remover o projeto <?php echo $project['name'] ?>?</h4>
        <p><strong>Atenção:</strong> A remoção será permanente, sem possibilidade de restaurar o projeto.</p>
    </div>
    <input type="checkbox" value="1" name="delete_all" <?php echo set_checkbox('main') ?>> Remover todos os arquivos do projeto do diretório <strong>/<?php echo $project['directory']; ?></strong>.<br>
    <div class="form-group text-right">
        <input class="btn btn-danger" value="Remover" name="send" type="submit">
    </div>
    <?php echo form_close(); ?>
</div>