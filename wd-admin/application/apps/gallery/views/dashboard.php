<?php
if (check_method('upload', 'gallery')) {
    echo form_open_multipart('apps/gallery/upload', ['id' => 'dropzone_gallery', 'class' => 'dropzone form-group']);
    ?>
    <div class="dropzone-previews"></div>
    <div class="dz-default dz-message"></div>
    <?php
    echo form_close();
}
?>
<?php
if (check_method('view-files', 'gallery')) {
    echo form_open(null, ['method' => 'get', 'id' => 'search-files']);
    ?>
    <div class="input-group">
        <input type="text" name="search" id="search-field" value="<?php echo $this->input->get('search') ?>" placeholder="<?php echo $lang->line('gallery_search_field')?>" class="input-sm form-control"> 
        <span class="input-group-btn">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button> 
        </span>
    </div>
    <?php
    echo form_close();
    ?>
    <div id="files-list"><!--EJS--></div>
    <?php
}
?>
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
    var edit_file = "<?php echo (check_method('edit', 'gallery')) ?>";
    var remove_file = "<?php echo (check_method('remove', 'gallery')) ?>";
</script>