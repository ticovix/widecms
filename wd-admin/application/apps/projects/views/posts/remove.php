<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url() ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app() ?>"><?php echo $name_app ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $project_dir); ?>"><?php echo $name_project ?></a></li>
    <?php
    if ($dev_mode) {
        ?>
        <li><a href="<?php echo base_url_app('project/' . $project_dir . '/' . $page_dir); ?>"><?php echo $name_page ?></a></li>
        <li><a href="<?php echo base_url_app('project/' . $project_dir . '/' . $page_dir . '/' . $section_dir); ?>"><?php echo $name_section ?></a></li>
        <?php
    }
    ?>
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
                <?php
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                echo form_open(null, ['class' => 'form-horizontal']);
                ?>
                <div class="alert alert-danger">
                    <h4><?php echo $this->lang->line(APP . '_ask_remove'); ?></h4>
                    <p><?php echo $this->lang->line(APP . '_warning_remove'); ?></p>
                </div>
                <table class="table table-striped table-responsive table-bordered">
                    <thead>
                        <tr>
                            <?php
                            if ($list) {
                                foreach ($list as $arr) {
                                    ?>
                                    <th><?php echo $arr['input']['label']; ?></th>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($posts) {
                            foreach ($posts as $row) {
                                $id = $row['id'];
                                ?>
                                <tr class="register-current">
                            <input type="hidden" name="post[]" value="<?php echo $id; ?>">
                            <?php
                            foreach ($row as $key => $value) {
                                if ($key != 'id') {
                                    ?>
                                    <td>
                                        <?php
                                        echo $value;
                                        ?>
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td>' . $this->lang->line(APP . '_registers_not_found') . '</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <label><?php echo $this->lang->line(APP . '_label_confirm_password'); ?></label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group text-right">
                    <input class="btn btn-danger" value="<?php echo $this->lang->line(APP . '_btn_remove'); ?>" name="send" type="submit">
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
