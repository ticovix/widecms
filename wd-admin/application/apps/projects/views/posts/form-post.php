<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app(); ?>"><?php echo $name_app ?></a></li>
    <li><a href="<?php echo base_url_app('project/' . $slug_project); ?>"><?php echo $name_project ?></a></li>
    <?php if ($dev_mode) { ?>
        <li><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page); ?>"><?php echo $name_page ?></a></li>
    <?php } ?>
    <?php
    if ($breadcrumb_section) {
        ?>
        <li><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section); ?>"><?php echo $name_section ?></a></li>
        <?php
    }
    ?>
    <li class="active">
        <?php
        echo $title;
        ?>
    </li>
</ul>

<div class="row">
    <div class="col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    <?php
                    echo $title;
                    ?>
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content row">
                <?php
                $type = $this->uri->segment(7);
                $check = $method;
                if ($type === 'edit' or $type === 'create') {
                    $check = $method . '-' . $type;
                }

                $check_method = check_method($method);
                if ($check_method) {
                    ?>
                    <div id="data-project" data-project="<?php echo $slug_project ?>" data-page="<?php echo $slug_page ?>" data-section="<?php echo $slug_section ?>">
                        <?php
                        echo getErrors();
                        echo form_open('', array('class' => 'form-horizontal'));
                        if ($fields) {
                            foreach ($fields as $field) {
                                $type = (isset($field['type'])) ? $field['type'] : '';
                                $label = (isset($field['label'])) ? $field['label'] : '';
                                $input = (isset($field['input'])) ? $field['input'] : '';
                                $observation = (isset($field['observation'])) ? $field['observation'] : '';
                                $column = $field['column'];
                                ?>
                                <div class="form-group <?php if (empty($label)) { ?>hide<?php } ?>">
                                    <label for="<?php echo $column ?>_field" class="label-field col-sm-2 col-xs-12 control-label" data-field="<?php echo $column ?>" id="label_<?php echo $column; ?>"><?php echo $label; ?></label>
                                    <div class="col-sm-9 col-xs-12 content-field">
                                        <?php
                                        echo $input;
                                        if (!empty($observation)) {
                                            ?>
                                            <div class="observation"><?php echo $observation ?></div>
                                            <?php
                                        }
                                        ?>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="form-group text-right">
                                <div class="col-sm-11">
                                    <a href="<?php
                                    if ($dev_mode) {
                                        echo base_url_app('project/' . $slug_project);
                                    }
                                    ?>" onclick="return confirm('<?php echo $this->lang->line(APP . "_ask_cancel") ?>');" class="btn btn-default"><?php echo $this->lang->line(APP . "_btn_cancel") ?></a>
                                    <input type="submit" value="<?php echo $this->lang->line(APP . "_btn_save") ?>" class="btn btn-primary">
                                </div>
                            </div>
                            <?php
                        }
                        echo form_close();
                        ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-danger"><?php echo $this->lang->line(APP . "_section_not_allowed") ?></div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var LANG = {
        select_default: '<?php echo $this->lang->line(APP . '_select_default') ?>',
        modal_title_edit_file: '<?php echo $this->lang->line(APP . '_title_modal_edit_file') ?>',
        modal_label_title: '<?php echo $this->lang->line(APP . '_label_title') ?>',
        modal_label_main: '<?php echo $this->lang->line(APP . '_label_main') ?>',
        modal_btn_close: '<?php echo $this->lang->line(APP . '_btn_close') ?>',
        modal_btn_save: '<?php echo $this->lang->line(APP . '_btn_save') ?>',
        modal_btn_remove: '<?php echo $this->lang->line(APP . '_btn_remove') ?>',
    }
</script>