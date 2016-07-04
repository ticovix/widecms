function slug (str, transform_space) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
    var to = "aaaaaeeeeeiiiiooooouuuunc------";
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, transform_space)
            .replace(/-+/g, transform_space);

    return str;
};
function if_input_select(){
    var input = $(".select-input").val();
    if (input == "select" || input == "checkbox" || input == "radio") {
        $(".options-field, .label-options-field").removeClass('hide');
    } else {
        $(".options-field, .label-options-field").addClass('hide');
    }
    if (input == "select") {
        $(".trigger-field").removeClass('hide');
    } else {
        $(".trigger-field").addClass('hide');
    }
}

function clean_inputs() {
    $("#modal-new-field .nav-tab").removeClass("active");
    $("#modal-new-field .nav-tab").eq(0).addClass("active");
    $("#modal-new-field .tab-pane").removeClass("active");
    $("#modal-new-field .tab-pane").eq(0).addClass("active");
    $(".attr-current").html("");
    add_attr('', '');
    $("#msg-modal").addClass("hide").html("");
    $("#modal-new-field").attr("data-index", "");
    $("#modal-new-field textarea").val("");
    $("#modal-new-field input").each(function(){
        var type = $(this).attr("type");
        if(type=="text"){
            $(this).val("");
        }else if(type == "checkbox"){
            $(this).removeAttr('checked');
        }
    });
    $("#modal-new-field select option").removeAttr('selected');
    $("#label_options_field").html($("<option>").val("").text("Selecione a opção"));
    if_input_select();
}

function name_exists(name, index_ignore) {
    var exists = false;
    $(".name-val").each(function () {
        var val = $(this).val();
        var index_cur = $(".name-val").index(this);

        if(String(index_cur)!=index_ignore){
            if (val.toLowerCase() == name.toLowerCase()) {
                exists = true;
            }
        }
    });
    return exists;
}

function column_exists(column, index_ignore) {
    var exists = false;
    $(".column-val").each(function () {
        var val = $(this).val();
        var index_cur = $(".column-val").index(this);
        if(String(index_cur)!==index_ignore){
            if (val.toLowerCase() == column.toLowerCase()) {
                exists = true;
            }
        }
    });
    return exists;
}

