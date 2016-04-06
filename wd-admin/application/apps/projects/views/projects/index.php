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
    <li class="active"><?php echo $title ?></li>
</ul>

<div class="container-fluid">
    <?php echo form_open(null, ['method' => 'get', 'class' => 'form-group']); ?>
    <div class="input-group">
        <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar projeto" class="input-sm form-control"> 
        <span class="input-group-btn">
            <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
        </span>
    </div>
    <?php echo form_close(); ?>
    <ul class="nav nav-stacked">
        <?php
        if ($projects) {
            foreach ($projects as $arr) {
                ?>
                <li>
                    <a href="<?php echo base_url_app('project/'.$arr['slug']) ?>">
                        <?php if ($arr['main']) { ?><span class="fa fa-star"></span> <?php } ?> <?php echo $arr["name"] ?>
                    </a>
                </li>
                <?php
            }
            ?>
            <li><strong><hr>Foram encontrados <?php echo $total ?> projetos.</strong></li>
            <?php
        } else {
            echo '<li>Nenhum projeto encontrado, contate o desenvolvedor.</li>';
        }
        ?>
    </ul>
    <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
</div>