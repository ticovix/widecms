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
    <div class="btn-toolbar">
        <a href="<?php echo base_url(); ?>project/<?php echo $project['slug'] ?>/create" class="btn btn-primary"><i class="icon-plus"></i> Nova página</a>
        <div class="btn-group"></div>
    </div>
    <?php echo form_open(null, ['method' => 'get', 'class' => 'form-group']); ?>
    <div class="input-group">
        <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar projeto" class="input-sm form-control"> 
        <span class="input-group-btn">
            <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
        </span>
    </div>
    <?php echo form_close(); ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Página</th>
                <th>Diretório</th>
                <th>Status</th>
                <th style="width: 50px;">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($pages) {
                foreach ($pages as $arr) {
                    ?>
                    <tr>
                        <td>
                            <a href="<?php echo base_url() ?>project/<?php echo $project['slug'] ?>/<?php echo $arr['slug'] ?>">
                                <?php echo $arr["name"] ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $arr['directory'] ?>
                        </td>
                        <td>
                            <i class="fa fa-lg <?php
                            if ($arr['status'] === '1') {
                                echo 'fa-check';
                            } elseif ($arr['status'] === '0') {
                                echo 'fa-times';
                            }
                            ?>">
                        </td>
                        <td>
                            <a href="<?php echo base_url(); ?>project/<?php echo $project['slug'] ?>/edit/<?php echo $arr["slug"] ?>"><i class="fa fa-pencil"></i></a>
                            <a href="<?php echo base_url(); ?>project/<?php echo $project['slug'] ?>/remove/<?php echo $arr["slug"] ?>"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr><td colspan="4"><strong>Foram encontrados <?php echo $total ?> páginas.</strong></td></tr>
                <?php
            } else {
                echo '<tr><td colspan="4">Nenhuma página encontrado.</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
</div>