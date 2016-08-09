<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app(); ?>"><?php echo $name_app ?></a></li>
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
                        <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></button> 
                    </span>
                </div>
                <a href="" class="pull-right">Pesquisa avançada</a>
                <?php
                if (check_method($method . '-create')) {
                    ?>
                    <div class="btn-toolbar">
                        <a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/create'); ?>" class="btn btn-primary"><i class="icon-plus"></i> <?php echo $this->lang->line(APP . '_btn_add_post') ?></a>
                        <div class="btn-group"></div>
                    </div>
                    <?php
                }
                echo form_close();
                echo form_open(APP_PATH.'project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/remove');
                ?>
                <button class="btn btn-danger hide" id="btn-del-selected"><i class="fa fa-remove remove_register"></i> <?php echo $this->lang->line(APP . '_btn_remove_selected') ?></button>
                <table class="table table-striped table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th width="20"></th>
                            <?php
                            if ($list) {
                                foreach ($list as $arr) {
                                    ?>
                                    <th><?php echo $arr['label']; ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <!--th style="width: 60px;"></th-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($posts) {
                            foreach ($posts as $row) {
                                $id = $row['id'];
                                ?>
                                <tr class="register-current">    
                                    <td align="center">
                                        <?php
                                        if (check_method($method . '-remove')) {
                                            ?>
                                            <input type="checkbox" name="post[]" value="<?php echo $id; ?>" class="multiple_delete">
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <?php
                                    foreach ($row as $key => $value) {
                                        if ($key != 'id') {
                                            ?>
                                            <td>
                                                <?php
                                                $allow_edit = check_method($method . '-edit');
                                                if ($allow_edit) {
                                                    echo '<a href="' . base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/edit/' . $id) . '">';
                                                }
                                                echo $value;
                                                if ($allow_edit) {
                                                    echo '</a>';
                                                }
                                                ?>
                                            </td>    
                                            <?php
                                        }
                                    }
                                    ?>
                                    <!--td>
                                    <?php if (check_method($method . '-edit')) { ?><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/edit/' . $id); ?>"><i class="fa fa-pencil"></i></a><?php } ?>
                                    <?php if (check_method($method . '-remove')) { ?><a href="<?php echo base_url_app('project/' . $slug_project . '/' . $slug_page . '/' . $slug_section . '/remove/' . $id); ?>" onclick="javascript: return confirm('Deseja realmente remover esse registro?')"><i class="fa fa-remove"></i></a><?php } ?>
                                    </td-->
                                </tr>
                                <?php
                            }
                            ?>
                            <tr><td colspan="<?php echo $total_list; ?>"><strong><?php printf($this->lang->line(APP . '_registers_found'), $total); ?></strong></td></tr>
                            <?php
                        } else {
                            echo '<tr><td colspan="' . $total_list . '">' . $this->lang->line(APP . '_registers_not_found') . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <?php echo form_close();?>
                <ul class="pagination pagination-sm"><?php echo $pagination; ?></ul>
            </div>
        </div>
    </div>
</div>
