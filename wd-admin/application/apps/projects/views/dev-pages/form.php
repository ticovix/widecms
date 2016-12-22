<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app() ?>"><?php echo $name_app ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $project['directory']) ?>"><?php echo $project['name'] ?></a></li>
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
                echo form_open(null, ['class' => 'form-horizontal']);
                ?>
                <div class="tab-pane active in" id="home">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP . '_label_name'); ?>*</label>
                                <input type="text" name="name" id="dig_name" value="<?php echo set_value('name', $name) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP . '_label_status'); ?>*</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo set_select('status', '1', ($status == '1')) ?>><?php echo $this->lang->line(APP . '_status_option_enabled'); ?></option>
                                    <option value="0" <?php echo set_select('status', '0', ($status == '0')) ?>><?php echo $this->lang->line(APP . '_status_option_disabled'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <input class="btn btn-primary" value="Salvar" name="send" type="submit">
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
