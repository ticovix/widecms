<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
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
                                <span class="fa fa-edit icon-edit"><?php echo $this->lang->line(APP . '_btn_change_photo') ?></span>
                                <img src="<?php
                                if (is_file('../wd-content/upload/' . $image)) {
                                    echo wd_base_url('wd-content/upload/' . $image);
                                } else {
                                    echo base_url('assets/images/user.png');
                                }
                                ?>" alt="Avatar" class="img-circle profile_img" id="img-profile" height="109">
                            </a>
                            <input type="hidden" name="image" value="<?php echo set_value('image', $image); ?>" id="upload-image">
                        </div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_name') ?>*</label>
                                        <input type="text" name="name" value="<?php echo set_value('name', $name) ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_lastname') ?></label>
                                        <input type="text" name="lastname" value="<?php echo set_value('lastname', $last_name) ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_email') ?>*</label>
                                        <input type="email" name="email" value="<?php echo set_value('email', $email) ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_login') ?>*</label>
                                        <input type="text" name="login" value="<?php echo set_value('login', $login) ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_password') ?>*</label>
                                        <div class="input-group">
                                            <input type="password" name="password" value="<?php echo set_value('password') ?>" class="form-control input-pass"> 
                                            <a href="#rand-pass" class="btn btn-default generate-pass input-group-addon" data-toggle="modal"><?php echo $this->lang->line(APP . '_btn_gen_password') ?></a>
                                        </div>
                                        <small><?php echo $this->lang->line(APP . '_obs_password') ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_about') ?></label>
                                        <textarea name="about" class="form-control"><?php echo set_value('about', $about) ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <input class="btn btn-primary" value="<?php echo $this->lang->line(APP . '_btn_save') ?>" name="send" type="submit">
                    </div>
                </div>
                <?php echo form_close(); ?>
                <div class="modal fade" id="rand-pass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo $this->lang->line(APP . '_title_modal') ?></h4>
                            </div>
                            <div class="modal-body">
                                <h2 class="get-password"></h2>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line(APP . '_btn_cancel') ?></button>
                                <button class="btn btn-primary bt-ok" data-dismiss="modal"><?php echo $this->lang->line(APP . '_btn_save_password') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Envie ou selecione arquivos</h4>
            </div>
            <div class="modal-body">
                <?php
                if (check_app('gallery')) {
                    if (check_method('upload', 'gallery')) {
                        echo form_open_multipart('apps/gallery/upload', ['id' => 'dropzone_gallery', 'class' => 'dropzone form-group']);
                        ?>
                        <div class="dropzone-previews"></div>
                        <div class="dz-default dz-message"></div>
                        <?php
                        echo form_close();
                    }
                    if (check_method('view-files', 'gallery')) {
                        ?>
                        <?php echo form_open(null, ['method' => 'get', 'class' => 'form-group', 'id' => 'search-files']); ?>
                        <div class="input-group">
                            <input type="text" name="search" id="search-field" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar arquivo" class="input-sm form-control"> 
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
                            </span>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="row" id="files-content">
                            <!--EJS -->
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-danger">Você não tem permissão para acessar essa área.</div>
                    <?php
                }
                ?>
            </div>
            <?php
            if (check_app('gallery')) {
                ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-save-change">Salvar</button>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--EJS-->
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--EJS-->
        </div>
    </div>
</div>