function add_attr(param, value){
    $(".attr-current").append('<div class="col-sm-6">'+
                        '<div class="form-group">'+
                            '<input type="text" value="'+param+'" value="" class="form-control param_attr_field" placeholder="Atributo">'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-sm-6">'+
                        '<div class="form-group">'+
                            '<input type="text" value="'+value+'" value="" class="form-control value_attr_field" placeholder="Valor">'+
                        '</div>'+
                    '</div>');
}
$(function () {
    $("#fields").on("click",".field-current > td:not(:last-child)",function(e){
        $("#modal-new-field").modal('toggle');
    });
    $( "#sortable" ).sortable({
        items: "> tr",
        appendTo: "parent",
        revert: true,
        cursor: "move",
        cursorAt: { top: -5, left: -5 },
        helper: function( event ) {
          return $( "<div class=''>Mover campo</div>" );
        },
        stop: function(event){
            $(".field-current").each(function(){
                var position = $(".field-current").index(this);
                $(".position-val").eq(position).val(position);
            });
        }
    }).disableSelection();
    
    $("#dig_name").keyup(function () {
        var val = $(this).val();
        $("#dir_name").val(slug(val, '-'));
        $("#table_name").val(slug(val, '_'));
    });

    $("#name_field").keyup(function () {
        var val = $(this).val();
        $("#column_field").val(slug(val, '_'));
    });
    
    $("#modal-new-field").on("change", ".select-input", function () {
        if_input_select();
    });

    $("#modal-new-field").on("change", ".select-options", function () {
        var option = $(this).val();
        var index = $(".select-options").index(this);
        $(".select-label").eq(index).html($("<option>").val("").html("Carregando.."));
        $.ajax({
            url: app_path + "sections/list-columns-json",
            data: {table: option},
            dataType: "json",
            type: "POST",
            success: function (data) {
                var total = data.length;
                $(".select-label").eq(index).html($("<option>").val("").html("Selecione"));
                for (var i = 0; i < total; i++) {
                    var column = data[i];
                    var option = $("<option>").val(column).html(column);
                    $(".select-label").append(option);
                }
            }
        });
    });
    
    
    String.prototype.replaceAll = function(search, replacement) {
        var target = this;
        return target.replace(new RegExp(search, 'g'), replacement);
    };
    
    $("#btn-add-field").click(function () {
        var id_current = $("#modal-new-field").attr("data-index");
        if (id_current !== "") {
            clean_inputs();
        }
    });

    $("#btn-save").click(function () {
        var fields = $("#fields");
        var name = $("#name_field");
        var type_input = $("#input_field");
        var list_registers = $("#list_registers_field");
        var required = $("#required_field");
        var options = $("#options_field");
        var label_options = $("#label_options_field");
        var trigger_select = $("#trigger_select_field");
        var param_attr = $(".param_attr_field");
        var value_attr = $(".value_attr_field");
        var observation = $("#observation_field");
        var column = $("#column_field");
        var type_column = $("#type_field");
        var limit_column = $("#limit_column_field");
        var default_column = $("#default_field");
        var comment_column = $("#comment_field");
        var unique = $("#unique_field");
        var plugins = $(".plugin_field");
        var table = $("#table_name");
        var attributes = new Object();
        var plugins_input = '';
        var options_selected = new Array();
        var id_current = $("#modal-new-field").attr("data-index");
        if (name.val() == "" || type_input.val() == "" || column.val() == "" || type_column == "") {
            $("#msg-modal").removeClass("hide").text("Todos os campos com asterísco são obrigatórios.");
            return false;
        } else if (name_exists(name.val(), id_current) === true) {
            $("#msg-modal").removeClass("hide").text("O nome desse campo já existe, tente outro.");
            return false;
        } else if (column_exists(column.val(), id_current) === true) {
            $("#msg-modal").removeClass("hide").text("O nome dessa coluna já existe, tente outro.");
            return false;
        } else if (column.val() == table.val()) {
            $("#msg-modal").removeClass("hide").text("O nome da coluna não pode ser igual ao nome da tabela.");
            return false;
        }
        var i = 0;
        label_options.children("option").each(function(){
            var value = $(this).val();
            options_selected[i] = value;
            i++;
        });
        
        var i = 0;
        param_attr.each(function () {
            var index = param_attr.index(this);
            var param = $(this).val();
            var value = value_attr.eq(index).val();
            if (param != '' && value != '') {
                attributes[i] = {[param]:value};
            }
            i++;
        });
        var attributes_json = JSON.stringify(attributes);
        plugins.each(function () {
            var checked = $(this).prop("checked");
            if (checked) {
                var plugin = $(this).val();
                plugins_input += plugin + '|';
            }
        });
        if(id_current===""){
            var index = $("#fields .field-current").length;
        }else{
            var index = id_current;
        }
        var field = new EJS({url: app_assets + "project/ejs/list-field.ejs"}).render({
                name: name.val(),
                input: type_input.val(),
                list_registers: list_registers.val(),
                required: required.val(),
                options: options.val(),
                label_options: label_options.val(),
                trigger_select: trigger_select.val(),
                attributes: attributes_json,
                observation: observation.val(),
                column: column.val(),
                type_column: type_column.val(),
                limit_column: limit_column.val(),
                default_column: default_column.val(),
                comment_column: comment_column.val(),
                unique: unique.val(),
                plugin: plugins_input,
                index: index,
                options_selected: JSON.stringify(options_selected)
            });
        if (id_current === '0' || id_current >= 1) {
            $("#fields .field-current").eq(id_current).html("");
            $("#fields .field-current").eq(id_current).html(field.replace(/<tr.+?>/,"").replace("</tr>",""));
        }else{
            fields.append(field);
        }
        clean_inputs();
        $(".msg-is-empty").remove();
    });

    $("#add-attr").click(function () {
        add_attr('', '');
    });
    
    $("#fields").on("click", ".btn-edit", function(){
        var index = $(".btn-edit").index(this);
        var id = $(this).data("index");
        var id_current = $("#modal-new-field").data("index");
        if(id_current !== id){
            clean_inputs();
            $("#modal-new-field").attr("data-index",id);
            var id_current = $("#modal-new-field").data("index");
            var name_val = $(".name-val").eq(index).val();
            var input_val = $(".input-val").eq(index).val();
            var list_registers_val = $(".list-registers-val").eq(index).val();
            var options_val = $(".options-val").eq(index).val();
            var label_options_val = $(".label-options-val").eq(index).val();
            var trigger_select_val = $(".trigger-select-val").eq(index).val();
            var column_val = $(".column-val").eq(index).val();
            var type_val = $(".type-val").eq(index).val();
            var plugins_val = $(".plugins-val").eq(index).val();
            var require_val = $(".require-val").eq(index).val();
            var observation_val = $(".observation-val").eq(index).val();
            var attributes_val = $(".attributes-val").eq(index).val();
            var limit_val = $(".limit-val").eq(index).val();
            var unique_val = $(".unique-val").eq(index).val();
            var default_val = $(".default-val").eq(index).val();
            var comment_val = $(".comment-val").eq(index).val();
            var options_selected = $(".options-selected").eq(index).val();

            if(plugins_val!=''){
                plugins_val = plugins_val.split("|");
                $(".plugin_field").each(function(){
                    var plugin = $(this).val();
                    if($.inArray(plugin, plugins_val)>=0){
                        $(this).prop('checked', true);
                    }
                });
            }
            if(attributes_val!=''){
                if (attributes_val.indexOf('{') != '-1') {
                    attr = $.parseJSON(attributes_val);
                } else {
                    attr = new Object();
                }
                var total = Object.keys(attr).length;
                $(".attr-current").html("");
                for(var i = 0;i<total;i++){
                    var attr_current = attr[i];
                    for(var key in attr_current) {
                        var value = attr_current[key];
                        add_attr(key,value);
                    }
                }
            }
            if(options_selected != "" && options_selected != "[]" && options_selected != undefined){
                options_selected = $.parseJSON(options_selected);
                $("#label_options_field").html("");
                if(options_selected.length>0){
                    for(var i=0;i<options_selected.length; i++){
                        var col = options_selected[i];
                        $("#label_options_field").append($("<option>").val(col).text(col));
                    }
                }
                $("#label_options_field").val(label_options_val);
            }
            
            $("#name_field").val(name_val);
            $("#input_field").val(input_val);
            $("#list_registers_field").val(list_registers_val);
            $("#required_field").val(require_val);
            $("#options_field").val(options_val);
            $("#trigger_select_field").val(trigger_select_val);
            $("#observation_field").val(observation_val);
            $("#column_field").val(column_val);
            $("#type_field").val(type_val);
            $("#limit_column_field").val(limit_val);
            $("#unique_field").val(unique_val);
            $("#default_field").val(default_val);
            $("#comment_field").val(comment_val);
            if_input_select();
        }
    });
    
    $("#form-import").submit(function(){
        var fields = $("#fields");
        var table = $("#table-value").val();
        $.ajax({
            url: app_path+"sections/list-columns-import",
            type : "POST",
            dataType: "json",
            data : $(this).serialize(),
            success : function(data){
                if(data.error){
                    $("#msg-import").html("<div class='alert alert-danger'>"+data.message+"</div>");
                }else{
                    var columns = data.columns;
                    var total_columns = columns.length;
                    $("#dig_name, #dir_name, #table_name").val(table).attr('readonly','');
                    if(total_columns>0){
                        $(".msg-is-empty, .field-current, #btn-add-field").remove();
                        for(var i = 0; i<total_columns; i++){
                            var col_name = columns[i].Field;
                            var col_type = columns[i].Type;
                            var col_limit = columns[i].Limit;
                            var col_default = columns[i].Default;
                            var primary = columns[i].Key;
                            if(primary != 'PRI'){
                                var index = $("#fields .field-current").length;
                                var field = new EJS({url: app_assets + "project/ejs/list-field.ejs"}).render({
                                    name: col_name,
                                    input: "text",
                                    list_registers: 0,
                                    required: 0,
                                    options: '',
                                    label_options: '',
                                    trigger_select: '',
                                    attributes: '',
                                    observation: '',
                                    column: col_name,
                                    type_column: col_type,
                                    limit_column: col_limit,
                                    default_column: col_default,
                                    comment_column: '',
                                    unique: '',
                                    plugin: '',
                                    index: index,
                                    options_selected: ''
                                });
                                fields.append(field);
                            }
                        }
                    }
                    $("#column_field, #type_field, #limit_column_field, #default_field, #comment_field").attr('readonly','');
                    $(".field-current input[type=checkbox]").attr('disabled','');
                    $("#modal-import").modal('toggle');
                    $("#import-value").val("true");
                }
            }
        });
        return false;
    });

});