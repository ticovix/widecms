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
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                echo form_open(null, ['class' => 'form-horizontal']);
                ?>
                <input type="hidden" name="section" value="<?php echo $section['directory'] ?>">
                <div class="alert alert-danger">
                    <h4><?php printf($this->lang->line(APP . '_ask_remove_section'), $section['name']); ?></h4>
                    <p><?php echo $this->lang->line(APP . '_warning_remove'); ?></p>
                </div>
                <div class="form-group">
                    <label><?php echo $this->lang->line(APP . '_label_confirm_password'); ?></label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group text-right">
                    <input class="btn btn-danger" value="<?php echo $this->lang->line(APP . '_btn_remove'); ?>" name="send" type="submit">
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
