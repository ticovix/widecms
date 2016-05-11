<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
?>
<!-- page content -->
<div class="right_col" role="main">
    <ul class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
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
                    <?php echo form_open_multipart(APP_PATH . 'upload', ['id' => 'dropzone_gallery', 'class' => 'dropzone form-group']) ?>
                    <div class="dropzone-previews"></div>
                    <div class="dz-default dz-message"></div>
                    <?php echo form_close() ?>
                    <?php echo form_open(null, ['method' => 'get', 'id' => 'search-files']); ?>
                    <div class="input-group">
                        <input type="text" name="search" id="search-field" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar arquivo" class="input-sm form-control"> 
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
                        </span>
                    </div>
                    <?php echo form_close(); ?>
                    <div id="files-list"><!--EJS--></div>
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

    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--EJS-->
            </div>
        </div>
    </div>

</div>