$(function () {
    delete_file = function (param) {
        var table = param.table;
        var target_class_del = param.target_class_del;
        var column_list = param.column_list;
        var column_del = param.column_del;
        var position = param.position;
        var id_register = param.id_register;
        var file = param.file;
        var url = param.url;
        var bt_delete = param.bt_delete;
        var loading = param.loading;
        if (target_class_del == '' || column_list == '' || column_del == '' || table == '')
            return false;
        if (bt_delete != '')
            $(bt_delete).hide();
        if (loading != '')
            $(loading).show();
        var last_values = $('.' + column_del + '_last_value').val();
        if (last_values != '') {
            last_values = $.parseJSON(last_values);
            total = last_values.length;
            if (total == 0) {
                alert('Houve um erro ao tentar deletar o arquivo, entre em contato com o administrador.');
                return false;
            }

            for (x = 0; x < total; x++) {
                if (last_values[x].file == file)
                    delete last_values[x];
            }
            $('.' + column_del + '_last_value').val(convertToText(last_values));

        }
        $.ajax({
            url: url + "/view/ajax/delete-file.php",
            type: "POST",
            data: {table: table, column_list: column_list, column_del: column_del, id_reg: id_register, file: file},
            dataType: "json",
            success: function (data) {
                if (data.error) {
                    alert(data.message);
                } else {
                    $('.' + target_class_del).eq(position).remove();
                }
                if (bt_delete != '')
                    $(bt_delete).show();
                if (loading != '')
                    $(loading).hide();
            }
        });

    }
    function convertToText(obj) {
        //create an array that will later be joined into a string.
        var string = [];

        //is object
        //    Both arrays and objects seem to return "object"
        //    when typeof(obj) is applied to them. So instead
        //    I am checking to see if they have the property
        //    join, which normal objects don't have but
        //    arrays do.
        if (typeof (obj) == "object" && (obj.join == undefined)) {
            string.push("{");
            for (prop in obj) {
                string.push(prop, ": ", convertToText(obj[prop]), ",");
            }
            ;
            string.push("}");

            //is array
        } else if (typeof (obj) == "object" && !(obj.join == undefined)) {
            string.push("[")
            for (prop in obj) {
                string.push(convertToText(obj[prop]), ",");
            }
            string.push("]")

            //is function
        } else if (typeof (obj) == "function") {
            string.push(obj.toString())

            //all other values can be done with JSON.stringify
        } else {
            string.push(JSON.stringify(obj))
        }

        return string.join("")
    }
});