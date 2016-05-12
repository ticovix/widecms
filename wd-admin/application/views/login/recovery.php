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
                        Caso esse e-mail exista no seu cadastro você receberá um e-mail com os dados para redefinição de senha, caso não receba verifique o e-mail na caixa de spam ou na lixeira.
                    </div>    
                    <a class="reset_pass" href="<?php echo base_url('login') ?>"><i class="fa fa-arrow-left fa-fw"></i> Voltar</a>
                    <?php
                } else {
                    ?>
                    <p>Para maior segurança as senhas do sistema são criptografadas, não é possível recuperar, porém é possível redefinir. <strong>Digite o e-mail do seu usuário cadastrado:</strong></p>
                    <div>
                        <input type="text" name="email" value="<?php echo set_value('email'); ?>" class="form-control" placeholder="E-mail" required="" />
                    </div>
                    <?php
                    if ($captcha) {
                        ?>
                        <div>
                            <label for="captcha"><?php echo $captcha['image']; ?></label>
                            <br>
                            <input type="text" class="form-control" autocomplete="off" name="captcha" placeholder="Digite o texto da imagem" value="<?php echo set_value('captcha') ?>" />
                        </div>
                        <?php
                    }
                    ?>
                    <div>
                        <input value="Enviar e-mail de redefinição" name="access" class="btn btn-primary pull-right" type="submit">
                        <a class="reset_pass" href="<?php echo base_url('login') ?>"><i class="fa fa-arrow-left fa-fw"></i> Voltar</a>
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



