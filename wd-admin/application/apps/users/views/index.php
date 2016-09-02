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

                <?php echo form_open(null, ['method' => 'get', 'class' => 'form-group']); ?>
                <div class="input-group">
                    <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="<?php echo $this->lang->line(APP . '_field_search'); ?>" class="input-sm form-control"> 
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button> 
                    </span>
                </div>
                <?php
                if (check_method('create')) {
                    ?>
                    <div class="btn-toolbar">
                        <a href="<?php echo base_url_app('create'); ?>" class="btn btn-primary">
                            <i class="icon-plus"></i> <?php echo $this->lang->line(APP . '_btn_add_user') ?>
                        </a>
                        <div class="btn-group"></div>
                    </div>
                    <?php
                }
                echo form_close();
                echo form_open(base_url_app('delete'));
                ?>
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line(APP . '_title_remove_users') ?></h4>
                            </div>
                            <div class="modal-body">
                                <?php echo $this->lang->line(APP . '_ask_remove_users') ?>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" login="">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line(APP . '_btn_answer_no') ?></button>
                                <button type="submit" class="btn btn-primary"><?php echo $this->lang->line(APP . '_btn_answer_yes') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="#myModal" role="button" data-toggle="modal" class="btn btn-danger hide" id="btn-del-all"><i class="fa fa-remove remove_register"></i> <?php echo $this->lang->line(APP . '_btn_remove_users') ?></a>
                <table class="table table-striped table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php echo $this->lang->line(APP . '_label_name'); ?></th>
                            <th><?php echo $this->lang->line(APP . '_label_login'); ?></th>
                            <th><?php echo $this->lang->line(APP . '_label_email'); ?></th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>

                    <?php
                    if ($users) {
                        ?>
                        <tbody>
                            <?php
                            foreach ($users as $user) {
                                $root = $user['root'];
                                ?>
                                <tr>
                                    <td width="10">
                                        <?php
                                        if (check_method('delete')) {
                                            ?>
                                            <input type="checkbox" name="del[]" <?php if ($user['id'] == '1') { ?>disabled=""<?php } ?> value="<?php echo $user['id']; ?>" class="multiple_delete">
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url_app('profile/' . $user['login']) ?>"><?php echo $user["name"] ?> <?php echo $user["last_name"] ?></a>
                                    </td>
                                    <td><a href="<?php echo base_url_app('profile/' . $user['login']) ?>"><?php echo $user["login"] ?></a></td>
                                    <td><a href="<?php echo base_url_app('profile/' . $user['login']) ?>"><?php echo $user["email"] ?></a></td>
                                    <td align="center">
                                        <?php if ($root != '1' && check_method('edit') or $user_logged['root'] == '1') { ?><a href="<?php echo base_url_app('edit/' . $user["login"]); ?>"><i class="fa fa-pencil"></i></a><?php } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr><td colspan="5"><strong><?php printf($this->lang->line(APP . '_text_users_found'), $total) ?></strong></td></tr>
                        </tfoot>
                        <?php
                    }
                    ?>

                </table>
                <?php echo form_close(); ?>
                <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
            </div>
        </div>
    </div>
</div>
