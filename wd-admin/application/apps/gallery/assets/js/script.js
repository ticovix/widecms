if (typeof Dropzone == "function") {
    Dropzone.autoDiscover = false;
}

$(function () {

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
    /*
     * Function to list files
     */
    function files_list(param) {

        var URL = param.url;
        var content = $("#files-list");
        if (URL == '' || URL == undefined) {
            URL = app_path + "files-list";
        }
        content.html("<p>" + LANG.loading + "</p>");
        $.ajax({
            url: URL,
            dataType: "json",
            type: "POST",
            data: {limit: 12},
            success: function (data) {
                var template = new EJS({url: app_assets + "ejs/gallery/list-files.ejs"}).render({data: data, url: url, app_path: app_path, edit: edit_file, remove: remove_file});
                content.html(template);
            }
        });
    }
    /*
     * Init method files_list()
     */
    files_list({});

    /*
     * Delete file
     */
    $("#files-list").on("click", ".btn-delete-file", function () {
        var index = $(".btn-delete-file").index(this);
        var file = $(".file").eq(index).data("file");
        var index = $(".btn-delete-file").index(this);

        if (confirm(LANG._ask_remove_file + " " + file + " ?")) {
            $.ajax({
                url: app_path + "delete",
                type: 'POST',
                data: {file: file},
                success: function () {
                    $(".file").eq(index).remove();
                }
            });
        }
    });

    /*
     * View file
     */

    $("#files-list").on("click", ".btn-view-file", function () {
        var index = $(".btn-view-file").index(this);
        var file = $(".file").eq(index).data("file");
        var content = $("#details .modal-content");
        content.html('<div class="modal-body">' + LANG.loading + '</div>');
        $.ajax({
            url: app_path + "file",
            dataType: "json",
            type: "POST",
            data: {file: file},
            success: function (data) {
                var template = new EJS({url: app_assets + "ejs/gallery/file-view.ejs"}).render({data: data, url: url, app_path: app_path});
                content.html(template);
                load_fancybox();
            }
        });
    });
    /*
     * Edit file
     */

    $("#files-list").on("click", ".btn-edit-file", function () {
        var index = $(".btn-edit-file").index(this);
        var file = $(".file").eq(index).data("file");
        var content = $("#edit .modal-content");
        content.html('<div class="modal-body">' + LANG.loading + '</div>');
        $.ajax({
            url: app_path + "file",
            dataType: "json",
            type: "POST",
            data: {file: file},
            success: function (data) {
                var template = new EJS({url: app_assets + "ejs/gallery/file-edit.ejs"}).render({data: data, url: url, app_path: app_path});
                content.html(template);
            }
        });
    });
    /*
     * Save Edit
     */
    $("#edit .modal-content").delegate("#btn-save-edit", "click", function () {
        var name = $("#field-name").val();
        var new_file = $("#field-file").val();
        var file = $(this).data("file");
        var msg = $("#message-edit");
        $.ajax({
            url: app_path + "edit-file",
            dataType: "json",
            type: "POST",
            data: {file: file, name: name, new_file: new_file},
            success: function (data) {
                if (data.error) {
                    msg.html('<div class="alert alert-danger">' + data.message + '</div>');
                } else {
                    msg.html('<div class="alert alert-success">' + data.message + '</div>');
                    setTimeout(function () {
                        document.location.reload();
                    }, 1000);
                }
            }
        });
    });

    /*
     * Search file
     */
    $("#search-files").submit(function (e) {
        var keyword = $("#search-field").val();
        files_list({
            url: app_path + 'files-list?search=' + keyword
        });
        e.preventDefault();
        return false;
    });

    /*
     * Pagination
     */
    $("#files-list").on("click", ".btn-page", function (e) {
        files_list({
            url: $(this).attr("href")
        });
        e.preventDefault();
        return false;
    });

    /*
     * Dropzone
     */
    if (typeof Dropzone == "function") {
        var myDropzone = new Dropzone("#dropzone_gallery");
        myDropzone.on("complete", function (file) {
            files_list({});
        });
    }

});