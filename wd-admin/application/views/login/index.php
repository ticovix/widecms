<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
?>
<div class="row">
    <div class="dialog">
            <div class="block-body">
                <div align="center">
                    <img src="<?php echo base_url() ?>assets/images/cms_wide.png" class="logo img-responsive">
                </div>
                <?php
                echo form_open();
                ?>
                <div class="form-group">
                    <label for="login">Login:</label>
                    <input type="text" id="login" name="login" value="<?php echo set_value('login'); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <div class="panel-bottom form-group">
                    <a href="<?php echo base_url() ?>login/reset-pass">Esqueci minha senha</a>
                    <input value="Acessar" name="access" class="btn btn-primary pull-right" type="submit">
                    <div class="clearfix"></div>
                </div>
                <?php
                echo form_close();
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                ?>
            </div>
    </div>
</div>



