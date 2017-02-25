$(function () {

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
        if (files.indexOf('[') != '-1') {
            files = $.parseJSON(files);
            var total = files.length;
            for (var i = 0; i < total; i++) {
                files_selected[i] = files[i];
            }
        } else {
            files_selected = new Array();
        }

        return files_selected;
    }

    function load_fancybox() {
        var fancybox = ".fancybox";
        $(fancybox).attr('rel', 'gallery').fancybox({
            nextEffect: 'fade',
            prevEffect: 'fade',
            openEffect: 'elastic',
            closeEffect: 'elastic',
            autoCenter: true,
            padding: 0,
            margin: 20,
            arrows: true,
            mouseWheel: true,
            fitToView: true,
        });

        $(fancybox).attr('rel', 'gallery').fancybox({
            beforeShow: function () {
                /* Disable right click */
                $.fancybox.wrap.bind("contextmenu", function (e) {
                    return false;
                });
            }
        });
    }

    load_fancybox();

    String.prototype.replaceAll = function (search, replacement) {
        var target = this;

        return target.replace(new RegExp(search, 'g'), replacement);
    };

    $(".content-files").sortable({
        items: ".files-list",
        appendTo: "parent",
        revert: true,
        cursor: "move",
        cursorAt: {top: -5, left: -5},
        stop: function (event) {
            var my_list = new Array();
            var count = 0;
            $(this).find(".files-list").each(function () {
                var btn_file = $(this).find(".btn-edit-file");
                var file = btn_file.data("file");
                my_list[count] = file;
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

        $(".btn-gallery[data-field=" + field + "]").gallery($.extend(params, config_upload));
    });

    function list_files(btn, files) {
        var index = $(".btn-gallery").index(btn);
        var field = btn.data("field");

        if (typeof files === 'string') {
            var object_files = new Array();
            object_files[0] = files;
            files = object_files;
        }

        var total = files.length;
        var my_new_list = new Array();
        for (var i = 0; i < total; i++) {
            var file = files[i];
            my_new_list[i] = file;
        }

        var json = JSON.stringify(my_new_list);
        if (json === '[]') {
            json = '';
        }

        $("#" + field + "_field").val(json);

        var total = my_new_list.length;
        $(".content-files").eq(index).html("");
        for (var i = 0; i < total; i++) {
            var file = my_new_list[i];
            var img = $("<img>").addClass("img-responsive").attr("src", url + "apps/gallery/image/thumb/" + file);
            var btn_remove = $("<span>").addClass("fa fa-remove");
            var div = $("<div>").addClass("files-list image-file")
                    .append(
                            $("<a>").attr({href: "javascript:void(0);", "data-file": file}).addClass("btn-edit-file").html(img),
                            $("<a>").attr({href: "javascript:void(0);", "data-file": file}).addClass("btn-remove-file").html(btn_remove)
                        );

            $(".content-files").eq(index).append(div);
        }
    }

    $(".content-files").on("click", ".btn-remove-file", function () {
        var index = $(this).parents(".content-files").index();
        var file = $(this).attr("data-file");
        var field = $(".btn-gallery").eq(index).attr("data-field");
        $(".btn-gallery[data-field=" + field + "]").gallery().delete_file(file);
        $(".btn-gallery[data-field=" + field + "]").gallery().save_list();
    });
});