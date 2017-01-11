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
                <?php echo form_open(null, ['method' => 'get']); ?>
                <div class="input-group">
                    <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="<?php echo $this->lang->line(APP . '_field_search'); ?>" class="input-sm form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></button>
                    </span>
                </div>
                <?php echo form_close(); ?>
                <div class="list-group">
                    <?php
                    if ($projects) {
                        foreach ($projects as $arr) {
                            ?>
                            <a href="<?php echo base_url_app('project/' . $arr['directory']) ?>" class="list-group-item">
                                <?php if ($arr['main_project']) { ?><span class="fa fa-star"></span> <?php } ?> <?php echo $arr["name"] ?>
                            </a>
                            <?php
                        }
                        ?>
                        <strong><?php printf($this->lang->line(APP . '_registers_found'), $total); ?></strong>
                        <?php
                    } else {
                        echo $this->lang->line(APP . '_registers_not_found');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
