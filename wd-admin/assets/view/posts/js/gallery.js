Dropzone.autoDiscover = false;

$(function () {
    /*
     * Dropzone
     */
    var myDropzone = new Dropzone("#dropzone_gallery");
    myDropzone.on("complete", function (file) {
        files_list({});
    });
    /*
     * Gallery
     */
    $(".fancybox").attr('rel', 'gallery').fancybox({
        beforeShow: function () {
            /* Disable right click */
            $.fancybox.wrap.bind("contextmenu", function (e) {
                return false;
            });
        }
    });
    $(".fancybox").attr('rel', 'gallery').fancybox({
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
    var field, index_field_upload;
    var modal = $("#gallery");
    var content_files = $("#files-list");
    var content_files_added = $("#files-list-added");
    var list = new Object();
    /*
     * By clicking the input file, open a modal
     */
    $(".btn-gallery").click(function () {
        index_field_upload = $(".btn-gallery").index(this);
        field = $(this).data("field");
        var field_list = $("#" + field + "-field").val();
        if (field_list.indexOf('{') != '-1') {
            list = $.parseJSON(field_list);
        } else {
            list = new Object();
        }
        files_list({});
        files_list_added();
    });
    /*
     * Display options to hover over the file
     */
    modal.on('mouseenter', '.file', function (event) {
        $(this).children(".image-file").children(".options-file").removeClass("hide");
    }).on('mouseleave', '.file', function (event) {
        $(this).children(".image-file").children(".options-file").addClass("hide");
    });
    /*
     * Function to list files
     */
    function files_list(param) {
        var URL = param.url;
        var content = $("#files-list");
        if (URL == '' || URL == undefined) {
            URL = url + "app/gallery/files-list";
        }
        $.ajax({
            url: URL,
            dataType: "json",
            type: "POST",
            data: {limit: 12},
            success: function (data) {
                var template = new EJS({url: url + "assets/view/project/ejs/list-files.ejs"}).render({data: data, url: url});
                content.html(template);
            }
        });
    }
    /*
     * Function to list files added
     */
    function files_list_added() {
        var content = content_files_added;
        var template = new EJS({url: url + "assets/view/project/ejs/list-files-added.ejs"}).render({files: list, url: url});
        content.html(template);
    }
    /*
     * Pagination
     */
    content_files.on("click", ".btn-page", function (e) {
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
            url: url + 'app/gallery/files-list?search=' + keyword
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
            url: url + "app/gallery/file",
            dataType: "json",
            type: "POST",
            data: {file: file},
            success: function (data) {
                var template = new EJS({url: url + "assets/view/app/gallery/ejs/file-view.ejs"}).render({data: data, url: url});
                content.html(template);
            }
        });
    });

    /*
     * Add file to post
     */
    content_files.on("click", ".btn-add-file", function () {
        var is_multiple = ($("#" + field + "-field").attr("multiple") !== undefined);
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

    content_files_added.on("click", ".btn-delete-file", function () {
        var index = $(".btn-delete-file").index(this);
        $("#files-list-added .file").eq(index).remove();
        delete_file();
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
        list[file_current] = {file: file, checked: false, title: ''};
        files_list_added();
    }
    /*
     * Function to delete file
     */
    function delete_file(file) {
        if (list != undefined) {
            var new_list = new Object();
            var total = Object.keys(list).length;
            for (var i = 0; i < total - 1; i++) {
                var file_ = list[i].file;
                var checked = list[i].checked;
                var title = list[i].title;
                if (file_ != file) {
                    new_list[i] = {file: file_, checked: checked, title: title};
                }
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
                var img = $("<img>").addClass("img-responsive").attr("src",url+"app/gallery/image/thumb/"+file);
                $(".content-files").eq(index_field_upload).append($("<div>").addClass("files-list thumbnail").html(img));
            }
            var json = JSON.stringify(list);
            if (json === '{}') {
                json = '';
            }
            $("#" + field + "-field").val(json);


        }
    });

});