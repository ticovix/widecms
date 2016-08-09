<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<div class="">
    <a class="hiddenanchor" id="toregister"></a>
    <a class="hiddenanchor" id="tologin"></a>

    <div id="wrapper">
        <div id="login" class=" form">
            <section class="login_content">
                <?php
                echo form_open('login/redefine-pass?token=' . $token . '&login=' . $login);
                ?>
                <div align="center">
                    <img src="<?php echo base_url() ?>assets/images/cms_wide.png" class="logo img-responsive">
                </div>
                <?php
                if ($this->input->get('send') === 'true') {
                    ?>
                    <div class="alert alert-success">
                        <?php echo $this->lang->line('redefine_message_success');?>
                    </div>    
                    <a class="reset_pass" href="<?php echo base_url('login') ?>">Login <i class="fa fa-arrow-right fa-fw"></i></a>
                    <?php
                } else {
                    ?>
                    <div>
                        <input type="password" name="pass" value="<?php echo set_value('pass'); ?>" class="form-control" placeholder="<?php echo $this->lang->line('redefine_password_field');?>" required="" />
                    </div>
                    <div>
                        <input type="password" name="re_pass" value="<?php echo set_value('re_pass'); ?>" class="form-control" placeholder="<?php echo $this->lang->line('redefine_re_password_field');?>" required="" />
                    </div>
                    <div>
                        <input value="<?php echo $this->lang->line('redefine_change_pass_btn');?>" name="access" class="btn btn-primary pull-right" type="submit">
                        <a class="reset_pass" href="<?php echo base_url('login') ?>"><i class="fa fa-arrow-left fa-fw"></i> <?php echo $this->lang->line('redefine_return_btn');?></a>
                    </div>
                    <?php
                }
                ?>
                <?php
                echo form_close();
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                ?>
            </section>
        </div>
    </div>
</div>



