<script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/upload.js"></script>
<div class="header">
    <h1 class="page-title"><?php echo $infor_page["name_function"] ?> <small>/ <?php echo $infor_page["name_page"] ?></small></h1>

</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li><a href="<?php echo ROUTES::baseLink() ?>/pages">Páginas</a></li>
    <?php if (ROUTES::getURL(2) == 'create' && $show_form == true or ROUTES::getURL(2) == 'edit' && $show_form == true or ROUTES::getURL(2) == 'view') { ?>
        <li><a href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo ROUTES::getURL(1) ?>"><?php echo $infor_page["name_function"] ?></a></li>
        <li class="active"><?php if (ROUTES::getURL(2) == 'edit') { ?>Editar registro<?php } elseif (ROUTES::getURL(2) == 'create') { ?>Inserir registro<?php } elseif (ROUTES::getURL(2) == 'view') { ?>Ver registro<?php } ?></li>
    <?php } else {
        ?>
        <li class="active"><?php echo $infor_page["name_function"] ?></li>
    <?php }
    ?>
</ul>

<div class="container-fluid">
    <?php if (ROUTES::getURL(2) == 'create' && $show_form == true or $show_register == false && $show_form == true or ROUTES::getURL(2) == 'edit' && $show_form == true) { ?>
        <form id="tab" method="post" enctype="multipart/form-data">
            <div class="btn-toolbar">
                <a href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo ROUTES::getURL(1) ?>" class="btn btn-default">Cancelar</a>
                <input type="submit" name="<?php echo (ROUTES::getURL(2) == "edit" or $show_register == false) ? 'edit' : 'enviar'; ?>" class="btn btn-primary   pull-right" value="Salvar">
            </div>
            <!--div class="well"-->
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane active in" id="home">
                    <div class="row">
                        <div class="<?php echo $grid ?>">
                            <?php if ($show_config_page) { ?>
                                <div class="loading-url well">
                                    <strong><span class="icon-spinner icon-spin"></span> Carregando..</strong>
                                </div>
                                <div class="tab-current url-box" style="display:none;">
                                    <div class="tab-head">
                                        URL - Identificação unica
                                    </div>
                                    <div class="tab-body">
                                        <div class="form-group">
                                            <input type="text" value="<?php echo $wd_url; ?>"  name="wd_url" class="form-control wd_url" placeholder="Informe uma url de acordo com o título ou nome da página. (obrigatório)">
                                            <div class="alert alert-url alert-danger" style="display:none;">
                                                <p><span class="title_change"></span> <strong class="status_url">Indisponível!</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <ul class="list_form_dynamic">
                                <?php
                                foreach ($listform["form"] as $key) {
                                    $real_type = $key["real_type"];
                                    $real_name = $key["real_name"];
                                    $multiupload = $key["multiupload"];
                                    $type = $key["type"];
                                    $extensions = $key["extensions"];
                                    $id_input = $key["id_input"];
                                    $qtd_length = $key["qtd_length"];
                                    $arr_select = $key["arr_select"];
                                    $sub_select = $key["sub_select"];
                                    $label = $key["label"];
                                    $image = $key["attr_image"];
                                    $permission_config_upload = $NAVIGATION->safe_access($profile->id, "config-upload");
                                    $treat = $POSTS->treat_infors_input(array("type" => $type, "value" => $content[$real_name], "id_post" => $content['id']));
                                    $input_type = $treat["input_type"];
                                    $mask = $treat["mask"];
                                    $value = $treat["value"];
                                    if ($send or $edit)
                                        $value = $_POST[$real_name];
                                    $multiple = '';
                                    $selected = '';
                                    $checked = '';
                                    $multi = '';

                                    if ($type == 'ckeditor')
                                        $load_ckeditor = true;
                                    if ($type == 'color')
                                        $load_color = true;
                                    if ($qtd_length <= 0)
                                        $qtd_length = '';
                                    if ($real_type == 'input') {
                                        ?>
                                        <li form-type="<?php echo $real_name ?>">
                                            <label class="label_form moveli">
                                                <span class="pull-right icon-move"></span>
                                                <strong><?php echo $label ?></strong>
                                            </label>
                                            <?php
                                            if ($input_type == 'file') {
                                                $multiple = "";
                                                $multi = "";
                                                if ($multiupload == "true") {
                                                    $multiple = "multiple='multiple'";
                                                    $multi = "[]";
                                                }
                                                ?>
                                                <?php
                                                $value = html_to_special_chars($value);
                                                $files = json_decode($value);
                                                $count = 0;

                                                if (!empty($value) && ROUTES::getURL(2) != 'create') {
                                                    ?>
                                                    <div class='row'>
                                                        <?php
                                                        foreach ($files as $obj) {
                                                            $file = $obj->file;
                                                            if (!empty($file)) {
                                                                $title = $obj->title;
                                                                $checked = $obj->checked;
                                                                $extension = end(explode(".", $file));
                                                                if ($count > 0 && ($count % 4) == 0) {
                                                                    ?>
                                                                </div>
                                                                <div class='row'>
                                                                    <?php
                                                                }
                                                                if (in_array($extension, array("jpg", "jpeg", "png", "gif", "bmp"))) {
                                                                    ?>
                                                                    <div class="col-sm-3">
                                                                        <div class='media_show_form'>
                                                                            <a href='#' class='delete_file' data-file='<?php echo $file ?>' data-column='<?php echo $real_name ?>'>
                                                                                <span class='icon-remove-sign' title='Remover arquivo'></span>
                                                                            </a>
                                                                            <span class="icon-spinner icon-spin loading-del" style='display:none;'></span>
                                                                            <br>
                                                                            <a href='<?php echo $data_client->address ?>/<?php echo $data_client->path_upload ?>/<?php echo $file ?>' onclick='return hs.expand(this)'>
                                                                                <img src="<?php echo $data_client->address ?>/<?php echo $data_client->path_upload ?>/<?php echo $file ?>" title="<?php echo $title ?>" style="max-width:100%;">
                                                                            </a>
                                                                            <input type="text" name="<?php echo $real_name ?>_file_title[]" class="form-control" placeHolder="Título" value="<?php echo $title ?>">
                                                                            <?php if ($multiupload == "true") { ?>
                                                                            <input type="radio" name="<?php echo $real_name ?>_file_check" <?php if ($checked) { ?>checked="checked"<?php } ?> value="<?php echo $file ?>">
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    if (is_file("../<?php echo $data_client->path_upload ?>/" . $file))
                                                                        $arr_ext = explode(".", $file);
                                                                    $ext = $arr_ext[count($arr_ext) - 1];
                                                                    if (!is_file("template/images/icons/" . $ext . ".png"))
                                                                        $ext = "other";
                                                                    ?>
                                                                    <div class="col-sm-3">
                                                                        <div class='media_show_form'>
                                                                            <a href='#' class='delete_file' data-file='<?php echo $file ?>' data-column='<?php echo $real_name ?>'>
                                                                                <span class='icon-remove-sign' title='Remover arquivo'></span>
                                                                            </a>
                                                                            <span class="icon-spinner icon-spin loading-del" style='display:none;'></span>
                                                                            <br>
                                                                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/icons/<?php echo $ext ?>.png" align="baseline" style="width:28px;"> <?php echo $file ?>
                                                                            <input type="text" name="<?php echo $real_name ?>_file_title[]" class="form-control" placeHolder="Título">
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                $count++;
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                                $array_url = array();
                                                foreach ($image as $key => $val) {
                                                    if ($val != "false" && !empty($val)) {
                                                        $array_url[] = $key . '=' . $val;
                                                    }
                                                }
                                                $url = implode('&', $array_url);

                                                if ($permission_config_upload == 1) {
                                                    echo str_replace(array('${realname}',
                                                        '${label}',
                                                        '${image}',
                                                        '${url}',
                                                        '${image_x}',
                                                        '${image_y}',
                                                        '${image_background_color}',
                                                        '${input_reflection_height}',
                                                        '${input_reflection_opacity}',
                                                        '${input_reflection_space}',
                                                        '${input_reflection_transparent}',
                                                        '${input_image_opacity}',
                                                        '${input_border_transparent}',
                                                        '${image_border}',
                                                        '${image_border_color}',
                                                        '${image_border_opacity}',
                                                        '${input_image_resize}',
                                                        '${if_resize}',
                                                        '${input_image_ratio_crop_corte_centro}',
                                                        '${input_image_ratio_crop_corte_esquerda}',
                                                        '${input_image_ratio_crop_corte_direita}',
                                                        '${input_image_ratio}',
                                                        '${input_image_ratio_x}',
                                                        '${input_image_ratio_y}',
                                                        '${input_image_ratio_fill_alinhando_centro}',
                                                        '${input_image_ratio_fill_alinhando_esquerda}',
                                                        '${input_image_ratio_fill_alinhando_direita}',
                                                        '${input_image_greyscale}',
                                                        '${thumbnail_300px}',
                                                        '${thumbnail_200px}',
                                                        '${thumbnail_100px}',
                                                        '${input_image_text}',
                                                        '${input_image_text_color}',
                                                        '${input_image_text_background}',
                                                        '${input_image_text_opacity}',
                                                        '${input_image_text_background_opacity}',
                                                        '${input_image_text_padding}',
                                                        '${input_image_text_x}',
                                                        '${input_image_text_y}',
                                                        '${input_image_text_direction_v}',
                                                        '${input_image_text_position_l}',
                                                        '${input_image_text_position_r}',
                                                        '${input_image_text_position_tl}',
                                                        '${input_image_text_position_tr}',
                                                        '${input_image_text_position_bl}',
                                                        '${input_image_text_position_br}',
                                                        '${input_image_convert_jpg}',
                                                        '${input_image_convert_png}',
                                                        '${input_image_convert_gif}'
                                                            ), array($real_name,
                                                        $label,
                                                        ROUTES::baseLink() . '/view-image?' . $url,
                                                        ROUTES::baseLink(),
                                                        $image["image_x"],
                                                        $image["image_y"],
                                                        $image["image_background_color"],
                                                        $image["image_reflection_height"],
                                                        $image["image_reflection_opacity"],
                                                        $image["image_reflection_space"],
                                                        $image["image_reflection_transparent"],
                                                        $image["image_opacity"],
                                                        $image["image_border_transparent"],
                                                        $image["image_border"],
                                                        $image["image_border_color"],
                                                        $image["image_border_opacity"],
                                                        ($image["image_resize"] == 'true') ? 'selected="selected"' : '',
                                                        ($image["image_resize"] != 'true') ? 'disabled="disabled"' : '',
                                                        ($image["image_ratio_crop"] == 'true') ? 'selected="selected"' : '',
                                                        ($image["image_ratio_crop"] == 'L') ? 'selected="selected"' : '',
                                                        ($image["image_ratio_crop"] == 'R') ? 'selected="selected"' : '',
                                                        ($image["image_ratio"] == 'true') ? 'selected="selected"' : '',
                                                        ($image["image_ratio_x"] == 'true') ? 'selected="selected"' : '',
                                                        ($image["image_ratio_y"] == 'true') ? 'selected="selected"' : '',
                                                        ($image["image_ratio_fill"] == 'true') ? 'selected="selected"' : '',
                                                        ($image["image_ratio_fill"] == 'L') ? 'selected="selected"' : '',
                                                        ($image["image_ratio_fill"] == 'R') ? 'selected="selected"' : '',
                                                        ($image["image_greyscale"] == 'true') ? 'selected="selected"' : '',
                                                        ($image["thumbnail_300px"] == 'true') ? 'checked="checked"' : '',
                                                        ($image["thumbnail_200px"] == 'true') ? 'checked="checked"' : '',
                                                        ($image["thumbnail_100px"] == 'true') ? 'checked="checked"' : '',
                                                        $image["image_text"],
                                                        $image["image_text_color"],
                                                        $image["image_text_background"],
                                                        $image["image_text_opacity"],
                                                        $image["image_text_background_opacity"],
                                                        $image["image_text_padding"],
                                                        $image["image_text_x"],
                                                        $image["image_text_y"],
                                                        ($image["image_text_direction"] == 'v') ? 'selected="selected"' : '',
                                                        ($image["image_text_position"] == 'L') ? 'selected="selected"' : '',
                                                        ($image["image_text_position"] == 'R') ? 'selected="selected"' : '',
                                                        ($image["image_text_position"] == 'TL') ? 'selected="selected"' : '',
                                                        ($image["image_text_position"] == 'TR') ? 'selected="selected"' : '',
                                                        ($image["image_text_position"] == 'BL') ? 'selected="selected"' : '',
                                                        ($image["image_text_position"] == 'BR') ? 'selected="selected"' : '',
                                                        ($image["image_convert"] == 'jpg' or $image["image_convert"] == 'JPEG') ? 'selected="selected"' : '',
                                                        ($image["image_convert"] == 'png') ? 'selected="selected"' : '',
                                                        ($image["image_convert"] == 'gif') ? 'selected="selected"' : ''
                                                                
                                                    ), file_get_contents('template/upload.html'));
                                                    ?>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            config_upload_modal({
                                                                real_name: "<?php echo $real_name ?>",
                                                                id_input: "<?php echo $id_input ?>",
                                                                id_function: "<?php echo ROUTES::getURL(1) ?>",
                                                                url: "<?php echo ROUTES::baseLink() ?>"
                                                            });
                                                        });
                                                    </script>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <?php
                                            if ($type != 'checkbox') {
                                                ?>
                                                <?php if (ROUTES::getURL(2) != 'create') { ?>
                                                    <input class="notranslate last_value <?php echo $real_name ?>_last_value" type='hidden' name='<?php echo $real_name ?>_value' value='<?php echo $value ?>'>
                                                    <?php } ?>
                                                    <?php if ($type == 'password(bcrypt)') { ?>
                                                    <div class="<?php if ($type == 'password(bcrypt)' && ROUTES::getURL(2) != 'create') { ?>hidden pass-change margin-bottom-5<?php }?> input-group">
                                                    <?php } ?>
                                                    <input type="<?php echo $input_type ?>" name="<?php echo $real_name . $multi ?>" <?php echo $multiple ?> value="<?php echo $value ?>" alt="<?php echo $mask ?>" maxlength="<?php echo $qtd_length ?>" class="notranslate <?php echo $type ?>_func form-control <?php if ($type == 'password(bcrypt)') { ?>input-pass<?php } ?>">
                                                <?php if ($type == 'password(bcrypt)') { ?>
                                                        <a href="#gerar-senha" class="btn btn-default generate-pass input-group-addon" data-toggle="modal">Gerar</a>
                                                    </div>
                                                <?php }?>
                                                <?php if ($type == 'password(bcrypt)' && ROUTES::getURL(2) != 'create') { ?>
                                                    <a href="javascript:;" class="btn btn-default new-pass bt-new" data-val="<?php echo $value ?>">Alterar</a>
                                                <?php } ?>
                                                <?php
                                            } else {
                                                $count = 0;
                                                ?>
                                                <input class="notranslate" type='hidden' name='<?php echo $real_name ?>[]' value=''>
                                                <div class="row">
                                                    <?php
                                                    $list_checkeds = $POSTS->list_options_checked(array("id_input" => $id_input, "id_post" => $content['id']));
                                                    if ($list_checkeds != false)
                                                        $list_checkeds = $list_checkeds->fetchAll();
                                                    foreach ($arr_select as $key) {
                                                        if ($count > 0 && ($count % 4) == 0) {
                                                            ?>
                                                        </div><div class="row">
                                                            <?php
                                                        }
                                                        $checked = (count(search($list_checkeds, 'id', $key["value"])) > 0) ? "checked='checked'" : '';
                                                        ?>
                                                        <label class='col-sm-3'>
                                                            <input type="<?php echo $input_type ?>" style="text-align:left !important;" name="<?php echo $real_name ?>[]" value="<?php echo $key['value'] ?>" alt="<?php echo $mask ?>" class="input-xlarge notranslate <?php echo $type ?>_func" <?php echo $checked ?>> <?php echo $key['label'] ?>
                                                        </label>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                                </div>
                                                <?
                                                }
                                                ?>
                <?php
                if (!empty($extensions) && $input_type == 'file') {
                    ?>
                                                    <p>
                                                        <sup>Extensões aceitas: <?php echo $extensions ?></sup>
                                                    </p>
                    <?php
                } elseif (empty($extensions) && $input_type == 'file') {
                    ?>
                                                    <p>
                                                        <sup>Todas as extensões são aceitas.</sup>
                                                    </p>
                                                <?php
                                            }
                                            ?>
                                            </li>
                                            <?php
                                        }
                                        if ($real_type == 'textarea') {
                                            ?>
                                            <li form-type="<?php echo $real_name ?>">
                                                <label class="label_form moveli">
                                                    <span class="pull-right icon-move"></span>
                                                    <strong><?php echo $label ?></strong>
                                                </label>
                                                <input type='hidden' name='<?php echo $real_name ?>_value' value='<?php echo $value ?>'>
                                                <textarea name='<?php echo $real_name ?>' class='input-xlarge form-control notranslate col-sm-12 <?php echo $type ?>' rows='4' maxlength='<?php echo $qtd_length ?>'><?php echo $value ?></textarea>
                                            </li>
                                            <?php
                                        }
                                        if ($real_type == 'select') {
                                            $name_show = '';
                                            $options = "<option value=''>" . $label . "</option>";
                                            if (!empty($arr_select)) {
                                                $v = $value;
                                                if ($arr["select_search"] == true) {
                                                    $v = $_POST[$real_name];
                                                }
                                                foreach ($arr_select as $key) {
                                                    $selected = "";
                                                    
                                                    if ($v == $key["value"]) {
                                                        
                                                        $selected = "selected='selected'";
                                                        $id_category = $key["value"];
                                                    }
                                                    $options .= "<option value='$key[value]' $selected>$key[label]</option>";
                                                }
                                                if (!empty($sub_select)) {
                                                    $options = '';
                                                    
                                                    $name_show = $POSTS->name_data_show(array("sub_select" => $sub_select));
                                                    $arr_options = $POSTS->get_options(array("name" => $name_show["name"], "id_selected" => ROUTES::getURL(3), "real_name" => $real_name));
                                                    
                                                    //var_dump($name_show);
                                                    if (!empty($name_show)) {
                                                        if (empty($selected)) {
                                                            $options = "<option value=''>Selecione primeiro o campo \"" . $name_show["label"] . "\"</option>";
                                                        }
                                                        if (count($arr_options) > 0 && !empty($arr_options)) {
                                                            foreach ($arr_options as $key) {
                                                                $options .= "<option value='$key[value]' $key[selected]>" . $key["label"] . "</option>";
                                                            }
                                                        }
                                                        $name_show = "data_show_select='$name_show[name]'";
                                                    }else{
                                                        $name_show = "";
                                                        
                                                    }
                                                    
                                                }
                                            }
                                            ?>
                                            <li form-type="<?php echo $real_name ?>">
                                                <label class="label_form moveli">
                                                    <span class="pull-right icon-move"></span>
                                                    <strong><?php echo $label ?></strong>
                                                </label>
                                                <input type='hidden' name='<?php echo $real_name ?>_value' value='<?php echo $value ?>'>
                                                <select name='<?php echo $real_name ?>' class='input-xlarge form-control notranslate <?php echo $real_name ?> select_ col-sm-12' <?php echo $name_show ?>><?php echo $options ?></select>
                                                <script type="text/javascript">
                                                    $(function () {
                                                        $(".<?php echo $real_name ?>").change(function () {
                                                            var selected = $(this).val();
                                                            $.ajax({
                                                                url: "<?php echo ROUTES::baseUrl() ?>/view/ajax/posts.php",
                                                                type: "POST",
                                                                dataType: "json",
                                                                data: {func: "show_sub_select", id_selected: selected, id_input: "<?php echo $real_name ?>"},
                                                                success: function (data) {
                                                                    $(".select_").each(function () {
                                                                        var select_real = $(this).attr("data_show_select");
                                                                        if (select_real == "<?php echo $real_name ?>") {
                                                                            $(this).html(data.message);
                                                                        }
                                                                    });
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>
                                            </li>
                                            <?php
                                        }
                                        if ($real_type == 'autovalue') {
                                            if (ROUTES::getURL(2) == 'create')
                                                $value = '';
                                            switch ($type) {
                                                case 'auto(date reg. created)':
                                                    $tp = (empty($value)) ? date("Y-m-d") : $value;
                                                    break;
                                                case 'auto(datetime reg. created)':
                                                    $tp .= (empty($value)) ? date("Y-m-d H:i:s") : $value;
                                                    break;
                                                case 'auto(date reg. edit)':
                                                    $tp .= (ROUTES::getURL(2) == "edit") ? date("Y-m-d") : "";
                                                    break;
                                                case 'auto(datetime reg. edit)':
                                                    $tp .= (ROUTES::getURL(2) == "edit") ? date("Y-m-d H:i:s") : "";
                                                    break;
                                                case 'auto(number)':
                                                    $rand = $POSTS->verify_number_rand(array("column" => $real_name));
                                                    $tp .= (empty($value)) ? $rand : $value;
                                                    break;
                                                default:
                                                    break;
                                            }
                                            ?>
                                            <li style="display:none;">
                                                <input class="notranslate" type='hidden' name='<?php echo $real_name ?>_value' value='<?php echo $value ?>'>
                                                <input class="notranslate" type='hidden' name='<?php echo $real_name ?>' value='<?php echo $tp ?>'>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
        <?php if ($show_tab) { ?>
                                <div class="col-sm-4">
            <?php if ($show_status) { ?>
                                        <div class="tab-current">
                                            <div class="tab-head">
                                                Publicar
                                            </div>
                                            <div class="tab-body">
                                                <div class="form-group">
                                                    <label>
                                                        <span class="icon-eye-open icon-large"></span> Visibilidade: <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="<strong>Público:</strong> Todos usuários podem ver essa postagem<br> <strong>Privado:</strong> Nenhum usuário pode ver essa postagem<br> <strong>Revisão pendente:</strong> Nenhum usuário pode ver essa postagem até sua aprovação."></span>
                                                    </label>
                                                    <select name="wd_status" class="form-control">
                                                        <option value="1" <?php if ($wd_status == '1') { ?>selected="selected"<?php } ?>>Público</option>
                                                        <option value="0" <?php if ($wd_status == '0') { ?>selected="selected"<?php } ?>>Privado</option>
                                                        <option value="2" <?php if ($wd_status == '2') { ?>selected="selected"<?php } ?>>Revisão pendente</option>
                <?php if (ROUTES::getURL(2) == 'edit' && $wd_status == '3') { ?><option value="3" <?php if ($wd_status == 3) { ?>selected="selected"<?php } ?>>Registro reprovado</option><?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        <span class="icon-calendar icon-large"></span> Publicado em:
                                                    </label>
                                                    <input type="text" name="wd_date" value="<?php echo ($content['wd_date'] != '0000-00-00 00:00:00' && !empty($content['wd_date']) && ROUTES::getURL(2) != 'create') ? datetime_format($content['wd_date']) : date("d/m/Y H:i:s"); ?>" class="form-control datetime_func">
                                                </div>
                                            </div>
                                        </div>
            <?php } ?>
            <?php if ($show_expiration) { ?>
                                        <div class="tab-current">
                                            <div class="tab-head">
                                                Vencimento
                                            </div>
                                            <div class="tab-body">
                                                <div class="form-group">
                                                    <label>
                                                        Data de vencimento: <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="Informe a data que a postagem vai expirar."></span>
                                                    </label>
                                                    <input type="text" name="wd_expiration" value="<?php echo datetime_format($wd_expiration) ?>" class="form-control datetime_func wd_expiration">
                                                    <strong class="get_date_expiration">
                                                        <?php
                                                        $date_expiration = $wd_expiration;
                                                        if ($date_expiration != '0000-00-00 00:00:00' && !empty($date_expiration)) {
                                                            $date_now = new DateTime(date("Y-m-d H:i:s"));
                                                            $date_expiration = new DateTime($date_expiration);
                                                            if ($date_now > $date_expiration) {
                                                                $message = "Postagem vencida";
                                                            } else {
                                                                $date_diff = $date_now->diff($date_expiration);
                                                                $days = $date_diff->d;
                                                                $hours = $date_diff->h;
                                                                $month = $date_diff->m;
                                                                $minutes = $date_diff->i;
                                                                $year = $date_diff->y;
                                                                $days = ($days > 0) ? $days . ' dia(s)' : '';
                                                                $hours = ($hours > 0) ? $hours . ' hora(s)' : '';
                                                                $month = ($month > 0) ? $month . ' mes(es)' : '';
                                                                $minutes = ($minutes > 0) ? $minutes . ' minuto(s)' : '';
                                                                $year = ($year > 0) ? $year . ' ano(s)' : '';
                                                                $message = 'Restam ' . $year . ' ' . $month . ' ' . $days . ' ' . $hours . ' ' . $minutes;
                                                            }
                                                            ?>
                    <?php echo $message ?>
                <?php } ?>
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
            <?php } ?>
            <?php if ($show_config_page) { ?>
                                        <div class="tab-current">
                                            <div class="tab-head">
                                                Configurações de página
                                            </div>
                                            <div class="tab-body">
                                                <div class="form-group">
                                                    <label>
                                                        Título: <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="Informando um título unico você pode otimizar essa postagem para os motores de busca<br><strong>Exemplos:</strong><br> Comprar apartamentos<br>Alugar imóveis no Brasil"></span>
                                                    </label>
                                                    <input type="text" name="wd_title"  value="<?php echo $wd_title; ?>" class="form-control notranslate">
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Descrição: <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="Informando uma descrição você pode convencer os usuários dos motores de busca / rede sociais a acessar essa postagem."></span>
                                                    </label>
                                                    <textarea class="form-control notranslate" name="wd_description"><?php echo $wd_description ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tags:</label>
                                                    <input type="text" class="form-control" name="wd_tags" value="<?php echo $wd_tags ?>" placeHolder="Separe as tags por vírgula">
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Imagem: <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="Adicione uma imagem para ser exibida nas redes sociais."></span>
                                                    </label>
                                                    <input type="file" name="wd_image" class="form-control">
                <?php
                if (!empty($wd_image) && ROUTES::getURL(2) != 'create') {
                    ?>
                                                        <br>
                                                        <label>Imagem atualização (<a href="javascript:void(0);" class='delete_file' data-file='<?php echo base64_encode($wd_image) ?>' data-column='<?php echo base64_encode('wd_image') ?>'>Deletar</a>)</label>
                                                        <div class="thumbnail">
                                                            <img src="<?php echo $data_client->address ?>/<?php echo $data_client->path_upload ?>/<?php echo $wd_image; ?>">
                                                        </div>
                    <?php
                }
                ?>
                                                </div>
                                            </div>
                                        </div>
            <?php } ?>
                                </div>
        <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
            <?php
        }
        ?>
    <?php
    if (ROUTES::getURL(2) == "view") {
        ?>
            <div class="btn-toolbar">
                <a href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo ROUTES::getURL(1) ?>" class="btn">Sair</a>
                <a href="javascript: window.history.back();" class="btn">Voltar</a>
            </div>
        <?php
        if ($content["wd_status"] == 3) {
            ?>
                <div class="alert alert-info" role="alert">
                    Essa postagem foi reprovada.<br>
                    <a href="<?php echo ROUTES::baseLink() ?>/<?php echo ROUTES::getURL(0) ?>/<?php echo ROUTES::getURL(1) ?>?status=3">Ver todas postagens reprovadas</a>
                </div>     
                <?php
            }
            if ($content["wd_status"] == 2) {
                ?>
                <div class="alert alert-info" role="alert">
                    Essa postagem está pendente para aprovação.<br>
                    <a href="<?php echo ROUTES::baseLink() ?>/<?php echo ROUTES::getURL(0) ?>/<?php echo ROUTES::getURL(1) ?>?status=2">Ver todas postagens pendentes</a>
                </div>  
                <?php
            }
            if ($content["wd_status"] == 0) {
                ?>
                <div class="alert alert-info" role="alert">
                    Essa postagem está privada.<br>
                    <a href="<?php echo ROUTES::baseLink() ?>/<?php echo ROUTES::getURL(0) ?>/<?php echo ROUTES::getURL(1) ?>?status=0">Ver todas postagens privadas</a>
                </div>  
            <?php
        }
        ?>
            <div class="well">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="home">
                        <ul class="list_form_dynamic row">
                            <?php
                            $type = array();
                            $x = 0;
                            foreach ($list_values["form"] as $key) {
                                if (!in_array($key["real_name"], $type) && $key["real_name"] != "id")
                                    $type[$key["real_name"]] = $PAGES->get_type(array("column" => $key["real_name"]));
                                $label = $key["label"];
                                $value = $content[$key["real_name"]];
                                ?>
                                <li form-type="<?php echo $key["real_name"] ?>">
            <?php if ($x > 0) { ?><hr><?php } ?>
                                    <label >
                                        <strong><?php echo $label ?></strong>
                                    </label>
                                    <div class="value_register notranslate">
                                <?php echo $POSTS->alter_mask(array("type" => $key["type"], "column" => $key, "value" => $value, "limit_chars" => false, "view" => true, "id_post" => $content['id'])) ?>
                                    </div>
                                </li>
                                <?php
                                $x++;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    <?php if ($show_register && ROUTES::getURL(2) != 'create' && ROUTES::getURL(2) != 'edit' && ROUTES::getURL(2) != 'view') { ?>
            <div class="btn-toolbar">
                <a href="#screen-options" data-toggle="collapse" class="btn btn-primary pull-right question" title="Define quais campos serão exibidos na listagem de registros.">Opções de tela</a>
                <a href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo ROUTES::getURL(1) ?>/create" class="btn btn-primary">Adicionar novo registro</a>
            </div>
            <div class="well">
                <div class="tab-content">
                    <div class="collapse out" id="screen-options">
                        <div class="sc-options" id="sc-options">
                            <!--JS-->
                        </div>
                    </div>
                </div>

                <form action="" method="GET" class="form_search" name="form_search">
                    <div class="row">
                        <input type="hidden" name="status" value="<?php echo $get_status; ?>">
                        <div class="col-sm-1">
                            <select name="qtd_page" class="form-control no-margin question " title="Informe a quantidade de registros que serão exibidos por página">
                                <?php
                                $i = 0;
                                while ($i < 100) {
                                    $i = $i + 10;
                                    ?>
                                    <option <?php if ($qtd_page == $i) { ?>selected="selected"<?php } ?>><?php echo $i ?></option>
            <?php
        }
        ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="input_search" title="Informe um nome ou palavra que deseja encontrar" class="no-margin question form-control" placeHolder="Busca" value="<?php echo $input_search ?>" title="Nome do registro que procura (Opcional)">
                        </div>
                        <div class="col-sm-2">
                            <select name="search_type_filter" class="no-margin question form-control" title="Informe em qual campo você deseja buscar uma determinada palavra. <br> <strong>Por padrão: Todos</strong>">
                                <option value="">Buscar palavra de</option>
                                <?php
                                foreach ($listform["form"] as $key) {
                                    $label = $key["label"];
                                    $real_name = $key["real_name"]
                                    ?>
                                    <option value="<?php echo $real_name ?>" <?php if ($search_type_filter == $real_name) { ?>selected="selected"<?php } ?>><?php echo $label ?></option>
            <?php
        }
        ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="order_column_filter" class="no-margin question form-control" title="Informe em qual campo será ordenado a listagem de registros<br> <strong>Por padrão: ID</strong>">
                                <option value="">Ordenar por</option>
                                <?php
                                foreach ($listform["form"] as $key) {
                                    $label = $key["label"];
                                    $real_name = $key["real_name"]
                                    ?>
                                    <option value="<?php echo $real_name ?>" <?php if ($order_column_filter == $real_name) { ?>selected="selected"<?php } ?>><?php echo $label ?></option>
            <?php
        }
        ?>
                                <option value="wd_date" <?php if ($order_column_filter == "wd_date") { ?>selected="selected"<?php } ?>>Data de inserção</option>
                                <option value="wd_last_update" <?php if ($order_column_filter == "wd_last_update") { ?>selected="selected"<?php } ?>>Data de atualizaçao</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="order_type_filter" class="no-margin question form-control" title="Informe em qual ordem será listado os registros">
                                <option value="DESC" <?php if ($order_type_filter == 'DESC') { ?>selected="selected"<?php } ?>>Decrescente</option>
                                <option value="ASC" <?php if ($order_type_filter == 'ASC') { ?>selected="selected"<?php } ?>>Crescente</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <a href="#filters" data-toggle="collapse" class="btn btn-default question form-control" title="Você pode filtrar registros de um determinado valor, por exemplo listar todos os usuários de uma idade.">Filtros</a>
                        </div>
                        <div class="col-sm-1">
                            <input type="submit" value="Buscar" name="search_bt" class="btn btn-primary form-control">
                        </div>
                        <div class="tab-content" style="margin-top:10px;">
                            <div class="collapse out" id="filters">
                                <!--div class="row">
                                    <div class="col-sm-12"><br><strong>Filtrar por campos</strong></div>
                                </div-->
                                <?php
                                /*
                                if ($arr_filters["registers"] == false) {
                                    $total_registers_filters = 0;
                                } else {
                                    $total_registers_filters = $arr_filters["registers"]->rowCount();
                                }

                                $no_repeat_reg = '';
                                if ($total_registers_filters) {
                                    $count = 0;
                                    ?>
                                    <div class="row margin-bottom-5">
                                        <?php
                                        $registers_filters = $arr_filters["registers"]->fetchAll();
                                        $filters = $arr_filters["filters"];
                                        foreach ($filters as $filter) {
                                            if ($filter["datatype"] != "date" && $filter["datatype"] != "datetime" && $filter["datatype"] != "time") {
                                                if ($count > 0 && ($count % 4) == 0) {
                                                    ?>
                                                </div><div class="row margin-bottom-5">
                        <?php
                    }
                    ?>
                                                <div class="col-sm-3">
                                                    <select name="<?php echo $filter["real_name"] ?>_filter" title="Filtrar por <?php echo strtolower($filter["label"]) ?>" class="question form-control">
                                                        <option value=""><?php echo $filter["label"] ?></option>
                                                        <?php
                                                        foreach ($registers_filters as $arr) {
                                                            $label = $arr[$filter['real_name'] . '_label'];
                                                            $value = $arr[$filter['real_name'] . '_value'];

                                                            if (!in_array($value, $no_repeat_reg) && !empty($value) && $value != '0000-00-00' && $value != '0000-00-00 00:00:00') {
                                                                ?>
                                                                <option <?php if ($_GET[$filter["real_name"] . '_filter'] == $value) { ?>selected="selected"<?php } ?> value="<?php echo $value ?>"><?php echo $label; ?></option>
                                                                <?   
                                                                }
                                                                $no_repeat_reg[] = $value;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <?php
                                                        $count++;
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        $find = array("date", "datetime", "time");
                                        $filters_date = search($filters, "datatype", $find);
                                        if (count($filters_date) > 0) {
                                            foreach ($filters_date as $arr) {
                                                ?>
                                                <div class="row">
                                                    <div class="col-sm-12"><br><strong>Filtrar por <?php echo strtolower($arr["label"]) ?></strong></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="input-prepend">
                                                            <span class="add-on">De</span>
                                                            <input class="<?php echo $arr["datatype"] ?>_func form-control" value="<?php echo $_GET[$arr["real_name"] . '_filter_of'] ?>" name="<?php echo $arr["real_name"] ?>_filter_of" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="input-prepend">
                                                            <span class="add-on">Até</span>
                                                            <input class="<?php echo $arr["datatype"] ?>_func form-control" value="<?php echo $_GET[$arr["real_name"] . '_filter_to'] ?>" name="<?php echo $arr["real_name"] ?>_filter_to" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }*/
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-12"><br><strong>Filtrar por data de inserção</strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="input-prepend">
                                                    <span class="add-on">De</span>
                                                    <input class="datetime_func form-control" value="<?php echo $wd_date_of_filter ?>" name="wd_date_of_filter" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-prepend">
                                                    <span class="add-on">Até</span>
                                                    <input class="datetime_func form-control" value="<?php echo $wd_date_to_filter ?>" name="wd_date_to_filter" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12"><br><strong>Filtrar por data de atualização</strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="input-prepend">
                                                    <span class="add-on">De</span>
                                                    <input class="datetime_func form-control" value="<?php echo $wd_last_update_of_filter ?>" name="wd_last_update_of_filter" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-prepend">
                                                    <span class="add-on">Até</span>
                                                    <input class="datetime_func form-control" value="<?php echo $wd_last_update_to_filter ?>" name="wd_last_update_to_filter" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12"><br><strong>Filtrar por usuário administrativo</strong></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <select name="wd_fk_user_filter" class="form-control">
                                                    <option value="">Selecione</option>
                                                    <?php
                                                    foreach ($list_users as $arr) {
                                                        ?>
                                                        <option value="<?= $arr["id"] ?>" <?php if ($wd_fk_user_filter == $arr["id"]) { ?>selected="selected"<?php } ?>><?php echo $arr["name"] . ' ' . $arr["surname"] ?></option>
                    <?php
                }
                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="loading-reg"><span class="icon-spinner icon-spin"></span> Carregando..</div>
                        <div class="registers" style="display:none;">
                            <ul class="nav nav-tabs" id="filter_status">
                <?php if ($show_status) { ?>
                                    <li <?php if ($get_status == '') { ?>class="active"<?php } ?>><a href="<?php echo addParam(array("status" => "")) ?>" title="" class="question">Todos (<span class="total_all"><?php echo $count_total ?></span>)</a></li>
                                    <li <?php if ($get_status == '1') { ?>class="active"<?php } ?>><a href="<?php echo addParam(array("status" => "1")) ?>" class="tag_public question" title="Todos usuários podem ver essas postagens">Públicos (<span class="total_public"><?php echo $total_public ?></span>)</a></li>
                                    <li <?php if ($get_status == '0') { ?>class="active"<?php } ?>><a href="<?php echo addParam(array("status" => "0")) ?>" class="tag_private question" title="Nenhum usuário pode ver essas postagens">Privados (<span class="total_private"><?php echo $total_private ?></span>)</a></li>
                                    <li <?php if ($get_status == '2') { ?>class="active"<?php } ?>><a href="<?php echo addParam(array("status" => "2")) ?>" class="tag_pending question" title="Nenhum usuário pode ver essas postagens até sua aprovação.">Revisões pendentes (<span class="total_pending"><?php echo $total_pending ?></span>)</a></li>
                                    <li <?php if ($get_status == '3') { ?>class="active"<?php } ?>><a href="<?php echo addParam(array("status" => "3")) ?>" class="tag_deny question" title="Nenhum usuário pode ver essas postagens. Todas passaram por revisão e foram reprovadas.">Registros reprovadas (<span class="total_deny"><?php echo $total_deny ?></span>)</a></li>
                                <?php } ?>
                                <?php if ($show_expiration) { ?>
                                    <li <?php if ($get_status == '4') { ?>class="active"<?php } ?>><a href="<?php echo addParam(array("status" => "4")) ?>" class="tag_expiration question" title="Postagens públicas que possui menos de 7 dias para vencer.">Vencendo (<span class="total_deny"><?php echo $total_expiration ?></span>)</a></li>
                                    <li <?php if ($get_status == '5') { ?>class="active"<?php } ?>><a href="<?php echo addParam(array("status" => "5")) ?>" class="tag_expired question" title="Postagens públicas vencidas.">Vencidos (<span class="total_deny"><?php echo $total_expired ?></span>)</a></li>
                <?php } ?>
                            </ul>
                            <table class="table table-striped table-responsive" id="list_registers_<?= ROUTES::getURL(1) ?>">
                                <thead>
                                    <tr>
                                        <?php
                                        if ($count_columns > 0) {
                                            $count = 1;
                                            foreach ($list_registers["labels"] as $label) {
                                                if ($list_registers["reg"][$count - 1] == 0) {
                                                    $hidden_reg[] = $count;
                                                    $hidden_col[] = $label;
                                                }
                                                ?>
                                                <th><?php echo $label ?></th>
                                                <?php
                                                $count++;
                                            }
                                        }
                                        ?>
                <th><!--a href="<?php echo ROUTES::baseLink() ?>/print/<?php echo ROUTES::getURL(1) ?>/<?php echo ROUTES::getURL(2) ?>/<?php echo ROUTES::getURL(3) ?>" target="_blank" title="Imprimir listagem de registros" class="icon-print print_registers"></a--></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count_values = count($list_registers["values"]);
                                    $count_current = 0;
                                    $type = array();
                                    if ($count_values > 0) {

                                        foreach ($list_registers["values"] as $obj) {
                                            $status = $POSTS->get_status($obj->wd_status);
                                            $status_name = $status["name"];
                                            $class_status = $status["class"];
                                            ?>
                                            <tr class="reg_current">
                                                <?php
                                                $id_register = $obj->id;
                                                $ct = 0;
                                                foreach ($obj as $key => $value) {
                                                    if ($key != 'id' && $key != "wd_status") {
                                                        if (!in_array($key, $type) && $key != "id")
                                                            $type[$key] = $PAGES->get_type(array("column" => $key));
                                                        ?>
                                                        <td class="notranslate" style="vertical-align:middle; <?php /* if($list_registers["reg"][$ct-1]==0){?>display:none;<?php } */ ?>">
                                                            <a href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo ROUTES::getURL(1) ?>/view/<?php echo $id_register ?>" title="Ver registro completo">
                                                        <?php echo $POSTS->alter_mask(array("type" => $type[$key], "column" => $key, "value" => $value, "limit_chars" => true, "id_post" => $id_register)) ?>
                                                            </a>
                                                        </td>      
                                                        <?php
                                                    }
                                                    $ct++;
                                                }
                                                ?>    
                                                <td style="<?php if ($get_status == '2') { ?>width: 240px;<?php } elseif ($get_status == '' or $get_status == 4 or $get_status == 5) { ?>width: 180px;<?php } else { ?>width:120px;<?php } ?> vertical-align:middle; ">
                                                    <?php if ($get_status == '2') { ?>
                                                        <a href="javascript:void(0);" class="btn btn-sm allow_post" data-id='<?php echo $obj->id; ?>'>Aprovar</a> 
                                                        <a href="#deny-reg" data-toggle="modal" class="btn btn-sm deny_post" data-id='<?php echo $obj->id; ?>'>Reprovar</a>
                        <?php } ?>
                                                    <a href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo ROUTES::getURL(1) ?>/create/<?php echo $id_register ?>" class="btn btn-sm btn-primary" title="Criar nova postagem com o conteúdo deste registro."><i class="icon-copy"></i></a>
                                                    <?php if ($show_form == true) { ?><a href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo ROUTES::getURL(1) ?>/edit/<?php echo $id_register ?>" class="btn btn-sm btn-primary" title="Editar"><i class="icon-pencil"></i></a><?php } ?>
                                                    <a href="#myModal" role="button" data-id="<?php echo $id_register ?>" class="remove_register btn btn-sm btn-danger" data-toggle="modal" title="Deletar"><i class="icon-remove"></i></a>

                                                    <?php
                                                    if ($show_status) {
                                                        if ($get_status == '' or $get_status == 4 or $get_status == 5) {
                                                            ?><span class="btn-sm <?php echo $class_status ?>" title="Status"><?php echo $status_name ?></span><?php
                            }
                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $count_current++;
                                        }
                                    } else {
                                        ?>
                                        <tr><td colspan='<?php echo $count ?>'>Nenhum registro encontrado.</td></tr>
                    <?php
                }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    if ($count_total > 0) {
                        ?>
                        <?php echo $count_total ?> registros foram encontrados.

                        <?php
                    }
                    if ($count_total > $qtd_page) {
                        $pagination = 6;
                        $go_pagination = ($pagination - $page_current);
                        $go_pagination = $go_pagination - $go_pagination * 2;
                        $total_pag = $page_current + $pagination - 1;
                        if ($total_pag > ceil($count_total / $qtd_page))
                            $total_pag -= (int) ($total_pag - ceil($count_total / $qtd_page));
                        if ($pag <= $pagination) {
                            $go_pagination = 1;
                        }
                        ?>
                        <div >
                            <ul class="pagination">
                                <li><a <?php if ($page_current > 1) { ?>href="<?php echo addParam(array("page" => ($page_current - 1))) ?>" class="active"<?php } else { ?>class="inactive"<?php } ?>>Voltar</a></li>
                                <?php
                                for ($i = $go_pagination; $i <= $total_pag; $i++) {
                                    if ($page_current != $i) {
                                        ?>
                                        <li><a href="<?php echo addParam(array("page" => $i)) ?>" class="active"><?php echo $i ?></a></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li><a class="inactive"><?php echo $i ?></a></li>
                                        <?php
                                    }
                                }
                                ?>
                                <li><a <?php if ($pag_total != $page_current) { ?>href="<?php echo addParam(array("page" => ($page_current + 1))) ?>" class="active"<?php } else { ?>class="inactive"<?php } ?>>Avançar</a></li>
                            </ul>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4  class="modal-title">Confirmação para deletar</h4>
                            </div>
                            <div class="modal-body">
                                <p class="error-text"><i class="icon-warning-sign modal-icon"></i>Deseja realmente deletar esse registro?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                                <input type="hidden" name="register_id" value="" class="register_id">
                                <input type="submit" name="confirm" class="btn btn-danger btn_send" value="Deletar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if ($_GET['status'] == 2) { ?>
                <div class="modal fade" id="deny-reg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Confirmar reprovação</h4>
                                </div>
                                <div class="modal-body">
                                    <p class="error-text">
                                        <label><strong>Motivo:</strong></label>
                                        <textarea class="form-control message_deny"></textarea>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" value="" class="id_post_deny">
                                    <button class="btn cancel_deny" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                                    <button name="confirm" class="btn btn-danger deny_post" data-dismiss="modal" aria-hidden="true">Reprovar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <link rel="stylesheet" href="<?php echo ROUTES::baseUrl() ?>/template/lib/jquery-ui/jquery-ui.css"/>
            <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/posts.js"></script>
            <script src="<?php echo ROUTES::baseUrl() ?>/template/lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
            <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/jquery.meio.js"></script>
            <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/datetimepicker.js"></script>
            <script type="text/javascript">
                var URL = function () {
                    return "<?php echo ROUTES::baseUrl() ?>";
                }
                $(function () {
                    posts.url = "<?php echo ROUTES::baseLink() ?>";
                    posts.page = "<?php echo base64_encode(ROUTES::getURL(1)); ?>";
                    posts.id_post = "<?php echo ROUTES::getURL(3); ?>";
                    posts.init();
                });
            </script>

            <?php if (ROUTES::getURL(2) == 'create' && $show_form == true or $show_register == false && $show_form == true or ROUTES::getURL(2) == 'edit' && $show_form == true) { ?>
                <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/user.js"></script>
                <div class="modal fade" id="gerar-senha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Senha gerada</h4>
                            </div>
                            <div class="modal-body">
                                <h2 class="get-password"></h2>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                                <button class="btn btn-primary bt-ok" data-dismiss="modal">Copiado</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if ($load_ckeditor) {
                    ?>
                    <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/plugins/ckeditor/ckeditor.js"></script>
                    <?php
                }
                ?>
                <?php
                if($load_color){
                ?>
                <link rel="stylesheet" href="<?php echo ROUTES::baseUrl() ?>/template/plugins/spectrum/spectrum.css"/>
                <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/plugins/spectrum/spectrum.js"></script>
                <script type="text/javascript">
                $(".color_func").spectrum({
                    showInput: true,
                    allowEmpty: true,
                    showInitial: true,
                    showAlpha: true,
                    showPalette: true,
                    togglePaletteOnly: true,
                    showSelectionPalette: true,
                    preferredFormat: true
                });
                </script>
                <?php
                }
                ?>
                <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/file.js"></script>
                <script type="text/javascript">
                                                    $(function () {
                                                        posts.form_dynamic();
                                                        posts.expiration();
                <?php if ($show_config_page) { ?>
                                                            posts.keyup_url();
                    <?php
                }
                ?>
                                                        $('.delete_file').click(function (e) {
                                                            var position = $('.delete_file').index(this);
                                                            var column_del = $('.delete_file').eq(position).attr("data-column");
                                                            var file = $('.delete_file').eq(position).attr("data-file");

                                                            if (confirm("Deseja realmente deletar esse arquivo ?")) {
                                                                e.preventDefault();
                                                            } else {
                                                                return false;
                                                            }

                                                            delete_file({
                                                                table: "<?php echo base64_encode('posts') ?>",
                                                                target_class_del: "media_show_form",
                                                                column_list: "<?php echo ($show_register == false) ? base64_encode('wd_function') : base64_encode('id'); ?>",
                                                                column_del: column_del,
                                                                file: file,
                                                                id_register: "<?php echo ($show_register == false) ? base64_encode(ROUTES::getURL(1)) : base64_encode(ROUTES::getURL(3)); ?>",
                                                                position: position,
                                                                url: "<?= ROUTES::baseLink() ?>",
                                                                bt_delete: '.delete_file',
                                                                loading: '.loading-del'
                                                            });

                                                        });
                                                    });
                </script>
            <?php } else { ?>
                <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/jquery.cookie.js"></script>
                <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/js/jquery.columnmanager.min.js"></script>
                <script type="text/javascript">
                                                    $(function () {
                                                        posts.remove_register();
                                                        posts.change_status();
                                                        posts.column_manager({
                                                            hidden_reg: [<?php echo implode(",", $hidden_reg); ?>],
                                                            id_column_manager: "list_registers_<?= ROUTES::getURL(1) ?>"
                                                        });
                                                    });
                </script>
            <?php } ?>
            <link rel="stylesheet" type="text/css" href="<?php echo ROUTES::baseUrl() ?>/template/plugins/highslide/highslide.css" />
            <script type="text/javascript" src="<?php echo ROUTES::baseUrl() ?>/template/plugins/highslide/highslide-with-gallery.js"></script>
            <script type="text/javascript">
                                                hs.graphicsDir = '<?php echo ROUTES::baseUrl() ?>/template/plugins/highslide/graphics/';
                                    hs.align = 'center';
                                    hs.transitions = ['expand', 'crossfade'];
                                    hs.outlineType = 'rounded-white';
                                    hs.fadeInOut = true;
                                    hs.numberPosition = 'caption';
                                    hs.dimmingOpacity = 0.75;

                                    if (hs.addSlideshow)
                                        hs.addSlideshow({
                                            interval: 5000,
                                            repeat: false,
                                            useControls: true,
                                            fixedControls: 'fit',
                                            overlayOptions: {
                                                opacity: .75,
                                                position: 'bottom center',
                                                hideOnMouseOut: true
                                            }
                                        });
                                    $(function () {
                                        $('.question').tooltip('hide');
                                        $(".loading-reg").hide();
                                        $(".registers").slideDown(500);
                                    });
</script>