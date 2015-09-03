<div class="row-fluid">
    <div class="dialog">
        <div class="block">
            <p class="block-heading">
                <img src="<?php echo base_url() ?>assets/images/widedevelop.png" class="logo"> Sistema de gerenciamento
            </p>
            <div class="block-body">
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
                <?php
                /* if($persistence_login_error>3){?>
                  <img src="<?php echo base_url()?>assets/lib/captcha/imgGera.php">
                  <label>O que você vê na imagem ?</label>
                  <input type="text" name="captcha" class="form-control"><br>
                 */
                ?>
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
</div>



