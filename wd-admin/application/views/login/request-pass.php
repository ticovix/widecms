<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
?>
<div class="row-fluid">
    <div class="dialog">
        <div class="block">
            <p class="block-heading">
                Redefinir senha 
            </p>
            <div class="block-body">
                <div class="alert alert-info">
                    Informe seu e-mail registrado no administrador para que possamos enviar um e-mail com o link de redefinição de senha.
                </div>
                <?php echo form_open(); ?>
                <div class="form-group">
                    <label>E-mail:</label>
                    <input type="text" name="email" class="form-control">
                </div>
                <?php
                /* if ($persistence_email_error > 3) { ?>
                  <div class="form-group">
                  <img src="<?php echo base_url() ?>/template/lib/captcha/imgGera.php">
                  <label>O que você vê na imagem ?</label>
                  <input type="text" name="captcha" class="form-control"><br>
                  </div>
                 */
                ?>
                <div class="panel-bottom form-group">
                    <a href="<?php echo base_url() ?>login" class="btn btn-primary">Cancelar</a>
                    <input value="Enviar" name="access" class="btn btn-primary pull-right" type="submit">
                </div>
                <div class="clearfix"></div><br>
                <?php
                form_close();
                ?>

            </div>
        </div>
    </div>
</div>