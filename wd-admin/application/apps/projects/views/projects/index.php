<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<!-- page content -->
<div class="right_col" role="main">
    <ul class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
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
                        <input type="text" name="search" value="<?php echo $this->input->get('search') ?>" placeholder="Procurar projeto" class="input-sm form-control"> 
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary"> Buscar</button> 
                        </span>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="list-group">
                        <?php
                        if ($projects) {
                            foreach ($projects as $arr) {
                                ?>
                                <a href="<?php echo base_url_app('project/' . $arr['slug']) ?>" class="list-group-item">
                                    <?php if ($arr['main']) { ?><span class="fa fa-star"></span> <?php } ?> <?php echo $arr["name"] ?>
                                </a>
                                <?php
                            }
                            ?>
                            <strong><hr>Foram encontrados <?php echo $total ?> projetos.</strong>
                            <?php
                        } else {
                            echo '<li>Nenhum projeto encontrado, contate o desenvolvedor.</li>';
                        }
                        ?>
                    </div>
                    <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
                </div>
            </div>
        </div>
    </div>
</div>