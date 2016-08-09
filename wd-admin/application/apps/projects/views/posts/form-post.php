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
                if($type === 'edit' or $type === 'create'){
                    $check_method = check_method($method . '-' . $type);
                }else{
                    $check_method = check_method($method);
                }
                if ($check_method) {
                    ?>
                    <div id="data-project" data-project="<?php echo $slug_project ?>" data-page="<?php echo $slug_page ?>" data-section="<?php echo $slug_section ?>">
                        <?php
                        echo getErrors();
                        echo form_open('', array('class' => 'form-horizontal'));
                        if ($fields) {
                            foreach ($fields as $field) {
                                $type = (isset($field['label'])) ? $field['type'] : '';
                                $label = (isset($field['label'])) ? $field['label'] : '';
                                $input = (isset($field['input'])) ? $field['input'] : '';
                                $observation = (isset($field['observation'])) ? $field['observation'] : '';
                                $column = $field['column'];
                                ?>
                                <div class="form-group <?php if (empty($label)) { ?>hide<?php } ?>">
                                    <label for="<?php echo $column ?>_field" class="label-field col-sm-2 col-xs-12 control-label" data-field="<?php echo $column ?>" id="label_<?php echo $column; ?>"><?php echo $label; ?></label>
                                    <div class="col-sm-9 col-xs-12 content-field">
                                        <?php echo $input; ?>
                                        <?php
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
                                    ?>" onclick="return confirm('Tem certeza que deseja cancelar?');" class="btn btn-default">Cancelar</a>
                                    <input type="submit" value="Salvar" class="btn btn-primary">
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
                    <div class="alert alert-danger">Você não possui permissões para editar essa seção.</div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Envie ou selecione arquivos</h4>
            </div>
            <div class="modal-body">
                <?php
                if (check_app('gallery')) {
                    if (check_method('upload', 'gallery')) {
                        echo form_open_multipart('apps/gallery/upload', ['id' => 'dropzone_gallery', 'class' => 'dropzone form-group']);
                        ?>
                        <input type="hidden" name="config_upload" id="input_config">
                        <div class="dropzone-previews"></div>
                        <div class="dz-default dz-message"></div>
                        <?php
                        echo form_close();
                    }

                    if (check_method('view-files', 'gallery')) {
                        echo form_open(null, ['method' => 'get', 'class' => 'form-group', 'id' => 'search-files']);
                        ?>
                        <div class="input-group">
                            <input type="text" name="search" id="search-field" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar arquivo" class="input-sm form-control"> 
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
                            </span>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="row" id="files-content">
                            <!--EJS -->
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-danger">Você não tem permissão para acessar essa área.</div>
                    <?php
                }
                ?>
            </div>
            <?php
            if (check_app('gallery')) {
                ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-save-change">Salvar</button>
                </div>
                <?php
            }
            ?>
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
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--EJS-->
        </div>
    </div>
</div>