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
                <?php
                echo form_open();
                ?>
                <div class="alert alert-info">
                    A senha não pode ter menos de 8 caracteres.
                </div>
                <div class="form-group">
                    <label for="new-pass">Nova senha:</label>
                    <input type="password" name="pass" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirmar nova senha:</label>
                    <input type="password" id="new-pass" name="confirm-pass" class="form-control">
                </div>
                <a href="<?php echo base_url ?>login" class="btn btn-primary">Cancelar</a>
                <input value="Alterar" name="reset_pass" class="btn btn-primary pull-right" type="submit">
                <div class="clearfix"></div><br>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>