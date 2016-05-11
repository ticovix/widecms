<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<!-- page content -->
<div class="right_col" role="main">


    <ul class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
        <li><a href="<?php echo base_url_app(); ?>">Projetos</a></li>
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
                        <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar" class="input-sm form-control"> 
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
                        </span>
                    </div>
                    <?php echo form_close(); ?>
                    <?php
                    if ($pages) {
                        ?>
                        <div class="list-group" id="nav-pages" role="tablist" aria-multiselectable="true">    
                            <?php
                            $x = 0;
                            foreach ($pages as $arr) {
                                $sections = $arr['sections'];
                                ?>
                                <a href="<?php
                                if (count($sections) == 1 && $sections[0]['name']==$arr['name']) {
                                    echo base_url_app('project/' . $project['slug'] . '/' . $arr['slug'] . '/' . $sections[0]['slug']);
                                } else {
                                    echo '#collapse'.$x;
                                }
                                ?>" class="page-current list-group-item" data-toggle="collapse" data-parent="#accordion" aria-expanded="true" aria-controls="collapse<?php echo $x;?>">
                                   <?php echo $arr["name"] ?>
                                    <?php if (count($sections) > 1) { ?><span class="fa arrow"></span><?php } ?>
                                </a>
                                <?php if (count($sections) > 1 or count($sections) == 1 && $sections[0]['name']!=$arr['name']) { ?>
                                    <div id="collapse<?php echo $x;?>" class="panel-collapse collapse" role="tabpanel">
                                        <?php
                                        if (count($sections)) {
                                            foreach ($sections as $section) {
                                                ?>
                                                <a class="list-group-item" href="<?php echo base_url_app('project/' . $project['slug'] . '/' . $arr['slug'] . '/' . $section['slug']) ?>">
                                                    &nbsp;&nbsp; <?php echo $section['name'] ?>                                                
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                } elseif (count($sections) == 0) {
                                    ?>
                                    <ul class="nav nav-list collapse"><li class="no-sections">Nenhum seção encontrada.</li></ul>        
                                    <?php
                                }
                                ?>
                                <?php
                                $x++;
                            }
                            ?>
                        </div>
                        <?php
                    } else {
                        echo '<strong>Nenhuma página encontrada, contate o desenvolvedor.</strong>';
                    }
                    ?>
                    <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
                </div>
            </div>
        </div>
    </div>
</div>