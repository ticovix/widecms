<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url() ?>">Home</a></li>
    <li><a href="<?php echo base_url_app() ?>">Projetos</a></li>
    <li><a href="<?php echo base_url_app('project/' . $project['slug']) ?>"><?php echo $project['name'] ?></a></li>
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
                <?php
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                echo form_open(null, ['class' => 'form-horizontal']);
                ?>
                <input type="hidden" name="section" value="<?php echo $section['id'] ?>">
                <div class="alert alert-danger">
                    <h4>Deseja realmente remover a seção <?php echo $section['name'] ?>?</h4>
                    <p><strong>Atenção:</strong> A remoção será permanente, sem possibilidade de restauração.</p>
                </div>
                <div class="form-group">
                    <label>Informe sua senha para confirmar a remoção:</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group text-right">
                    <input class="btn btn-danger" value="Remover" name="send" type="submit">
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
