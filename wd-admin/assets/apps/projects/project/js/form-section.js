$(function () {
    var slug = function (str, transform_space) {
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

    $("#dig_name").keyup(function () {
        var val = $(this).val();
        $("#dir_name").val(slug(val, '-'));
        $("#table_name").val(slug(val, '_'));
    });

    $("#fields").on("keyup", ".dig_name", function () {
        var val = $(this).val();
        var index = $(".dig_name").index(this);
        $(".column").eq(index).val(slug(val, '_'));
    });

    $("#addField").click(function () {
        var html = $(".field").eq(0).html();
        $("#fields").append("<div class='field'>" + html + "</div>");
        var final = $(".field").length - 1;
        $(".field:eq(" + final + ") input").val("");
        $(".field:eq(" + final + ") select option").removeAttr('selected');
        $(".field:eq(" + final + ") checkbox").removeAttr('checked');
    });

    $(".select-input, .radio-input").change(function () {
        var input = $(this).val();
        var index = $(".select-input").index(this);
        if (input == "select" || input == "checkbox" || input == "radio") {
            $(".options-field:eq("+index+"), .label-options-field:eq("+index+")").removeClass('hide');
        } else {
            $(".options-field:eq("+index+"), .label-options-field:eq("+index+")").addClass('hide');
        }
        if(input == "select"){
            $(".trigger-field").eq(index).removeClass('hide');
        }else{
            $(".trigger-field").eq(index).addClass('hide');
        }
    });

    $(".select-options").change(function () {
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


});