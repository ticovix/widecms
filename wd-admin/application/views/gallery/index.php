<div class="header">
    <h1 class="page-title"><?php echo $title ?></h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>">Home</a></li>
    <li class="active"><?php echo $title ?></li>
</ul>

<div class="container-fluid">
    <?php echo form_open_multipart('gallery/upload', ['id' => 'dropzone_gallery', 'class' => 'dropzone form-group']) ?>
    <div class="dropzone-previews"></div>
    <div class="dz-default dz-message"></div>
    <?php echo form_close() ?>
    <?php echo form_open(null, ['method' => 'get', 'class' => 'form-group', 'id' => 'search-files']); ?>
    <div class="input-group">
        <input type="text" name="search" id="search-field" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar arquivo" class="input-sm form-control"> 
        <span class="input-group-btn">
            <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
        </span>
    </div>
    <?php echo form_close(); ?>
    <div id="files-list"><!--EJS--></div>
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