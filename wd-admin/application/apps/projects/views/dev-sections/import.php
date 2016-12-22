<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app() ?>"><?php echo $name_app ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $project['directory']) ?>"><?php echo $project['name'] ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $project['directory'] . '/' . $page['directory']) ?>"><?php echo $page['name'] ?></a></li>
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
                echo getErrors();
                echo form_open_multipart(null, ['class' => 'form-horizontal']);
                ?>
                <div class="form-group">
                    <label for="file">Arquivo Zip</label>
                    <input type="file" name="file" id="file" required="">
                </div>
                <div class="form-group text-right">
                    <input class="btn btn-danger" value="Importar" name="import" type="submit">
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
