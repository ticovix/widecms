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
                echo form_open();
                ?>
                <div align="center">
                    <img src="<?php echo base_url() ?>assets/images/cms_wide.png" class="logo img-responsive">
                </div>
                <div>
                    <input type="text" name="login" value="<?php echo set_value('login'); ?>" class="form-control" placeholder="<?php echo $this->lang->line('login_login_field');?>" required="" />
                </div>
                <div>
                    <input type="password" name="password" class="form-control" placeholder="<?php echo $this->lang->line('login_password_field');?>" required="" />
                </div>
                <?php
                if ($captcha) {
                    ?>
                    <div>
                        <label for="captcha"><?php echo $captcha['image']; ?></label>
                        <br>
                        <input type="text" class="form-control" autocomplete="off" name="captcha" placeholder="<?php echo $this->lang->line('login_captcha_field');?>" value="<?php echo set_value('captcha') ?>" />
                    </div>
                    <?php
                }
                ?>
                <div>
                    <input value="<?php echo $this->lang->line('login_login_btn');?>" name="access" class="btn btn-primary pull-right" type="submit">
                    <a class="reset_pass" href="<?php echo base_url('login/recovery') ?>"><?php echo $this->lang->line('login_password_recovery');?></a>
                </div>
                <?php
                echo form_close();
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                ?>
            </section>
        </div>
    </div>
</div>



