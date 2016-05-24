<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>">Home</a></li>
    <li><a href="<?php echo base_url_app(); ?>">Projetos</a></li>
    <li><a href="<?php echo base_url_app('project/' . $slug_project); ?>"><?php echo $name_project ?></a></li>
    <?php
    if ($dev_mode) {
        ?>
        <li><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page); ?>"><?php echo $name_page ?></a></li>
        <?php
    }
    ?>
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
                <?php
                if (check_method($method . '-create')) {
                    ?>
                    <div class="btn-toolbar">
                        <a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/create'); ?>" class="btn btn-primary"><i class="icon-plus"></i> Novo</a>
                        <div class="btn-group"></div>
                    </div>
                    <?php
                }
                echo form_close();
                ?>
                <table class="table table-striped table-responsive table-bordered">
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
                                        <?php if (check_method($method . '-edit')) {?><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/edit/' . $id); ?>"><i class="fa fa-pencil"></i></a><?php }?>
                                        <?php if (check_method($method . '-remove')) {?><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/remove/' . $id); ?>" onclick="javascript: return confirm('Deseja realmente remover esse registro?')"><i class="fa fa-remove"></i></a><?php }?>
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
        </div>
    </div>
</div>
