$(function () {
    var field;
    var modal = $("#gallery");
    var content_files = $("#files-list");
    /*
     * Dropzone
     */

    myDropzone.on("complete", function (file) {
        if ($("#files-content").data('gallery') == "ckeditor") {
            ck_files_list();
        }
    });
    /*
     * Function to list files
     */
    function ck_files_list() {
        var content = $("#files-list");
        content.html("Carregando..");
        $.ajax({
            url: url + "apps/gallery/files-list",
            dataType: "json",
            type: "POST",
            data: {limit: 12},
            success: function (data) {
                var template = new EJS({url: url + "assets/apps/gallery/ejs/ckeditor/list-files.ejs"}).render({data: data, url: url, app_path: app_path});
                content.html(template);
            }
        });
    }

    // Insere o botão para inserir imagem
    $(".form-group").each(function () {
        var index = $(".form-group").index(this);
        var field = $(".label-field").eq(index).data("field");
        if ($(".input-field").eq(index).hasClass("ckeditor")) {
            $("#btn-save-change").addClass("hide");
            $(".form-group").eq(index).prepend(
                    $("<a>").attr({"href": "javascript:void(0);", "data-field": field})
                    .attr({"data-toggle": "modal", "data-target": "#gallery"})
                    .html('<i class="fa fa-file-image-o"></i> Inserir arquivos')
                    .addClass('pull-right btn btn-default btn-sm btn-upload')
                    );
        }
    });
    $("#data-project").on("click", ".btn-upload", function () {
        var text = $(this).html();
        field = $(this).data("field") + "_field";
        if ($("#files-content").data('gallery') != "ckeditor") {
            var template = new EJS({url: url + "assets/apps/gallery/ejs/ckeditor/base-files.ejs"}).render();
            $("#files-content").data("gallery", "ckeditor").html(template);
            ck_files_list();
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
                var html = '<img src="' + base_url + 'wd-content/upload/' + file + '">';
            } else {
                var html = '<a href="' + base_url + 'wd-content/upload/' + file + '">Arquivo</a>';
            }
            CKEDITOR.instances[field].insertHtml(html);
        }
        modal.modal('toggle');
    });
});
