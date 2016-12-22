<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app(); ?>"><?php echo $name_app ?></a></li>
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
                <?php echo form_open(null, ['method' => 'get']); ?>
                <div class="input-group">
                    <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="<?php echo $this->lang->line(APP . '_field_search'); ?>" class="input-sm form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></button>
                    </span>
                </div>
                <div class="btn-toolbar">
                    <a href="<?php echo base_url_app('project/' . $project['directory'] . '/create'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $this->lang->line(APP . '_btn_add_page'); ?></a>
                    <div class="btn-group"></div>
                </div>

                <?php echo form_close(); ?>
                <table class="table table-striped table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line(APP . '_label_page'); ?></th>
                            <th><?php echo $this->lang->line(APP . '_label_directory'); ?></th>
                            <th style="width: 50px;"><?php echo $this->lang->line(APP . '_label_status'); ?></th>
                            <th style="width: 70px;"></th>
                        </tr>
                    </thead>
                    <?php
                    if ($pages) {
                        ?>
                        <tbody>
                            <?php
                            foreach ($pages as $arr) {
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo base_url_app('project/' . $project['directory'] . '/' . $arr['directory']) ?>">
                                            <?php echo $arr["name"] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $arr['directory'] ?>
                                    </td>
                                    <td align="center">
                                        <i class="fa fa-lg <?php
                                        if ($arr['status'] === '1') {
                                            echo 'fa-check';
                                        } elseif ($arr['status'] === '0') {
                                            echo 'fa-times';
                                        }
                                        ?>">
                                    </td>
                                    <td align="center">
                                        <a href="<?php echo base_url_app('project/' . $project['directory'] . '/edit/' . $arr["directory"]); ?>" title="<?php echo $this->lang->line(APP . '_btn_edit_page') ?>"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo base_url_app('project/' . $project['directory'] . '/remove/' . $arr["directory"]); ?>" title="<?php echo $this->lang->line(APP . '_btn_remove_page') ?>"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr><td colspan="4"><strong><?php printf($this->lang->line(APP . '_registers_found'), $total); ?></strong></td></tr>
                        </tfoot>
                        <?php
                    } else {
                        echo '<tfoot><tr><td colspan="4">' . $this->lang->line(APP . '_registers_not_found') . '</td></tr></tfoot>';
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>
</div>
