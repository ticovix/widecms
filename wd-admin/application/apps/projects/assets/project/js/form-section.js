$(function () {

    var fields = $("#fields");
    var btn_add_field = $("#btn-add-field");
    var modal_new_field = $("#modal-new-field");
    var modal_select = $("#modal-select");

    // Fields
    var name_field = $("#name_field");
    var type_input_field = $("#input_field");
    var list_registers_field = $("#list_registers_field");
    var required_field = $("#required_field");
    var options_field = $("#options_field");
    var label_options_field = $("#label_options_field");
    var trigger_select_field = $("#trigger_select_field");
    var observation_field = $("#observation_field");
    var column_field = $("#column_field");
    var type_column_field = $("#type_field");
    var limit_column_field = $("#limit_column_field");
    var default_column_field = $("#default_field");
    var comment_column_field = $("#comment_field");
    var unique_field = $("#unique_field");
    var plugins_field = $(".plugin_field");
    var table_field = $("#table_name");

    //Fields Upload
    var extensions_allowed = $("#extensions_allowed");
    var image_resize = $("#input_image_resize");
    var image_x = $("#input_image_x");
    var image_y = $("#input_image_y");
    var image_ratio = $("#input_image_ratio");
    var image_ratio_x = $("#input_image_ratio_x");
    var image_ratio_y = $("#input_image_ratio_y");
    var image_ratio_crop = $("#input_image_ratio_crop");
    var image_ratio_fill = $("#input_image_ratio_fill");
    var image_background_color = $("#input_image_background_color");
    var image_convert = $("#input_image_convert");
    var image_text = $("#input_image_text");
    var image_text_color = $("#input_image_text_color");
    var image_text_background = $("#input_image_text_background");
    var image_text_opacity = $("#input_image_text_opacity");
    var image_text_background_opacity = $("#input_image_text_background_opacity");
    var image_text_padding = $("#input_image_text_padding");
    var image_text_position = $("#input_image_text_position");
    var image_text_direction = $("#input_image_text_direction");
    var image_text_x = $("#input_image_text_x");
    var image_text_y = $("#input_image_text_y");
    var btn_refresh_image = $("#btn_refresh_image");
    var thumbs = new Object();
    // Image example
    var image_example = $("#image_example");

    fields.on("click", ".field-current > td:not(:last-child)", function (e) {
        modal_new_field.modal('toggle');
    });

    $("#sortable").sortable({
        items: "> tr",
        appendTo: "parent",
        revert: true,
        cursor: "move",
        cursorAt: {top: -5, left: -5},
        helper: function (event) {
            return $("<div class='fa fa-arrows-v fa-2x'></div>");
        },
        stop: function (event) {
            $(".field-current").each(function () {
                var position = $(".field-current").index(this);
                $(".position-val").eq(position).val(position);
            });
        }
    }).disableSelection();

    $("#dig_name").keyup(function () {
        var val = $(this).val();
        $("#dir_name").val(slug(val, '-'));
        table_field.val(slug(val, '_'));
    });


    $("#name_field").keyup(function () {
        var val = $(this).val();
        column_field.val(slug(val, '_'));
    });

    modal_new_field.on("change", ".select-input", function () {
        if_input_select();
    });

    modal_select.on("change", ".select-options", function () {
        var option = $(this).val();
        var index = $(".select-options").index(this);
        $(".select-label").eq(index).html($("<option>").val("").html(LANG.loading));
        $.ajax({
            url: app_path + "sections/list-columns-json",
            data: {table: option},
            dataType: "json",
            type: "POST",
            success: function (data) {
                var total = data.length;
                $(".select-label").eq(index).html($("<option>").val("").html(LANG.option_select));
                for (var i = 0; i < total; i++) {
                    var column = data[i];
                    var option = $("<option>").val(column).html(column);
                    $(".select-label").append(option);
                }
            }
        });
    });


    String.prototype.replaceAll = function (search, replacement) {
        var target = this;

        return target.replace(new RegExp(search, 'g'), replacement);
    };


    btn_add_field.click(function () {
        var id_current = modal_new_field.attr("data-current");
        if (id_current !== "") {
            clean_inputs();
            modal_new_field.attr("data-current","");
        }

        list_selects_trigger();
    });


    $(".x_content").on("click","#btn-save",function () {
        var attributes = new Object();
        var plugins_input = new Array();
        var options_selected = new Array();
        var id_current = modal_new_field.attr("data-index");
        var sort_current = modal_new_field.attr("data-current");
        var msg_modal = $("#msg-modal");
        if (name_field.val() == "" || type_input_field.val() == "" || column_field.val() == "" || type_column_field.val() == "") {
            msg_modal.removeClass("hide").text(LANG.error_all_fields_required);
            return false;
        } else if (name_exists(name_field.val(), sort_current) === true) {
            msg_modal.removeClass("hide").text(LANG.error_name_exists);
            return false;
        } else if (column_exists(column_field.val(), sort_current) === true) {
            msg_modal.removeClass("hide").text(LANG.error_column_exists);
            return false;
        } else if (column_field.val() == table_field.val()) {
            msg_modal.removeClass("hide").text(LANG.error_column_equals_table);
            return false;
        }

        var i = 0;
        label_options_field.children("option").each(function () {
            var value = $(this).val();
            options_selected[i] = value;
            i++;
        });

        var i = 0;
        $(".param_attr_field").each(function () {
            var index = $(".param_attr_field").index(this);
            var param = $(this).val();
            var value = $(".value_attr_field").eq(index).val();
            if (param != '' && value != '') {
                attributes[i] = {[param]:value};
                i++;
            }
        });

        var attributes_json = JSON.stringify(attributes);
        var i = 0;
        plugins_field.each(function () {
            var checked = $(this).prop("checked");
            if (checked) {
                var plugin = $(this).val();
                plugins_input[i] = plugin;
                i++;
            }

        });
        plugins_input = plugins_input.join('|');

        var i = 0;
        $(".image_thumb_preffix").each(function(){
            var index = $(".image_thumb_preffix").index(this);
            var preffix = $(this).val();
            var width = $(".image_thumb_width").eq(index).val();
            var height = $(".image_thumb_height").eq(index).val();
            var crop = $(".image_thumb_ratio_crop").eq(index).val();
            if(preffix != '' && width != ''){
                thumbs[i] = {
                    preffix: preffix,
                    width: width,
                    height: height,
                    crop: crop,
                }
                i++;
            }
        });

        var thumbnails = JSON.stringify(thumbs).replaceAll("\"","'");
        var index = id_current;
        var current_field = sort_current;

        if (id_current === "") {
            index = current_field = $("#fields .field-current").length;
        }

        var field = new EJS({url: app_assets + "project/ejs/list-field.ejs"}).render({
            name: name_field.val(),
            input: type_input_field.val(),
            list_registers: list_registers_field.val(),
            required: required_field.val(),
            options: options_field.val(),
            label_options: label_options_field.val(),
            trigger_select: trigger_select_field.val(),
            attributes: attributes_json,
            observation: observation_field.val(),
            column: column_field.val(),
            type_column: type_column_field.val(),
            limit_column: limit_column_field.val(),
            default_column: default_column_field.val(),
            comment_column: comment_column_field.val(),
            unique: unique_field.val(),
            plugin: plugins_input,
            index: index,
            position: current_field,
            options_selected: JSON.stringify(options_selected),
            // Fields of config to upload
            extensions_allowed: extensions_allowed.val(),
            image_resize: image_resize.val(),
            image_x: image_x.val(),
            image_y: image_y.val(),
            image_ratio: image_ratio.val(),
            image_ratio_x: image_ratio_x.val(),
            image_ratio_y: image_ratio_y.val(),
            image_ratio_crop: image_ratio_crop.val(),
            image_ratio_fill: image_ratio_fill.val(),
            image_background_color: image_background_color.val(),
            image_convert: image_convert.val(),
            image_text: image_text.val(),
            image_text_color: image_text_color.val(),
            image_text_background: image_text_background.val(),
            image_text_opacity: image_text_opacity.val(),
            image_text_background_opacity: image_text_background_opacity.val(),
            image_text_padding: image_text_padding.val(),
            image_text_position: image_text_position.val(),
            image_text_direction: image_text_direction.val(),
            image_text_x: image_text_x.val(),
            image_text_y: image_text_y.val(),
            image_thumbnails: thumbnails
        });
        if (sort_current === '0' || sort_current >= 1) {
            $("#fields .field-current").eq(sort_current).html("");
            $("#fields .field-current").eq(sort_current).html(field.replace(/<tr.+?>/, "").replace("</tr>", ""));
        } else {
            fields.append(field);
        }

        clean_inputs();
        $(".msg-is-empty").remove();
        modal_new_field.modal("toggle");
    });

    $("#add-attr").click(function () {
        add_attr('', '');
    });

    fields.on("click", ".btn-edit", function () {
        var index = $(".btn-edit").index(this);
        var id = $(this).data("index");
        var id_current = modal_new_field.data("index");
        if (id_current !== id) {
            clean_inputs();
            modal_new_field.attr("data-index", id);
            modal_new_field.attr("data-current", $(".btn-edit").index(this));
            var id_current = modal_new_field.data("index");
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
            // Input of config to upload
            var extensions_allowed_val = $(".extensions-allowed-val").eq(index).val();
            var image_resize_val = $(".image-resize-val").eq(index).val();
            var image_x_val = $(".image-x-val").eq(index).val();
            var image_y_val = $(".image-y-val").eq(index).val();
            var image_ratio_val = $(".image-ratio-val").eq(index).val();
            var image_ratio_x_val = $(".image-ratio-x-val").eq(index).val();
            var image_ratio_y_val = $(".image-ratio-y-val").eq(index).val();
            var image_ratio_crop_val = $(".image-ratio-crop-val").eq(index).val();
            var image_ratio_fill_val = $(".image-ratio-fill-val").eq(index).val();
            var image_background_color_val = $(".image-background-color-val").eq(index).val();
            var image_convert_val = $(".image-convert-val").eq(index).val();
            var image_text_val = $(".image-text-val").eq(index).val();
            var image_text_color_val = $(".image-text-color-val").eq(index).val();
            var image_text_background_val = $(".image-text-background-val").eq(index).val();
            var image_text_opacity_val = $(".image-text-opacity-val").eq(index).val();
            var image_text_background_opacity_val = $(".image-text-background-opacity-val").eq(index).val();
            var image_text_padding_val = $(".image-text-padding-val").eq(index).val();
            var image_text_position_val = $(".image-text-position-val").eq(index).val();
            var image_text_direction_val = $(".image-text-direction-val").eq(index).val();
            var image_text_x_val = $(".image-text-x-val").eq(index).val();
            var image_text_y_val = $(".image-text-y-val").eq(index).val();
            var image_thumbnails_val = $(".image-thumbnails-val").eq(index).val();


            if (plugins_val != '') {
                plugins_val = plugins_val.split("|");
                plugins_field.each(function () {
                    var plugin = $(this).val();
                    if ($.inArray(plugin, plugins_val) >= 0) {
                        $(this).prop('checked', true);
                    }
                });
            }

            if (attributes_val != '') {
                if (attributes_val.indexOf('{') != '-1') {
                    var attr = $.parseJSON(attributes_val.replaceAll("'","\""));
                } else {
                    var attr = new Object();
                }

                var total = Object.keys(attr).length;
                $(".attr-current").html("");
                if(total>0){
                    for (var i = 0; i < total; i++) {
                        var attr_current = attr[i];
                        for (var key in attr_current) {
                            var value = attr_current[key];
                            add_attr(key, value);
                        }
                    }
                }else{
                    add_attr('','');
                }
            }

            if(image_thumbnails_val != ''){
                if (image_thumbnails_val.indexOf('{') != '-1') {
                    var thumbs = $.parseJSON(image_thumbnails_val.replaceAll("'","\""));
                } else {
                    var thumbs = new Object();
                }

                var total = Object.keys(thumbs).length;
                $(".thumbnails").html("");
                if(total>0){
                    for (var i = 0; i < total; i++) {
                        var thumb = thumbs[i];
                        add_thumbnail({
                            preffix: thumb.preffix,
                            width: thumb.width,
                            height: thumb.height,
                            crop: thumb.crop
                        });
                    }
                }else{
                    add_thumbnail({});
                }
            }
            if (options_selected != "" && options_selected != "[]" && options_selected != undefined) {
                options_selected = $.parseJSON(options_selected);
                label_options_field.html("");
                if (options_selected.length > 0) {
                    for (var i = 0; i < options_selected.length; i++) {
                        var col = options_selected[i];
                        label_options_field.append($("<option>").val(col).text(col));
                    }
                }

                label_options_field.val(label_options_val);
            }

            name_field.val(name_val);
            type_input_field.val(input_val);
            list_registers_field.val(list_registers_val);
            required_field.val(require_val);
            options_field.val(options_val);
            trigger_select_field.val(trigger_select_val);
            observation_field.val(observation_val);
            column_field.val(column_val);
            type_column_field.val(type_val);
            limit_column_field.val(limit_val);
            unique_field.val(unique_val);
            default_column_field.val(default_val);
            comment_column_field.val(comment_val);
            // Input of config to upload
            extensions_allowed.val(extensions_allowed_val);
            image_resize.val(image_resize_val);
            image_x.val(image_x_val);
            image_y.val(image_y_val);
            image_ratio.val(image_ratio_val);
            image_ratio_x.val(image_ratio_x_val);
            image_ratio_y.val(image_ratio_y_val);
            image_ratio_crop.val(image_ratio_crop_val);
            image_ratio_fill.val(image_ratio_fill_val);
            image_background_color.val(image_background_color_val);
            image_convert.val(image_convert_val);
            image_text.val(image_text_val);
            image_text_color.val(image_text_color_val);
            image_text_background.val(image_text_background_val);
            image_text_opacity.val(image_text_opacity_val);
            image_text_background_opacity.val(image_text_background_opacity_val);
            image_text_padding.val(image_text_padding_val);
            image_text_position.val(image_text_position_val);
            image_text_direction.val(image_text_direction_val);
            image_text_x.val(image_text_x_val);
            image_text_y.val(image_text_y_val);


            if_input_select();
            if_image_resize();
            refresh_image_example();
            list_selects_trigger(column_val);
        }
    });

    image_resize.change(function(){
        if_image_resize();
    });

    btn_refresh_image.click(function(){
        refresh_image_example();
    });

    $("#modal-upload select").change(function(){
        refresh_image_example();
    });
    $("#modal-upload input[type=text]").blur(function(){
        refresh_image_example();
    });

    $("#btn-add-thumbnail").click(function(){
        add_thumbnail({});
    });

    /*** Methods****/
    function slug(str, transform_space) {
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
    }
    function if_input_select() {
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

        if (input == "file" || input == "multifile") {
            $("#content-field").addClass("input-group");
            $("#btn-config").removeClass("hide").attr("href", "#modal-upload");
        } else if (input == "select" || input == "checkbox") {
            $("#content-field").addClass("input-group");
            $("#btn-config").removeClass("hide").attr("href", "#modal-select");
        } else {
            $("#content-field").removeClass("input-group");
            $("#btn-config").addClass("hide");
        }
    }
    function refresh_image_example(){
        var x = 0;
        var arr = new Array();
        if(image_resize.val()!="false"){
                arr[x] = "image_resize="+image_resize.val();
        }

        if(image_x.val()!=""){
                x++;
                arr[x] = "image_x="+image_x.val();
        }

        if(image_y.val()!=""){
                x++;
                arr[x] = "image_y="+image_y.val();
        }

        if(image_ratio.val()!=""){
                x++;
                arr[x] = "image_ratio="+image_ratio.val();
        }

        if(image_ratio_x.val()!=""){
                x++;
                arr[x] = "image_ratio_x="+image_ratio_x.val();
        }

        if(image_ratio_y.val()!=""){
                x++;
                arr[x] = "image_ratio_y="+image_ratio_y.val();
        }

        if(image_ratio_crop.val()!=""){
                x++;
                arr[x] = "image_ratio_crop="+image_ratio_crop.val();
        }

        if(image_ratio_fill.val()!=""){
                x++;
                arr[x] = "image_ratio_fill="+image_ratio_fill.val();
        }

        if(image_background_color.val()!=""){
                x++;
                arr[x] = "image_background_color="+image_background_color.val().replace('#','');
        }

        if(image_convert.val()!=""){
                x++;
                arr[x] = "image_convert="+image_convert.val();
        }

        if(image_text.val()!=""){
                x++;
                arr[x] = "image_text="+image_text.val();
        }

        if(image_text_color.val()!=""){
                x++;
                arr[x] = "image_text_color="+image_text_color.val().replace('#','');
        }

        if(image_text_background.val()!=""){
                x++;
                arr[x] = "image_text_background="+image_text_background.val().replace('#','');
        }

        if(image_text_opacity.val()!=""){
                x++;
                arr[x] = "image_text_opacity="+image_text_opacity.val();
        }

        if(image_text_background_opacity.val()!=""){
                x++;
                arr[x] = "image_text_background_opacity="+image_text_background_opacity.val();
        }

        if(image_text_padding.val()!=""){
                x++;
                arr[x] = "image_text_padding="+image_text_padding.val();
        }

        if(image_text_position.val()!=""){
                x++;
                arr[x] = "image_text_position="+image_text_position.val();
        }

        if(image_text_direction.val()!=""){
                x++;
                arr[x] = "image_text_direction="+image_text_direction.val();
        }

        if(image_text_x.val()!=""){
                x++;
                arr[x] = "image_text_x="+image_text_x.val();
        }

        if(image_text_y.val()!=""){
                x++;
                arr[x] = "image_text_y="+image_text_y.val();
        }

        image_example.attr("src",url+"apps/"+app+"/sections/image-example?"+arr.join("&"));
    }
    function if_image_resize(){
        if(image_resize.val()=="true"){
            image_x.removeAttr("disabled");
            image_y.removeAttr("disabled");
            image_ratio.removeAttr("disabled");
            image_ratio_x.removeAttr("disabled");
            image_ratio_y.removeAttr("disabled");
            image_ratio_crop.removeAttr("disabled");
            image_ratio_fill.removeAttr("disabled");
        }else{
            image_x.attr("disabled","disabled");
            image_y.attr("disabled","disabled");
            image_ratio.attr("disabled","disabled");
            image_ratio_x.attr("disabled","disabled");
            image_ratio_y.attr("disabled","disabled");
            image_ratio_crop.attr("disabled","disabled");
            image_ratio_fill.attr("disabled","disabled");
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
        modal_new_field.attr("data-index", "");
        $("#modal-new-field textarea").val("");
        $("#modal-new-field input").each(function () {
            var type = $(this).attr("type");
            if (type == "text") {
                $(this).val("");
            } else if (type == "checkbox") {
                $(this).removeAttr('checked');
            }
        });

        $(".plugin_field").prop("checked",false);
        $("#modal-new-field select option").prop("selected",false);
        $("#options_field option").prop("selected",false);
        $("#label_options_field").html($("<option>").val("").text(LANG.option_select));
        $("#trigger_select_field").html($("<option>").val("").text(LANG.option_select));
        if_input_select();
    }

    function name_exists(name, index_ignore) {
        var exists = false;
        $(".name-val").each(function () {
            var val = $(this).val();
            var index_cur = $(".name-val").index(this);

            if (String(index_cur) != index_ignore) {
                if (val.toLowerCase() == name.toLowerCase()) {
                    exists = true;
                }
            }
        });
        return exists;
    }

    function list_selects_trigger(column_current){
        $(".field-current").each(function(){
            var input = $(this).find(".input-val").val();
            if(input==="select"){
                var name = $(this).find(".name-val").val();
                var column = $(this).find(".column-val").val();
                if(column!==column_current){
                    $("#trigger_select_field").append(
                            $("<option>").val(column).html(name+" ("+column+")")
                    );
                }
            }
        });
    }

    function column_exists(column, index_ignore) {
        var exists = false;
        $(".column-val").each(function () {
            var val = $(this).val();
            var index_cur = $(".column-val").index(this);
            if (String(index_cur) !== index_ignore) {
                if (val.toLowerCase() == column.toLowerCase()) {
                    exists = true;
                }
            }
        });
        return exists;
    }

    function add_attr(param, value) {
        $(".attr-current").append('<div class="col-sm-6">' +
                '<div class="form-group">' +
                '<input type="text" value="' + param + '" value="" class="form-control param_attr_field" placeholder="'+LANG.label_attribute+'">' +
                '</div>' +
                '</div>' +
                '<div class="col-sm-6">' +
                '<div class="form-group">' +
                '<input type="text" value="' + value + '" value="" class="form-control value_attr_field" placeholder="'+LANG.label_value+'">' +
                '</div>' +
                '</div>');
    }

    function add_thumbnail(data){
        var preffix = (data.preffix!=undefined)?data.preffix:'';
        var width = (data.width!=undefined)?data.width:'';
        var height = (data.height!=undefined)?data.height:'';
        var crop = (data.crop!=undefined)?data.crop:'';

        $(".thumbnails").append(
                '<div class="row form-group">'+
                '<div class="col-sm-3">'+
                        '<label>'+LANG.label_preffix+'*</label>'+
                        '<input type="text" class="input-large form-control image_thumb_preffix" value="'+preffix+'">'+
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<label>'+LANG.label_width+'*</label>'+
                        '<input type="text" class="input-large form-control image_thumb_width" value="'+width+'">'+
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<label>'+LANG.label_height+'</label>'+
                        '<input type="text" class="input-large form-control image_thumb_height" value="'+height+'">'+
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<label>'+LANG.label_crop+'</label>'+
                        '<select class="input-large form-control image_thumb_ratio_crop">'+
                            '<option value="">'+LANG.option_no+'</option>'+
                            '<option value="true" '+((crop=="true")?'selected="selected"':'')+'>'+LANG.option_crop_center+'</option>'+
                            '<option value="L" '+((crop=="L")?'selected="selected"':'')+'>'+LANG.option_crop_left+'</option>'+
                            '<option value="R" '+((crop=="R")?'selected="selected"':'')+'>'+LANG.option_crop_right+'</option>'+
                        '</select>'+
                    '</div>'+
                '</div>'
                );
    }
});