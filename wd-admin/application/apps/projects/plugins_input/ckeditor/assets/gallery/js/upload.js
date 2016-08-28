$(function () {
    // Insere o botão para inserir arquivos no ckeditor
    var exts = ['jpg', 'jpeg', 'png', 'gif'];
    $(".form-group").each(function () {
        var index = $(".form-group").index(this);
        var field = $(".label-field").eq(index).data("field") + "_field";
        if ($(".input-field").eq(index).hasClass("ckeditor")) {

            $("#btn-save-change").addClass("hide");
            var btn_index = $(".content-field .btn-gallery-ckeditor").length;
            $(".content-field").eq(index).prepend(
                    $("<a>").attr({"href": "javascript:void(0);"})
                    .addClass("btn-gallery-ckeditor")
                    .html('<i class="fa fa-file-image-o"></i> Inserir arquivos')
                    .addClass('pull-right btn btn-default btn-sm btn-upload')
                    );
            $(".content-field .btn-gallery-ckeditor:eq(" + btn_index + ")").gallery({
                limit_select: 1,
                files_selecteds: [],
                reset_selecteds: true,
                complete: function (file) {
                    var file_split = file.split(".");
                    var ext = file_split[file_split.length - 1];
                    if ($.inArray(ext, exts) != '-1') {
                        var html = '<img src="' + base_url + 'wd-content/upload/' + file + '">';
                    } else {
                        var html = '<a href="' + base_url + 'wd-content/upload/' + file + '">Arquivo</a>';
                    }
                    CKEDITOR.instances[field].insertHtml(html);
                }
            });


        }
    });
});
