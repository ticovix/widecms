<div class="header">
    <h1 class="page-title"><?php if (ROUTES::getURL(0) == 'new-func') { ?>Criar função<?php } else { ?>Editar função<?php } ?></h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li><a href="<?php echo ROUTES::baseLink() ?>/pages">Páginas</a></li>
    <li class="active">Nova função</li>
</ul>
<div class="container-fluid">
    <div class="row-fluid">

        <div class="btn-toolbar">
            <div class="btn-group">
            </div>
        </div>
        <div class="well">
            <!--     <ul class="nav nav-tabs">
                  <li class="active"><a href="#new-page" data-toggle="tab">Nova página</a></li>
                  <li><a href="#new-function" data-toggle="tab">Nova função</a></li>
                </ul> -->
            <div class="tab-content">
                <div id="new-function" class="tab-pane active">
                    <div id="createinput" class="well">
                        <form action="javascript:;" class="action_new_input">
                            <h3>Cadastrar novo campo</h3><small>Cadastre um novo campo para função</small>
                            <div class="form-group">
                                <input type="text" class="label_input form-control" name="label_input" placeholder="Nome do campo">
                            </div>
                            <div class="form-group">
                                <input type="text" class="quantity_input form-control" name="quantity_input" placeholder="Quantidade de caracteres"> 
                            </div>
                            <div class="form-group">
                                <select name="type_input" class="type_input question form-control" title="Pode-se criar vários tipos de input, com máscara de telefone, cpf, data e etc..">
                                    <option value="">Tipo de input</option>
                                    <?php
                                    $count = 0;
                                    foreach ($input_type as $key) {
                                        ?>
                                        <option value="<?php echo $key["id"] ?>"><?php echo $key["name"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">    
                                <div class="extensions">
                                    <div class="form-group">
                                        <select name="multifile" class="multifile question form-control" disabled="disabled" title="Fazer upload de mais de um arquivo ?">
                                            <option value="false">Múltiplo upload - Não</option>
                                            <option value="true">Múltiplo upload - Sim</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="extensions_file question form-control" disabled="disabled" placeholder="Extensões de arquivo">
                                        <p>Separe as extensões por virgula ou deixe em branco = all (todos)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="select_pages">
                                <div class="form-group">
                                    <select class="page_selected question form-control" disabled="disabled" name="page_selected" title="Selecione a função que deseja que os registros apareceçam no select">
                                        <option value="">Função</option>
                                        <?php
                                        if (count($selectpage) > 0) {
                                            foreach ($selectpage as $key) {
                                                ?>
                                                <option value="<?php echo $key["id"] ?>"><?php echo $key["name"] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="select_addition">
                                    <div class="form-group">
                                        <select disabled="disabled" class="label_selected question form-control" name="label_selected" title="Selecione o campo que será visualizado no select">
                                            <option value="">Label (View)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select disabled="disabled" class="value_selected question form-control" name="value_selected" title="Selecione o campo a ser inserido no banco de dados na hora da seleção do cliente">
                                            <option value="">Value (Set Value)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="content_sub" style="display:none;">
                                            <select title="Selecione somente se o campo for acionado após um select específico" class="sub_select question form-control" disabled="disabled" name="sub_select">
                                                <option value="">SubSeleção de</option>
                                            </select>
                                            <small>* Selecione a subseleção somente se o campo criado for acionado depois de um select específico.<br> Ex: Ao acionar o select determinado, exibirá registros ligados a este campo no select, muito usado em categoria e subcategoria de produtos.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <select name="typedata_input" class="typedata_input question form-control" title="Tipo de coluna que será criado no banco de dados">
                                    <option value="">Tipo de campo</option>
                                    <?php
                                    $count = 0;
                                    foreach ($input_datatype as $key) {
                                        ?>
                                        <option value="<?php echo $key["id"] ?>" maxlength="<?php echo $key["max_length"] ?>"><?php echo $key["type"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="submit" value="Criar" class="send_new_input btn btn-primary pull-right form-control question" title="Ao criar o input é criado uma coluna com o nome do input na tabela 'posts' sem acentos, sinais e com undeline (_) substituindo espaço.<br> <strong>EX:</strong><br> Nome do campo = Este é um título<br> Coluna = este_e_um_titulo">
                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/load.gif" class="loading_create_input" style="display:none; height:26px;">
                        </form>
                    </div>
                    <form id="tab" method="post">
                        <div class="tab-pane active in" id="home">
                            <input type="submit" name="enviar" class="btn btn-primary" value="Salvar">

                            <h2>Configurar função</h2>
                            <h3>Para cliente</h3>
                            <div class="input-group form-group">
                                <label>Nome*</label>
                                <input type="text" name="name" value="<?php echo $name ?>" class="form-control">
                            </div>
                            <div class="input-group form-group">
                                <label>Função para página*</label>
                                <select name="func_from_page" class="select_category form-control" style="display:none;">
                                    <option value="">Selecione</option>
                                    <?php
                                    foreach ($list_pages as $arr) {
                                        ?>
                                        <option value="<?php echo $arr["id"] ?>" <?php if ($func_from_page == $arr["id"]) { ?>selected="selected"<?php } ?>><?php echo $arr["name"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group form-group">
                                <label>Função oculta ? <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title='Se marcada a opção "sim" o administrador não poderá ver essa função na listagem de funções da página.'></span></label><br>
                                <input type="radio" name="hidden" value="0" <?php if ($pagehidden == '0') { ?>checked="checked"<?php } ?>> Sim
                                <input type="radio" name="hidden" value="1" <?php if ($pagehidden != '0') { ?>checked="checked"<?php } ?>> Não<br>
                            </div>
                            <div class="input-group form-group">
                                <label>Módulo de status ? <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title='Se marcada a opção "sim" o administrador poderá definir o status (público, privado, pendente) de cada postagem.'></span></label><br>
                                <input type="radio" name="show_status" value="1" <?php if ($show_status == '1') { ?>checked="checked"<?php } ?>> Sim
                                <input type="radio" name="show_status" value="0" <?php if ($show_status != '1') { ?>checked="checked"<?php } ?>> Não
                                <br>
                            </div>
                            <div class="input-group form-group">
                                <label>Módulo de vencimento de registro ? <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title='Se marcada a opção "sim" o administrador poderá definir uma data de vencimento do anúncio, quando vencido aparece na aba "Vencidos" na listagem de registros.'></span></label><br>
                                <input type="radio" name="show_expiration" value="1" <?php if ($show_expiration == '1') { ?>checked="checked"<?php } ?>> Sim
                                <input type="radio" name="show_expiration" value="0" <?php if ($show_expiration != '1') { ?>checked="checked"<?php } ?>> Não
                                <br>
                            </div>
                            <div class="input-group form-group">
                                <label>Barra de configuração de página ? <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title='Se marcada a opção "sim" o administrador poderá definir/optimizar as postagens (Título, descrição, tags, imagem para ser exibido em redes sociais) '></span></label><br>
                                <input type="radio" name="show_config_page" value="1" <?php if ($show_config_page == '1') { ?>checked="checked"<?php } ?>> Sim
                                <input type="radio" name="show_config_page" value="0" <?php if ($show_config_page != '1') { ?>checked="checked"<?php } ?>> Não
                                <br>
                            </div>

                            <img src="<?php echo ROUTES::baseUrl() ?>/template/images/load.gif" class="loading_mod" style="display:none;">

                            <div class="show_option" style="display:none;"></div>
                            <h2>Selecione os campos <span class="icon-question-sign question" data-toggle="tooltip" data-placement="top" data-title="<strong>Listagem de registro:</strong> Esta opção ativa a possibilidade de inserção de mais de uma postagem, também permite que o campo marcado seja exibido na listagem das postagens.<br><br> <strong>Adicionar ao formulário: </strong> Esta opção adiciona o campo ao formulário de inserção e edição do administrador."></span><br></h2>

                            <table class="table createinput">
                                <?php
                                $x = 0;
                                foreach ($list_inputs as $key) {
                                    $class = ($x % 2 == 0) ? "" : "colorn";
                                    $qtd = $key["qtd_lenght"];
                                    if ($qtd > 0 or ! empty($qtd))
                                        $qtd = "(" . $qtd . ")";
                                    else
                                        $qtd = "";
                                    ?>
                                    <tr class="<?php echo $class ?>">
                                        <td colspan="4" class='tdfunc'><a href="http://<?php echo $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>?del=<?php echo $key[name] ?>" class="trash" onclick="return confirm_remove()"><img src="<?php echo ROUTES::baseUrl() ?>/template/images/delete.png"></a> <strong><?php echo $key["label"] ?></strong> - <?php echo $PAGES->list_input($key["datatype"]) ?><?php echo $qtd ?> - tipo de entrada "<?php echo $PAGES->get_type_name_of_id($key["type"]) ?>" <?php if (!empty($key["extension_file"])) { ?>- Aceita os formatos <?php
                                                echo $key["extension_file"];
                                            }
                                            ?></td>
                                    </tr>
                                    <tr class="<?php echo $class ?>" >
                                        <td style="padding:4px;"><label><input type="checkbox" value="1" id="checkform" class="checkform" name="<?php echo $key['name'] ?>_register" <?php eval('if($' . $key['name'] . '_register=="1"){echo "checked=\"checked\"";}'); ?>> Listagem de registro</label></td>
                                        <td style="padding:4px;"><label><input type="checkbox" value="1" id="checkform" class="checkform" name="<?php echo $key['name'] ?>_form" <?php eval('if($' . $key['name'] . '_form=="1"){echo "checked=\"checked\"";}'); ?>> Adicionar ao formulário</label></td>
                                    </tr>
                                    <?php
                                    $x++;
                                }
                                ?>
                            </table>
                    </form>
                </div>
            </div>
            <!--     <div id="new-page" class="tab-pane active">teste</div>
            -->  </div>
    </div>
</div>
</div>
</form>

<script type="text/javascript">
    function confirm_remove() {
        if (confirm("Apagando a coluna você apaga todos registros que contém na mesma, deseja realmente deletar ?"))
            return true;
        else
            return false;
    }
    $(function () {
        $('.question').tooltip('hide');
        $(".select_category").change(function () {
            var $value = $(this).val();
            if ($value == "4") {
                $(".postint").show().children("input").removeAttr("disabled");
            } else {
                $(".postint").hide().children("input").attr("disabled", "disabled");
            }
        });
        /*$(".checkform").change(function(){
         var $myposition =  $(".checkform").index(this);
         var $boolean    = $(this).is(":checked");
         if(!$boolean){
         $(".checkintro").eq($myposition).attr("checked",false);
         }
         });
         $(".checkintro").change(function(){
         var $myposition =  $(".checkintro").index(this);
         var $boolean    = $(this).is(":checked");
         if($boolean){
         $(".checkform").eq($myposition).click();
         }
         });*/

        $(".type_input").change(function () {
            var $myvalue = $(this).val();
            /*Valor é igual ao ID do file exibe o input de extensões*/
            if ($myvalue == 7) {
                $(".extensions").slideDown(500);
                $(".extensions_file,.multifile").removeAttr("disabled");

            } else {
                $(".extensions").slideUp(500);
                $(".extensions_file,.multifile").attr("disabled", "disabled");
            }

            if ($myvalue == 4) { // select
                $(".select_pages").slideDown(500);
                $(".content_sub").css("display", "block");
                $(".label_selected,.value_selected,.page_selected,.sub_select").removeAttr("disabled");
            } else if ($myvalue == 6) { // checkbox
                $(".select_pages").slideDown(500);
                $(".content_sub").css("display", "none");
                $(".label_selected,.value_selected,.page_selected").removeAttr("disabled");
            } else {
                $(".select_pages,.select_addition").slideUp(500);
                $(".content_sub").css("display", "none");
                $(".label_selected,.value_selected,.page_selected,.sub_select").attr("disabled", "disabled");
            }
        });
        $(".page_selected").change(function () {
            $value = $(this).val();
            $(".select_addition").slideDown(500);
            $.ajax({
                url: "<?php echo ROUTES::baseUrl() ?>/view/ajax/new-func.php",
                type: "POST",
                dataType: "json",
                data: {func: "list_values_of_page", value: $value},
                success: function ($data) {
                    if ($data.error) {
                        alert($data.message);
                    } else {
                        $(".label_selected").html("<option value=''>Label (View)</option>" + $data.message.values);
                        $(".value_selected").html("<option value=''>Value (Set Value)</option>" + $data.message.values);
                        $(".sub_select").html("<option value=''>SubSeleção de</option>" + $data.message.selects);
                    }
                }
            });

        });
        $(".action_new_input").submit(function () {
            $(".loading_create_input").fadeIn(200);
            $(".send_new_input").hide();
            var $name = $(".label_input").val();
            var $quantity = $(".quantity_input").val();
            var $type = $(".type_input").val();
            var $datatype = $(".typedata_input").val();
            var $maxlength = $(".typedata_input option:selected").attr("maxlength");
            var $extensions = $(".extensions_file").val();
            var $page = $(".page_selected").val();
            var $label = $(".label_selected").val();
            var $value = $(".value_selected").val();
            var $multifile = $(".multifile").val();
            var $sub_select = $(".sub_select").val();

            if (!$name || !$type || !$datatype) {
                alert("Digite o nome, tipo de input e tipo de campo.");
                $(".loading_create_input").hide();
                $(".send_new_input").fadeIn(200);
                return false;
            }
            console.log($name.indexOf("wd_"));
            if ($name == "id" || $name.indexOf("wd_") != '-1') {
                alert("Nome privado do sistema, tente outro");
                $(".loading_create_input").hide();
                $(".send_new_input").fadeIn(200);
                return false;
            }

            $.ajax({
                url: "<?php echo ROUTES::baseUrl() ?>/view/ajax/new-func.php",
                type: "POST",
                dataType: "json",
                data: {func: "create_new_input", name: $name, quantity: $quantity, type: $type, datatype: $datatype, extensions: $extensions, page: $page, label: $label, value: $value, multifile: $multifile, maxlength: $maxlength, sub_select: $sub_select},
                success: function ($data) {
                    $(".loading_create_input").hide();
                    $(".send_new_input").fadeIn(200);
                    if ($data.error == false) {
                        document.location.href = "http://<?php echo $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>";

                        $(".label_input").val("");
                        $(".quantity_input").val("");
                        $(".type_input").val("");
                        $(".typedata_input").val("");
                    } else {
                        alert($data.message);
                    }
                }
            });
        });
        $(".loading").remove();
        $(".select_category").show();
    });
</script>