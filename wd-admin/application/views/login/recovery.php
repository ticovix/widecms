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
                <?php
                if ($this->input->get('send') === 'true') {
                    ?>
                    <div class="alert alert-info">
                        <?php echo $this->lang->line('recovery_message_success');?>
                    </div>    
                    <a class="reset_pass" href="<?php echo base_url('login') ?>"><i class="fa fa-arrow-left fa-fw"></i> <?php echo $this->lang->line('recovery_return_btn');?></a>
                    <?php
                } else {
                    ?>
                    <p><?php echo $this->lang->line('recovery_text_about_recovery');?></p>
                    <div>
                        <input type="text" name="email" value="<?php echo set_value('email'); ?>" class="form-control" placeholder="<?php echo $this->lang->line('recovery_email_field');?>" required="" />
                    </div>
                    <?php
                    if ($captcha) {
                        ?>
                        <div>
                            <label for="captcha"><?php echo $captcha['image']; ?></label>
                            <br>
                            <input type="text" class="form-control" autocomplete="off" name="captcha" placeholder="<?php echo $this->lang->line('recovery_captcha_field');?>" value="<?php echo set_value('captcha') ?>" />
                        </div>
                        <?php
                    }
                    ?>
                    <div>
                        <input value="<?php echo $this->lang->line('recovery_send_email_btn');?>" name="access" class="btn btn-primary pull-right" type="submit">
                        <a class="reset_pass" href="<?php echo base_url('login') ?>"><i class="fa fa-arrow-left fa-fw"></i> <?php echo $this->lang->line('recovery_return_btn');?></a>
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



