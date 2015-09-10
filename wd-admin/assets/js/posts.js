$(function () {
    posts = {
        page: null,
        url: null,
        id_post: null,
        init: function () {
            $('.question').tooltip('hide');
            $("input[type=text],textarea").eq(0).focus();
            $(".loading-url").hide();
            $(".url-box").show();
            $("input.datetime_func").datetimepicker({
                dateFormat: "dd/mm/yy",
                timeFormat: "HH:mm:00",
                numberOfMonths: 3,
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                nextText: 'Próximo',
                prevText: 'Anterior'
            });
            $("input.time_func").timepicker({
                timeFormat: "HH:mm:00"
            });
            $("input.date_func").datepicker({
                dateFormat: "dd/mm/yy",
                numberOfMonths: 3,
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                nextText: 'Próximo',
                prevText: 'Anterior'
            });
            $('input[type="text"]').setMask();
            $(".new-pass").click(function(){
                var pos = $(".new-pass").index(this);
                var val = $(this).attr('data-val');
                if($(this).hasClass('bt-new')){ 
                    $(".pass-change").eq(pos).removeClass('hidden');
                    $(".pass-change input").eq(pos).focus().val('');
                    $(this).removeClass('bt-new').addClass('bt-cancel').text("Cancelar");
                }else{
                    $(".pass-change").eq(pos).addClass('hidden').val(val);
                    $(".pass-change input").eq(pos).val(val);
                    $(this).removeClass('bt-cancel').addClass('bt-new').text("Alterar");
                }
            });
        },
        keyup_url: function () {
            var url = posts.url;
            var id_post = posts.id_post;
            $(".wd_url").keyup(function () {
                var value = $(this).val();
                $(".alert-url").show();
                $.ajax({
                    type: "POST",
                    url: url + "/view/ajax/posts.php",
                    data: {value: value, id_post:id_post, func: 'url_encode'},
                    dataType: 'json',
                    success: function (data) {
                        if (data.error == true) {
                            $(".alert-url").removeClass("alert-success");
                            $(".alert-url").addClass("alert-danger");
                            $(".status_url").text("Indisponível!");
                        } else {
                            $(".alert-url").removeClass("alert-danger");
                            $(".alert-url").addClass("alert-success");
                            $(".status_url").text("Disponível!");
                        }
                        $(".title_change").text(data.url);

                    }
                });
            });
        },
        form_dynamic: function () {
            var page = posts.page;
            var url = posts.url;
            $(".list_form_dynamic").sortable({
                handle: ".moveli",
                stop: function (event, ui) {
                    var count = 0;
                    var array = Array();
                    $(".list_form_dynamic li").each(function () {
                        var realtype = $(this).attr("form-type");
                        array[count] = {
                            "id_page": page,
                            "opt_content": realtype,
                            "position": $(".list_form_dynamic li").index(this)
                        };
                        count++;
                    });
                    $.ajax({
                        type: "POST",
                        url: url + "/view/ajax/change_position.php",
                        data: {array: array},
                        success: function (data) {
                        }
                    });
                }
            });
        },
        remove_register: function () {
            $(".remove_register").click(function () {
                var $id = $(this).attr("data-id");
                $(".register_id").val($id);
            });
            $(".cancel").click(function () {
                $(".register_id").val("");
            });
        },
        change_status: function () {
            var url = posts.url;
            $(".allow_post").click(function () {
                var id_post = $(this).attr("data-id");
                var position = $(".allow_post").index(this);
                $.ajax({
                    url: url + "/view/ajax/posts.php",
                    type: "POST",
                    dataType: "json",
                    data: {func: "allow_post", id_post: id_post},
                    success: function (data) {
                        if (data.error == false) {
                            var total_pending = parseInt($(".total_pending").text());
                            var total_public = parseInt($(".total_public").text());

                            $(".total_pending").text(total_pending - 1);
                            $(".total_public").text(total_public + 1);
                            $(".reg_current").eq(position).slideUp(200);
                        } else {
                            alert(data.message);
                        }
                    }
                });
            });
            $("a.deny_post").click(function () {
                var id_post = $(this).attr("data-id");
                var position = $("a.deny_post").index(this);
                $("button.deny_post").click(function () {
                    var message = $(".message_deny").val();
                    $.ajax({
                        url: url + "/view/ajax/posts.php",
                        type: "POST",
                        dataType: "json",
                        data: {func: "deny_post", id_post: id_post, message: message},
                        success: function (data) {
                            if (data.error == false) {
                                var total_pending = parseInt($(".total_pending").text());
                                var total_deny = parseInt($(".total_deny").text());
                                $(".total_pending").text(total_pending - 1);
                                $(".total_deny").text(total_deny + 1);
                                $(".reg_current").eq(position).slideUp(200);
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                });
            });

        },
        expiration: function () {
            $(".wd_expiration").blur(function () {
                posts.get_expiration($(".wd_expiration").val())
            });
        },
        get_expiration: function (date) {
            var url = posts.url;
            $.ajax({
                url: url + "/view/ajax/posts.php",
                type: "POST",
                dataType: "json",
                data: {func: "get_expiration", date: date},
                success: function (data) {
                    if (data.error == false) {
                        $(".get_date_expiration").html(data.message);
                    } else {
                        alert(data.message);
                    }
                }
            });
        },
        column_manager: function (param) {
            var id_column_manager = param.id_column_manager;
            var hidden_reg = param.hidden_reg;
            var total = $("#" + id_column_manager + " th").length;

            $('#' + id_column_manager).columnManager({
                listTargetID: 'sc-options',
                saveState: true,
                hideInList: [total],
                colsHidden: hidden_reg,
                onClass: 'opt-on',
                offClass: 'opt-off',
                cellVisible: function (data) {
                    console.log(data);
                }
            });
        },
        delete_file: function () {
            $('.delete_file').click(function (e) {
                var url = posts.url;
                var table = posts.table;
                var target_class_del = posts.target_class_del;
                var column_list = posts.column_list;
                var id_post = posts.id_post;
                var position = $('.delete_file').index(this);
                var column_del = $('.delete_file').eq(position).attr("data-column");
                var file = $('.delete_file').eq(position).attr("data-file");

                if (confirm("Deseja realmente deletar esse arquivo ?")) {
                    e.preventDefault();
                } else {
                    return false;
                }

                if (target_class_del == '' || column_list == '' || column_del == '' || table == '' || file == '')
                    return false;

                $.ajax({
                    url: url + "/view/ajax/delete-file.php",
                    type: "POST",
                    data: {table: table, column_list: column_list, column_del: column_del, id_reg: id_post, file: file},
                    dataType: "json",
                    success: function (data) {
                        if (data.error) {
                            alert(data.message);
                        } else {
                            $('.' + target_class_del).eq(position).remove();
                        }
                    }
                });

            });
        }
    }
});