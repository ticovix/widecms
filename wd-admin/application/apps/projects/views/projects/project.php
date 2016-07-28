<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
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
                <div class="list-group" id="nav-pages" role="tablist" aria-multiselectable="true">   
                    <?php
                    $exists = false;
                    if ($pages) {
                        $x = 0;
                        foreach ($pages as $arr) {
                            $sections = $arr['sections'];
                            $total_sections = count($sections);
                            $name_page = $arr['name'];
                            $slug_project = $project['slug'];
                            $slug_page = $arr['slug'];
                            if ($total_sections > 0) {
                                $exists = true;
                                $first_name_section = $sections[0]['name'];
                                $first_slug_section = $sections[0]['slug'];
                                ?>
                                <a href="<?php
                                if ($total_sections == 1 && $first_name_section == $name_page) {
                                    echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $first_slug_section);
                                } else {
                                    echo '#collapse' . $x;
                                }
                                ?>" class="page-current list-group-item" <?php if ($total_sections > 1 or $total_sections == 1 && $first_name_section != $name_page) { ?>data-toggle="collapse" data-parent="#accordion" aria-expanded="true" aria-controls="collapse<?php echo $x; ?>"<?php } ?>>
                                   <?php echo $name_page ?>
                                    <?php if ($total_sections > 1 or $total_sections == 1 && $first_name_section != $name_page) { ?><span class="fa fa-caret-down pull-right"></span><?php } ?>
                                </a>
                                <?php if ($total_sections > 1 or $total_sections == 1 && $first_name_section != $name_page) { ?>
                                    <div id="collapse<?php echo $x; ?>" class="panel-collapse collapse" role="tabpanel">
                                        <?php
                                        if ($total_sections) {
                                            foreach ($sections as $section) {
                                                $slug_section = $section['slug'];
                                                $name_section = $section['name'];
                                                if (check_method($slug_project . '-' . $slug_page . '-' . $slug_section)) {
                                                    ?>
                                                    <a class="list-group-item" href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section) ?>">
                                                        &nbsp;&nbsp; <?php echo $name_section ?>                                                
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
                                    <ul class="nav nav-list collapse"><li class="no-sections">Nenhum seção encontrada.</li></ul>        
                                    <?php
                                }
                                ?>
                                <?php
                                $x++;
                            }
                        }
                    }

                    if (!$exists) {
                        echo '<strong>Nenhuma página encontrada, contate o desenvolvedor.</strong>';
                    }
                    ?>
                </div>
                <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
            </div>
        </div>
    </div>
</div>
