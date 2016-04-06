<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<div class="header">
    <h1 class="page-title">
        <?php
        if ($dev_mode) {
            echo $title;
        } else {
            echo $name_page;
        }
        ?>
    </h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>">Home</a></li>
    <li><a href="<?php echo base_url_app(); ?>">Projetos</a></li>
    <li><a href="<?php echo base_url_app('project/' . $slug_project); ?>"><?php echo $name_project ?></a></li>
    <?php if ($dev_mode) { ?>
        <li><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page); ?>"><?php echo $name_page ?></a></li>
    <?php }?>
    <?php
    if ($breadcrumb_section) {
        ?>
        <li><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section); ?>"><?php echo $name_section ?></a></li>
        <?php
    }
    ?>
    <li class="active">
        <?php
        if ($dev_mode) {
            echo $title;
        } else {
            echo $name_page;
        }
        ?>
    </li>
</ul>

<div class="container-fluid" id="data-project" data-project="<?php echo $slug_project ?>" data-page="<?php echo $slug_page ?>" data-section="<?php echo $slug_section ?>">
<?php
$has_input_file = false;
echo form_open();
if ($fields) {
    foreach ($fields as $field) {
        $type = (isset($field['label'])) ? $field['type'] : '';
        $label = (isset($field['label'])) ? $field['label'] : '';
        $input = (isset($field['input'])) ? $field['input'] : '';
        if (!$has_input_file && ($type == 'file' or $type == 'multifile')) {
            $has_input_file = true;
        }
        ?>
            <div class="form-group <?php if (empty($label)) { ?>hide<?php } ?>">
                <label><?php echo $label; ?></label>
                <?php echo $input; ?>
            </div>
                <?php
            }
            ?>
        <div class="form-group text-right">
            <a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page); ?>" class="btn btn-default">Cancelar</a>
            <input type="submit" value="Salvar" class="btn btn-primary">
        </div>
    <?php
}
echo validation_errors('<div class="alert alert-danger">', '</div>');
echo form_close();
?>
</div>
    <?php
    if ($has_input_file) {
        ?>
    <!-- Modal -->
    <div class="modal fade" id="gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Envie ou selecione arquivos</h4>
                </div>
                <div class="modal-body">
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
                    <div class="row">
                        <div class="col-sm-6">
                            <h3>Arquivos</h3>
                            <div id="files-list"><!--EJS--></div>
                        </div>
                        <div class="col-sm-6">

                            <h3>Arquivos adicionados</h3>
                            <div class="well well-sm">
                                <div id="files-list-added"><!--EJS--></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-save-change">Salvar</button>
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
}
?>