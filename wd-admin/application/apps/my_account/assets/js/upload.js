$(function () {
    var image_current = $("#upload-image").val();
    $(".btn-upload").gallery({
        files_selecteds: image_current,
        limit_select: 1,
        extensions_allowed: "jpg,jpeg,png,gif",
        image_resize: true,
        image_x: 110,
        image_y: 110,
        image_ratio_crop: true,
        complete: function(files){
            var file = files[0];
            var exts = ['jpg', 'jpeg', 'png', 'gif'];
            var file_split = file.split(".");
            var ext = file_split[file_split.length - 1];
            if ($.inArray(ext, exts) != '-1') {
                $("#upload-image").val(file);
                $("#img-profile").attr('src', base_url + 'wd-content/upload/' + file);
            }
        }
    });
});
