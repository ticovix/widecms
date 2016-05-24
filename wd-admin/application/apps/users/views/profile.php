<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>">Home</a></li>
    <li><a href="<?php echo base_url_app(); ?>">Usuários</a></li>
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
                            <a href="#gallery" class=" btn-upload" data-toggle="modal">
                                <img src="<?php
                                if (is_file('../wd-content/upload/' . $image)) {
                                    echo wd_base_url('wd-content/upload/' . $image);
                                } else {
                                    echo base_url('assets/images/user.png');
                                }
                                ?>" alt="Avatar" class="img-circle profile_img" id="img-profile" height="109">
                            </a>
                        </div>
                        <div class="col-sm-10">
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email*</label><br>
                                        <?php echo $email ?>    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Login*</label><br>
                                        <?php echo $login ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Sobre</label><br>
                                        <?php echo nl2br($about) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>