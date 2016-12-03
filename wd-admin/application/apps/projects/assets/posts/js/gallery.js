$(function () {
    var template_modal = new EJS({url: url + "application/apps/projects/assets/posts/ejs/modal-edit-file.ejs"}).render({
        LANG: LANG,
        url: url
    });
    $("body").append(template_modal);

    var my_list = new Object();
    function search_file(array, file_search) {
        var total = Object.keys(array).length;
        for (var i = 0; i < total; i++) {
            var file = array[i].file;
            if (file === file_search) {
                return array[i];
            }
        }

        return false;
    }

    function treat_files_selected(files) {
        var files_selected = new Array();
        if (files.indexOf('{') != '-1') {
            files = $.parseJSON(files);
            var total = Object.keys(files).length;
            for (var i = 0; i < total; i++) {
                files_selected[i] = files[i].file;
            }
        } else {
            files_selected = new Array();
        }

        return files_selected;
    }

    function is_checked(files) {
        var total = Object.keys(files).length;
        for (var i = 0; i < total; i++) {
            var checked = files[i].checked;
            if (checked) {
                return true;
            }
        }

        return false;
    }

    String.prototype.replaceAll = function (search, replacement) {
        var target = this;

        return target.replace(new RegExp(search, 'g'), replacement);
    };

    $(".content-files").on("click", ".btn-edit-file", function () {
        var file = $(this).data("file");
        var field = $(this).parents("div.content-field").find(".input-field").attr("name");
        var title = $(this).attr("title");
        var index = $(".content-files").index($(this).parents("div.content-files"));
        var checked = $(this).parents("div.files-list").hasClass("active");
        $("#modal-edit #field-image-file").attr("href", base_url + "wd-content/upload/" + file);
        $("#modal-edit #field-image-file img").attr("src", url + "apps/gallery/image/thumb/" + file);
        $("#modal-edit #field-title").val(title);
        $("#modal-edit #field-index").val(index);
        $("#modal-edit #field-file").val(file);
        $("#modal-edit #field-edit").val(field);
        $("#modal-edit #field-checked").removeAttr("checked").removeAttr("disabled");
        if (checked) {
            $("#modal-edit #field-checked").prop("checked", "checked").attr("disabled", "disabled");
        }
    });

    $("#modal-edit").on("click", "#btn-save-edit", function () {
        var title = $("#modal-edit #field-title").val();
        var index = $("#modal-edit #field-index").val();
        var file = $("#modal-edit #field-file").val();
        var field = $("#modal-edit #field-edit").val();
        var checked = $("#modal-edit #field-checked").prop("checked");
        var list = $("input#" + field + "_field.input-field").val();
        if (typeof list !== "object" && list.indexOf("{") != "-1") {
            list = $.parseJSON(list.replaceAll("'", "\""));
        }

        var total = Object.keys(list).length;
        for (var i = 0; i < total; i++) {
            var file_ = list[i].file;
            var checked_ = list[i].checked;
            var title_ = list[i].title;
            if (checked == true) {
                checked_ = false;
            }

            if (file_ === file) {
                checked_ = checked;
                title_ = title;
            }
            my_list[i] = {file: file_, checked: checked_, title: title_};
        }

        var json = JSON.stringify(my_list);
        if (json === '{}') {
            json = '';
        }

        $("#" + field + "_field").val(json);
        var file_edit = $(".content-files:eq(" + index + ") .btn-edit-file").filter(function (index) {
            return $(this).attr("data-file") === file;
        });
        file_edit.attr({title: title});
        if (checked) {
            $(".content-files:eq(" + index + ") .files-list").removeClass("active");
            file_edit.parents("div.files-list").addClass("active");
        } else {
            file_edit.parents("div.files-list").removeClass("active");
        }

    });

    $(".content-files").sortable({
        items: ".files-list",
        appendTo: "parent",
        revert: true,
        cursor: "move",
        cursorAt: {top: -5, left: -5},
        stop: function (event) {
            var my_list = new Object();
            var count = 0;
            $(this).find(".files-list").each(function () {
                var btn_file = $(this).find(".btn-edit-file");
                var file = btn_file.data("file");
                var title = btn_file.data("title");
                var checked = $(this).hasClass("active");
                if (title === undefined) {
                    title = null;
                }
                my_list[count] = {file: file, checked: checked, title: title};
                count++;
            });
            $(this).parents(".content-field").find(".input-field").val(JSON.stringify(my_list));
        }
    }).disableSelection();

    $(".btn-gallery").each(function () {
        var btn = $(this);
        var field = btn.data("field");
        var config_upload = $(this).data("config");
        var files_selected = $("#" + field + "_field").val();
        var type_upload = $("#" + field + "_field").attr("multiple");
        var limit = null;

        if (type_upload === undefined || type_upload === false) {
            limit = 1;
        }

        if (config_upload.indexOf('{') != '-1') {
            config_upload = $.parseJSON(config_upload.replaceAll("'", "\""));
        } else {
            config_upload = new Object();
        }

        var params = {
            limit_select: limit,
            files_selecteds: treat_files_selected(files_selected),
            file_deleted: function (files) {
                list_files(btn, files);
            },
            complete: function (files) {
                list_files(btn, files);
            }
        }

        $(".btn-gallery[data-field="+field+"]").gallery($.extend(params, config_upload));
    });

    function list_files(btn, files) {
        var index = $(".btn-gallery").index(btn);
        var my_list = new Object();
        var field = btn.data("field");
        var limit = null;

        var files_selected = $("#" + field + "_field").val();
        if (files_selected.indexOf('{') != '-1') {
            var my_list = $.parseJSON(files_selected.replaceAll("'", "\""));
        }

        if (typeof files === 'string') {
            var object_files = new Array();
            object_files[0] = files;
            files = object_files;
        }

        var total = files.length;
        var my_new_list = new Object();
        var ischecked = is_checked(my_list);
        for (var i = 0; i < total; i++) {
            var file = files[i];
            var checked = false;
            var title = null;
            if (data = search_file(my_list, file)) {
                checked = data.checked;
                title = data.title;
            }

            if (i == 0 && ischecked === false || limit === 1) {
                checked = true;
            }

            my_new_list[i] = {file: file, checked: checked, title: title};
        }

        var json = JSON.stringify(my_new_list);
        if (json === '{}') {
            json = '';
        }

        $("#" + field + "_field").val(json);

        var total = Object.keys(my_new_list).length;
        $(".content-files").eq(index).html("");
        for (var i = 0; i < total; i++) {
            var file = my_new_list[i].file;
            var title = my_new_list[i].title;
            var checked = my_new_list[i].checked;
            var img = $("<img>").addClass("img-responsive").attr("src", url + "apps/gallery/image/thumb/" + file);
            var div = $("<div>").addClass("files-list thumbnail").html($("<a>").attr({href: "javascript:void(0);", "data-file": file, "data-target": "#modal-edit", "data-toggle": "modal", "title": title}).addClass("btn-edit-file").html(img));
            if (checked == true) {
                div.removeClass("active");
                div.addClass("active");
            }
            $(".content-files").eq(index).append(div);
        }

        my_list = my_new_list;
    }

    $("#modal-edit").on("click", "#btn-remove-file", function () {
        var file = $("#modal-edit #field-file").val();
        var field = $("#modal-edit #field-edit").val();
        $(".btn-gallery[data-field="+field+"]").gallery().delete_file(file);
        $(".btn-gallery[data-field="+field+"]").gallery().save_list();
    });
});