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
    <li><a href="<?php echo base_url('projects'); ?>">Projetos</a></li>
    <li><a href="<?php echo base_url('project/' . $slug_project); ?>"><?php echo $name_project ?></a></li>
    <?php
    if ($dev_mode) {
        ?>
        <li><a href="<?php echo base_url('project/' . $slug_project . '/' . $slug_page); ?>"><?php echo $name_page ?></a></li>
        <?php
    }
    ?>
    <li class="active"><?php echo $title ?></li>
</ul>

<div class="container-fluid">
    <div class="btn-toolbar">
        <a href="<?php echo base_url('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/create'); ?>" class="btn btn-primary"><i class="icon-plus"></i> Novo</a>
        <div class="btn-group"></div>
    </div>
    <?php echo form_open(null, ['method' => 'get', 'class' => 'form-group']); ?>
    <div class="input-group">
        <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar" class="input-sm form-control"> 
        <span class="input-group-btn">
            <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
        </span>
    </div>
    <?php echo form_close(); ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <?php
                if ($list) {
                    foreach ($list as $arr) {
                        ?>
                        <th><?php echo $arr['label']; ?></th>
                        <?php
                    }
                }
                ?>
                <th style="width: 100px;">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($posts) {
                foreach ($posts as $row) {
                    $id = $row['id'];
                    ?>
                    <tr>    
                        <?php
                        foreach ($row as $key => $value) {
                            if ($key != 'id') {
                                ?>
                                <td><?php echo $value ?></td>    
                                <?php
                            }
                        }
                        ?>
                        <td>
                            <a href="<?php echo base_url('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/' . $id . '/edit'); ?>"><i class="fa fa-pencil"></i></a>
                            <a href="<?php echo base_url('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/' . $id . '/remove'); ?>" onclick="javascript: return confirm('Deseja realmente remover esse registro?')"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr><td colspan="<?php echo $total_list; ?>"><strong>Foram encontrados <?php echo $total ?> registros.</strong></td></tr>
                <?php
            } else {
                echo '<tr><td colspan="' . $total_list . '">Nenhum registro encontrado.</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
</div>