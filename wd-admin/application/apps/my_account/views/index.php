<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
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

                <?php echo form_open(); ?>
                <div class="btn-toolbar">

                </div>
                <?php
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                echo form_open(null, ['class' => 'form-horizontal']);
                ?>
                <div class="tab-pane active in" id="home">
                    <div class="row">
                        <div class="col-sm-2">
                            <a href="javascript:void(0);" class=" btn-upload">
                                <span class="fa fa-edit icon-edit"><?php echo $this->lang->line(APP . '_btn_change_photo') ?></span>
                                <img src="<?php
                                if (is_file('../wd-content/upload/' . $image)) {
                                    echo wd_base_url('wd-content/upload/' . $image);
                                } else {
                                    echo base_url('assets/images/user.png');
                                }
                                ?>" alt="Avatar" class="img-circle profile_img" id="img-profile" height="109">
                            </a>
                            <input type="hidden" name="image" value="<?php echo set_value('image', $image); ?>" id="upload-image">
                        </div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_name') ?>*</label>
                                        <input type="text" name="name" value="<?php echo set_value('name', $name) ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_lastname') ?></label>
                                        <input type="text" name="lastname" value="<?php echo set_value('lastname', $last_name) ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_email') ?>*</label>
                                        <input type="email" name="email" value="<?php echo set_value('email', $email) ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_login') ?>*</label>
                                        <input type="text" name="login" value="<?php echo set_value('login', $login) ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_password') ?>*</label>
                                        <div class="input-group">
                                            <input type="password" name="password" value="<?php echo set_value('password') ?>" class="form-control input-pass"> 
                                            <a href="#rand-pass" class="btn btn-default generate-pass input-group-addon" data-toggle="modal"><?php echo $this->lang->line(APP . '_btn_gen_password') ?></a>
                                        </div>
                                        <small><?php echo $this->lang->line(APP . '_obs_password') ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_about') ?></label>
                                        <textarea name="about" class="form-control"><?php echo set_value('about', $about) ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <input class="btn btn-primary" value="<?php echo $this->lang->line(APP . '_btn_save') ?>" name="send" type="submit">
                    </div>
                </div>
                <?php echo form_close(); ?>
                <div class="modal fade" id="rand-pass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo $this->lang->line(APP . '_title_modal') ?></h4>
                            </div>
                            <div class="modal-body">
                                <h2 class="get-password"></h2>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line(APP . '_btn_cancel') ?></button>
                                <button class="btn btn-primary bt-ok" data-dismiss="modal"><?php echo $this->lang->line(APP . '_btn_save_password') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
