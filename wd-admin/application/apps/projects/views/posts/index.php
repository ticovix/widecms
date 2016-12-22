<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app(); ?>"><?php echo $name_app ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $project_dir); ?>"><?php echo $name_project ?></a></li>
    <?php
    if ($dev_mode) {
        ?>
        <li><a href="<?php echo base_url_app('project/' . $project_dir . '/' . $page_dir); ?>"><?php echo $name_page ?></a></li>
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
            <div class="x_content" id="data-project" data-project="<?php echo $project_dir ?>" data-page="<?php echo $page_dir ?>" data-section="<?php echo $section_dir ?>">
                <?php echo form_open(null, ['method' => 'get']); ?>
                <div class="row form-group">
                    <div class="col-sm-9">
                        <input type="text" name="wd_search" value="<?php echo $this->input->get('wd_search') ?>" placeholder="<?php echo $this->lang->line(APP . '_field_search') ?>" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <select name="wd_limit" class="form-control">
                            <?php
                            for ($i = 1; $i < 100; $i++) {
                                $i = $i + 9;
                                ?>
                                <option value="<?php echo $i ?>" <?php echo set_select('wd_limit', $i, ($i == $this->input->get('wd_limit'))); ?>><?php echo $i ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-primary btn-group-justified"> <i class="fa fa-search"></i></button>
                    </div>
                </div>
                <?php
                if ($form_search) {
                    ?>
                    <div class="collapse" id="search-advanced">
                        <div class="well">
                            <div class="row">
                                <?php
                                $count = 1;
                                $cols = 4;
                                foreach ($form_search as $field) {
                                    $input = $field['input'];
                                    $database = $field['database'];
                                    $label = $input['label'];
                                    $column = $database['column'];
                                    $input_html = $input['html'];
                                    $type = $input['type'];
                                    $type_column = $database['type_column'];
                                    $clearfix = '<div class="clearfix"></div>';
                                    $has_type_search = ($type_column != 'date' && $type_column != 'datetime' && $type != 'select' && $type != 'checkbox');
                                    ?>
                                    <div class="col-sm-3">
                                        <label><?php echo $label ?></label>

                                        <div class="<?php
                                        if ($has_type_search) {
                                            echo 'input-group';
                                        }
                                        ?>">
                                                 <?php
                                                 echo $input_html;
                                                 if ($has_type_search) {
                                                     $value_type = $input['value_type'];
                                                     ?>
                                                <div class="dropdown input-group-addon">
                                                    <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-cog"></span></a>
                                                    <ul class="dropdown-menu" data-field="<?php echo $column ?>" aria-labelledby="dLabel">
                                                        <li class="<?php echo is_nav_active($value_type, array('both', '')) ?> btn-config-field">
                                                            <a href="javascript:void(0);" data-value="both"><?php echo $this->lang->line(APP . '_option_both') ?></a>
                                                        </li>
                                                        <li class="<?php echo is_nav_active($value_type, 'equals') ?> btn-config-field">
                                                            <a href="javascript:void(0);" data-value="equals"><?php echo $this->lang->line(APP . '_option_equals') ?></a>
                                                        </li>
                                                        <li class="<?php echo is_nav_active($value_type, 'after') ?> btn-config-field">
                                                            <a href="javascript:void(0);" data-value="after"><?php echo $this->lang->line(APP . '_option_after') ?></a>
                                                        </li>
                                                        <li class="<?php echo is_nav_active($value_type, 'before') ?> btn-config-field">
                                                            <a href="javascript:void(0);" data-value="before"><?php echo $this->lang->line(APP . '_option_before') ?></a>
                                                        </li>
                                                        <li class="<?php echo is_nav_active($value_type, 'greater') ?> btn-config-field">
                                                            <a href="javascript:void(0);" data-value="greater"><?php echo $this->lang->line(APP . '_option_greater') ?></a>
                                                        </li>
                                                        <li class="<?php echo is_nav_active($value_type, 'smaller') ?> btn-config-field">
                                                            <a href="javascript:void(0);" data-value="smaller"><?php echo $this->lang->line(APP . '_option_smaller') ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>

                                    </div>

                                    <?php
                                    if ($count % $cols == 0) {
                                        echo $clearfix;
                                    }

                                    $count++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="form-group">
                    <?php
                    if ($form_search) {
                        ?>
                        <a class="pull-right" role="button" data-toggle="collapse" href="#search-advanced" aria-expanded="false" aria-controls="collapseExample"><?php echo $this->lang->line(APP . '_btn_search_advanced') ?></a>
                        <?php
                    }

                    if (check_method($method . '-create')) {
                        ?>
                        <div class="btn-toolbar">
                            <a href="<?php echo base_url_app('project/' . $project_dir . '/' . $page_dir . '/' . $section_dir . '/create'); ?>" class="btn btn-primary"><i class="icon-plus"></i> <?php echo $this->lang->line(APP . '_btn_add_post') ?></a>
                            <div class="btn-group"></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                echo form_close();
                echo form_open(APP_PATH . 'project/' . $project_dir . '/' . $page_dir . '/' . $section_dir . '/remove');
                ?>

                <button class="btn btn-danger hide" id="btn-del-selected"><i class="fa fa-remove remove_register"></i> <?php echo $this->lang->line(APP . '_btn_remove_selected') ?></button>
                <table class="table table-striped table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th width="20"><span class="fa fa-list"</th>
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
                    <?php
                    if ($posts) {
                        ?>
                        <tbody>
                            <?php
                            foreach ($posts as $row) {
                                $id = $row['id'];
                                ?>
                                <tr class="register-current">
                                    <td align="center" class="multi-options">
                                        <?php
                                        if (check_method($method . '-remove')) {
                                            ?>
                                            <input type="checkbox" name="post[]" value="<?php echo $id; ?>" class="check-post">
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <?php
                                    foreach ($row as $key => $value) {
                                        if ($key != 'id') {
                                            ?>
                                            <td>
                                                <?php
                                                $allow_edit = check_method($method . '-edit');
                                                if ($allow_edit) {
                                                    echo '<a href="' . base_url_app('project/' . $project_dir . '/' . $page_dir . '/' . $section_dir . '/edit/' . $id) . '">';
                                                }
                                                echo $value;
                                                if ($allow_edit) {
                                                    echo '</a>';
                                                }
                                                ?>
                                            </td>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr><td colspan="<?php echo ($total_list + 1); ?>"><strong><?php printf($this->lang->line(APP . '_registers_found'), $total); ?></strong></td></tr>
                        </tfoot>
                        <?php
                    } else {
                        echo '<tfoot><tr><td colspan="' . ($total_list + 1) . '">' . $this->lang->line(APP . '_registers_not_found') . '</td></tr></tfoot>';
                    }
                    ?>
                </table>
                <?php echo form_close(); ?>
                <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var LANG = {
        select_default: '<?php echo $this->lang->line(APP . '_select_default') ?>',
    }
</script>