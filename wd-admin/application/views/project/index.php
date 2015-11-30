<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<div class="header">
    <h1 class="page-title"><?php echo $title ?></h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>">Home</a></li>
    <li><a href="<?php echo base_url(); ?>projects">Projetos</a></li>
    <li class="active"><?php echo $title ?></li>
</ul>

<div class="container-fluid">
    <?php echo form_open(null, ['method' => 'get', 'class' => 'form-group']); ?>
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
        <ul class="nav nav-list" id="nav-pages">    
            <?php
            foreach ($pages as $arr) {
                $sections = $arr['sections'];
                ?>
                <li>
                    <a href="<?php
                    if (count($sections) == 1) {
                        echo base_url();
                        ?>project/<?php echo $project['slug'] ?>/<?php echo $arr['slug'] ?>/<?php
                           echo $sections[0]['slug'];
                       } else {
                           echo 'javascript:void(0);';
                       }
                       ?>" class="page-current">
                       <?php echo $arr["name"] ?>
                        <?php if (count($sections) > 1 or count($sections) == 0) { ?><span class="fa arrow"></span><?php } ?>
                    </a>
                    <?php if (count($sections) > 1) { ?>
                        <ul class="nav nav-list collapse">
                            <?php
                            if (count($sections)) {
                                foreach ($sections as $section) {
                                    ?>
                                    <li>
                                        <a href="<?php echo base_url() . $project['slug'] . '/' . $arr['slug'] . '/' . $section['slug'] ?>">
                                            <?php echo $section['name'] ?>                                                
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                        <?php
                    } elseif (count($sections) == 0) {
                        ?>
                        <ul class="nav nav-list collapse"><li class="no-sections">Nenhum seção encontrada.</li></ul>        
                        <?php
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    } else {
        echo '<strong>Nenhuma página encontrada, contate o desenvolvedor.</strong>';
    }
    ?>
    <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
</div>