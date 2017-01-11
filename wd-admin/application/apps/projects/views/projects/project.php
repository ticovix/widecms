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
                <?php echo form_close(); ?>
                <div class="list-group" id="nav-pages" role="tablist" aria-multiselectable="true">
                    <?php
                    $exists = false;
                    if ($pages) {
                        $x = 0;
                        foreach ($pages as $arr) {
                            $sections = $arr['sections'];
                            $total_sections = count($sections);
                            $page_name = $arr['name'];
                            $project_dir = $project['directory'];
                            $page_dir = $arr['directory'];
                            if ($total_sections > 0) {
                                $exists = true;
                                $first_section_name = $sections[0]['name'];
                                $first_section_dir = $sections[0]['directory'];
                                ?>
                                <a href="<?php
                                if ($total_sections == 1 && $first_section_name == $page_name) {
                                    echo base_url_app('project/' . $project_dir . '/' . $page_dir . '/' . $first_section_dir);
                                } else {
                                    echo '#collapse' . $x;
                                }
                                ?>" class="page-current list-group-item" <?php if ($total_sections > 1 or $total_sections == 1 && $first_section_name != $page_name) { ?>data-toggle="collapse" data-parent="#accordion" aria-expanded="true" aria-controls="collapse<?php echo $x; ?>"<?php } ?>>
                                   <?php echo $page_name ?>
                                    <?php if ($total_sections > 1 or $total_sections == 1 && $first_section_name != $page_name) { ?><span class="fa fa-caret-down pull-right"></span><?php } ?>
                                </a>
                                <?php
                                if ($total_sections > 1 or $total_sections == 1 && $first_section_name != $page_name) {
                                    ?>
                                    <div id="collapse<?php echo $x; ?>" class="panel-collapse collapse" role="tabpanel">
                                        <?php
                                        if ($total_sections) {
                                            foreach ($sections as $section) {
                                                $section_dir = $section['directory'];
                                                $section_name = $section['name'];
                                                if (check_method($project_dir . '-' . $page_dir . '-' . $section_dir)) {
                                                    ?>
                                                    <a class="list-group-item" href="<?php echo base_url_app('project/' . $project_dir . '/' . $page_dir . '/' . $section_dir) ?>">
                                                        &nbsp;&nbsp; <?php echo $section_name ?>
                                                    </a>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                } elseif (count($sections) == 0) {
                                    ?>
                                    <ul class="nav nav-list collapse"><li class="no-sections"><?php echo $this->lang->line(APP . '_sections_not_found') ?></li></ul>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        $x++;
                                    }
                                }
                            }

                            if (!$exists) {
                                echo '<strong>' . $this->lang->line(APP . '_registers_not_found') . '</strong>';
                            }
                            ?>
                </div>
            </div>
        </div>
    </div>
</div>
