$(function () {
    /*
     * Dropzone
     */
    if (typeof myDropzone == "function") {
        myDropzone.on("complete", function (file) {
            if ($("#files-content").data('gallery') == "posts") {
                files_list({});
            }
        });
    }
    var field, index_field_upload;
    var modal = $("#gallery");
    var content_files = $("#files-list");
    var content_files_added = '';
    var list = new Object();
    /*
     * By clicking the input file, open a modal
     */
    $("#data-project").on("click", ".btn-gallery", function () {
        index_field_upload = $(".btn-gallery").index(this);
        field = $(this).data("field");
        var field_list = $("#" + field + "_field").val();
        if (field_list.indexOf('{') != '-1') {
            list = $.parseJSON(field_list);
        } else {
            list = new Object();
        }
        if ($("#files-content").data('gallery') != "posts") {
            var template = new EJS({url: app_assets + "posts/ejs/base-files.ejs"}).render();
            $("#files-content").data("gallery", "posts").html(template);
            $("#btn-save-change").removeClass("hide");
            content_files_added = $("#files-list-added");
        }

        files_list({});
        files_list_added();
    });
    /*
     * Function to list files
     */
    function files_list(param) {
        var URL = param.url;
        var content = $("#files-list");
        if (URL == '' || URL == undefined) {
            URL = url + "apps/gallery/files-list";
        }
        content.html("Carregando..");
        $.ajax({
            url: URL,
            dataType: "json",
            type: "POST",
            data: {limit: 12},
            success: function (data) {
                var template = new EJS({url: app_assets + "posts/ejs/list-files.ejs"}).render({data: data, url: url, app_path: app_path});
                content.html(template);
            }
        });
    }
    /*
     * Function to list files added
     */
    function files_list_added() {
        var content = content_files_added;
        var template = new EJS({url: app_assets + "posts/ejs/list-files-added.ejs"}).render({files: list, url: url, app_path: app_path});
        content.html(template);
    }

    /*
     * Pagination
     */
    modal.on("click", ".btn-page", function (e) {
        files_list({
            url: $(this).attr("href")
        });
        e.preventDefault();
        return false;
    });
    /*
     * Search file
     */
    $("#search-files").submit(function (e) {
        var keyword = $("#search-field").val();
        files_list({
            url: url + 'apps/gallery/files-list?search=' + keyword
        });
        e.preventDefault();
        return false;
    });
    /*
     * View file in modal
     */
    modal.on("click", ".btn-view-file", function () {
        var index = $(".btn-view-file").index(this);
        var file = $(".file").eq(index).data("file");
        var content = $("#details .modal-content");
        content.html('<div class="modal-body">Aguarde..</div>');
        $.ajax({
            url: url + "apps/gallery/file",
            dataType: "json",
            type: "POST",
            data: {file: file},
            success: function (data) {
                var template = new EJS({url: url + "application/apps/gallery/assets/ejs/gallery/file-view.ejs"}).render({data: data, url: url, app_path: app_path});
                content.html(template);
            }
        });
    });

    modal.on("click", ".btn-checked-file", function () {
        var index = $(".btn-checked-file").index(this);
        $("#files-list-added .image-file").removeClass("active");
        $("#files-list-added .image-file").eq(index).addClass("active");

        checked_file(index);
    });
    /*
     * Add file to post
     */
    modal.on("click", ".btn-add-file", function () {
        var is_multiple = ($("#" + field + "_field").attr("multiple") !== undefined);
        var index = $(".btn-add-file").index(this);
        var file = $(".file").eq(index).data('file');
        if (is_multiple || list !== undefined && Object.keys(list).length < 1) {
            add_file(file);
        } else {
            $("#msg-add-file").html('<div class="alert alert-warning">Só é possível inserir um arquivo.</div>');
        }
    });

    /*
     * Delete file to list
     */

    modal.on("click", ".btn-delete-file", function () {
        var index = $(".btn-delete-file").index(this);
        delete_file(index);
        files_list_added();
    });

    /*
     * Function to add file in input
     */

    function add_file(file) {
        if (list == undefined || typeof list != "object") {
            list = new Object();
        }
        var file_current = Object.keys(list).length;
        var checked = true;
        for (var i = 0; i < file_current; i++) {
            var checked_ = list[i].checked;
            if (checked_ == true) {
                checked = false;
            }
        }
        list[file_current] = {file: file, checked: checked, title: ''};
        files_list_added();
    }
    /*
     * Function to delete file
     */
    function delete_file(index) {
        if (list != undefined) {
            var new_list = new Object();
            var total = Object.keys(list).length;
            var x = 0;
            for (var i = 0; i < total; i++) {
                var file = list[i].file;
                var checked = list[i].checked;
                var title = list[i].title;
                if (i != index) {
                    new_list[x] = {file: file, checked: checked, title: title};
                    x++;
                }
            }
            list = new_list;
        }
    }

    function checked_file(index) {
        if (list != undefined) {
            var new_list = new Object();
            var total = Object.keys(list).length;
            for (var i = 0; i < total; i++) {
                var file = list[i].file;
                var checked = false;
                var title = list[i].title;
                if (i == index) {
                    checked = true;
                }
                new_list[i] = {file: file, checked: checked, title: title};
            }
            list = new_list;
        }
    }

    function edit_file(param) {
        var index = param.index;
        var title = param.title;
        if (list != undefined) {
            var new_list = new Object();
            var total = Object.keys(list).length;
            for (var i = 0; i < total; i++) {
                var file = list[i].file;
                var checked = list[i].checked;
                var title_ = list[i].title;
                if (i == index) {
                    title_ = title;
                }
                new_list[i] = {file: file, checked: checked, title: title_};
            }
            list = new_list;

        }
    }

    /*
     * Save change
     */
    $("#btn-save-change").click(function () {
        if (list != undefined) {
            var total = Object.keys(list).length;
            $(".content-files").eq(index_field_upload).html("");
            for (var i = 0; i < total; i++) {
                var file = list[i].file;
                var checked = list[i].checked;
                var img = $("<img>").addClass("img-responsive").attr("src", url + "apps/gallery/image/thumb/" + file);
                var div = $("<div>").addClass("files-list thumbnail").html($("<a>").attr("href", base_url + "wd-content/upload/" + file).addClass("fancybox").html(img));
                if (checked == true) {
                    div.removeClass("active");
                    div.addClass("active");
                }
                $(".content-files").eq(index_field_upload).append(div);
            }
            var json = JSON.stringify(list);
            if (json === '{}') {
                json = '';
            }
            $("#" + field + "_field").val(json);


        }
    });

    /*
     * Edit file
     */

    modal.on("click", ".btn-edit-file", function () {
        var index = $(".btn-edit-file").index(this);
        var file = list[index];
        var content = $("#modal-edit .modal-content");

        content.attr("data-index", index);
        var template = new EJS({url: url + "application/apps/gallery/assets/ejs/posts/file-edit.ejs"}).render({file: file, url: url, app_path: app_path});
        content.html(template);
    });

    /*
     * Save Edit
     */
    $("#modal-edit .modal-content").delegate("#btn-save-edit", "click", function () {
        var title = $("#field-title").val();
        var index = $("#modal-edit .modal-content").attr("data-index");
        edit_file({
            title: title,
            index: index
        });
        var msg = $("#message-edit");
        msg.html('<div class="alert alert-success">Atualizado com sucesso!</div>');
    });

});