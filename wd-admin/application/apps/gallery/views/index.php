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
                <?php
                if (check_method('upload')) {
                    echo form_open_multipart(APP_PATH . 'upload', ['id' => 'dropzone_gallery', 'class' => 'dropzone form-group']);
                    ?>
                    <div class="dropzone-previews"></div>
                    <div class="dz-default dz-message"></div>
                    <?php
                    echo form_close();
                }
                ?>
                <?php
                if (check_method('view-files')) {
                    echo form_open(null, ['method' => 'get', 'id' => 'search-files']);
                    ?>
                    <div class="input-group">
                        <input type="text" name="search" id="search-field" value="<?php echo $this->input->get('search') ?>" placeholder="<?php echo $this->lang->line(APP . '_search_field') ?>" class="input-sm form-control">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></button>
                        </span>
                    </div>
                    <?php
                    echo form_close();
                    ?>
                    <div id="files-list"><!--EJS--></div>
                    <?php
                }
                ?>
            </div>
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
<?php
if (check_method('edit')) {
    ?>
    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--EJS-->
            </div>
        </div>
    </div>
    <?php
}
?>

<script type="text/javascript">
    var edit_file = "<?php echo (check_method('edit')) ?>";
    var remove_file = "<?php echo (check_method('remove')) ?>";
</script>