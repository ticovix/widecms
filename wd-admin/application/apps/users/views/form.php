<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<ul class="breadcrumb">
    <li><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app() ?>"><?php echo $name_app?></a></li>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP.'_label_name');?>*</label>
                                <input type="text" name="name" value="<?php echo set_value('name', $name) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP.'_label_lastname');?></label>
                                <input type="text" name="lastname" value="<?php echo set_value('lastname', $last_name) ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP.'_label_email');?>*</label>
                                <input type="email" name="email" value="<?php echo set_value('email', $email) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP.'_label_login');?>*</label>
                                <input type="text" name="login" value="<?php echo set_value('login', $login) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP.'_label_password');?>*</label>
                                <div class="input-group">
                                    <input type="password" name="password" value="<?php echo set_value('password') ?>" class="form-control input-pass"> 
                                    <a href="#rand-pass" class="btn btn-default generate-pass input-group-addon" data-toggle="modal"><?php echo $this->lang->line(APP.'_btn_gen_password');?></a>
                                </div>
                                <?php if ($this->uri->segment(2) == 'edit') { ?><small><?php echo $this->lang->line(APP.'_obs_password');?></small><?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="<?php
                        if ($PROFILE['root'] == 1) {
                            echo 'col-md-4';
                        } else {
                            echo 'col-md-12';
                        }
                        ?>">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP.'_label_status');?></label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo set_select('status', '1', ($status == '1')) ?>><?php echo $this->lang->line(APP.'_status_option_enabled');?></option>
                                    <option value="0" <?php echo set_select('status', '0', ($status == '0')) ?>><?php echo $this->lang->line(APP.'_status_option_disabled');?></option>
                                </select>
                            </div>
                        </div>
                        <?php
                        if ($PROFILE['root'] == 1) {
                            ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line(APP.'_label_dev_mode');?></label>
                                    <select name="allow_dev" class="form-control">
                                        <option value="0" <?php echo set_select('allow_dev', '0', ($allow_dev == '0')) ?>><?php echo $this->lang->line(APP.'_dev_mode_option_deny');?></option>
                                        <option value="1" <?php echo set_select('allow_dev', '1', ($allow_dev == '1')) ?>><?php echo $this->lang->line(APP.'_dev_mode_option_allow');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line(APP.'_label_permission_root');?></label>
                                    <select name="root" class="form-control">
                                        <option value="0" <?php echo set_select('root', '0', ($root == '0')) ?>><?php echo $this->lang->line(APP.'_permission_root_option_no');?></option>
                                        <option value="1" <?php echo set_select('root', '1', ($root == '1')) ?>><?php echo $this->lang->line(APP.'_permission_root_option_yes');?></option>
                                    </select>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line(APP.'_label_about');?></label>
                                <textarea name="about" class="form-control"><?php echo set_value('about', $about) ?></textarea>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($permissions && check_method('edit-permission') && $id_user != $PROFILE['id'] && ($PROFILE['root']!='1' OR $id_user != $PROFILE['id'])) {
                        ?>
                        <br>
                        <div class="x_title">
                            <h2><?php echo $this->lang->line(APP.'_title_manage_permissions');?></h2>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                        foreach ($permissions as $app) {
                            $name = $app['name'];
                            $dir_app = $app['app'];
                            $permissions_app = (isset($app['permissions']) ? $app['permissions'] : array());
                            ?>
                            <table class="table table-responsive table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo $name; ?></th>
                                        <td width="60" align="center"><input type="checkbox" data-app="true" name="<?php echo $dir_app ?>" value="1" checked="checked" class="check-permission"></td>
                                    </tr>
                                </thead>
                                <tbody id="<?php echo $dir_app ?>-list">
                                    <?php
                                    foreach ($permissions_app as $page => $arr) {
                                        foreach ($arr as $key => $value) {
                                            $method = $key;
                                            $label = $value;
                                            if (!is_array($value)) {
                                                $check = check_method($method, $dir_app);
                                                if ($check or $PROFILE['root']==1) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $label ?></td>
                                                        <td align="center"><input type="checkbox" data-page="<?php echo $dir_app . '/' . $page ?>" name="<?php echo $dir_app . '-' . $method ?>" value="1" <?php if($this->uri->segment(3)=='create' or check_method($method, $dir_app, $id_user)){ echo 'checked="checked"'; }?> class="check-permission"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                foreach ($value as $method => $label) {
                                                    $label = '&nbsp; - ' . $label;
                                                    $check = check_method($method, $dir_app);
                                                    if ($check or $PROFILE['root']==1) {
                                                        $check_method = check_method($method, $dir_app, $id_user);
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $label ?></td>
                                                            <td align="center"><input type="checkbox" name="<?php echo $dir_app . '-' . $method ?>" data-sub="<?php echo $dir_app . '/' . $page ?>" value="1" <?php if($this->uri->segment(3)=='create' or $check_method){ echo 'checked="checked"'; } if(!$check_method){ echo 'disabled readonly'; }?> class="check-permission"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                    }
                    ?>

                    <div class="form-group text-right">
                        <input class="btn btn-primary" value="Salvar" name="send" type="submit">
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
