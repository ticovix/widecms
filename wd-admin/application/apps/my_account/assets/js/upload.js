$(function () {
    var field;
    var modal = $("#gallery");
    var content_files = $("#files-list");
    /*
     * Dropzone
     */

    if (typeof myDropzone == 'function') {
        myDropzone.on("complete", function (file) {
            if ($("#files-content").data('gallery') == "ckeditor") {
                files_list();
            }
        });
    }
    /*
     * Function to list files
     */
    function files_list() {
        var content = $("#files-list");
        content.html("Carregando..");
        $.ajax({
            url: url + "apps/gallery/files-list",
            dataType: "json",
            type: "POST",
            data: {limit: 12},
            success: function (data) {
                var template = new EJS({url: app_assets + "ejs/list-files.ejs"}).render({data: data, url: url, app_path: app_path});
                content.html(template);
            }
        });
    }

    $(".btn-upload").click(function () {
        var text = $(this).html();
        field = $(this).data("field") + "_field";
        if ($("#files-content").data('gallery') != "ckeditor") {
            var template = new EJS({url: app_assets + "ejs/base-files.ejs"}).render();
            $("#files-content").data("gallery", "ckeditor").html(template);
            files_list();
        }
    });

    modal.on("click", ".btn-add", function () {
        if (field != null || field != undefined) {
            var index = $(".btn-add").index(this);
            var file = $(".file").eq(index).data('file');
            var exts = ['jpg', 'jpeg', 'png', 'gif'];
            var file_split = file.split(".");
            var ext = file_split[file_split.length - 1];
            if ($.inArray(ext, exts) != '-1') {
                $("#upload-image").val(file);
                $("#img-profile").attr('src', base_url + 'wd-content/upload/' + file);
            }
        }
        modal.modal('toggle');
    });
});
