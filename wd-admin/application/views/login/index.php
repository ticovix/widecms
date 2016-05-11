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
                    <input type="text" name="login" value="<?php echo set_value('login'); ?>" class="form-control" placeholder="Login" required="" />
                </div>
                <div>
                    <input type="password" name="password" class="form-control" placeholder="Senha" required="" />
                </div>
                <div>
                    <input value="Acessar" name="access" class="btn btn-primary pull-right" type="submit">
                    <a class="reset_pass" href="#">Esqueceu sua senha?</a>
                </div>
                <?php
                echo form_close();
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                ?>
            </section>
        </div>
    </div>
</div>



