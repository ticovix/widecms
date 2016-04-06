Dropzone.autoDiscover = false;

$(function () {
    /*
     * Dropzone
     */
    var myDropzone = new Dropzone("#dropzone_gallery");
    myDropzone.on("complete", function (file) {
        files_list({});
        //myDropzone.removeFile(file);
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
    /*
     * Function to list files
     */
    function files_list(param) {
        var URL = param.url;
        var content = $("#files-list");
        if (URL == '' || URL == undefined) {
            URL = app_path + "files-list";
        }
        $.ajax({
            url: URL,
            dataType: "json",
            type: "POST",
            data: {limit: 12},
            success: function (data) {
                var template = new EJS({url: app_assets + "ejs/list-files.ejs"}).render({data: data, url: url, app_path: app_path});
                content.html(template);
            }
        });
    }
    /*
     * Init method files_list()
     */
    files_list({});
    /*
     * File hover
     */
    $("#files-list").on('mouseenter', '.file', function (event) {
        $(this).children(".image-file").children(".options-file").removeClass("hide");
    }).on('mouseleave', '.file', function (event) {
        $(this).children(".image-file").children(".options-file").addClass("hide");
    });

    /*
     * Delete file
     */
    $("#files-list").on("click", ".btn-delete-file", function () {
        var index = $(".btn-delete-file").index(this);
        var file = $(".file").eq(index).data("file");
        var index = $(".btn-delete-file").index(this);

        if (confirm("Deseja realmente remover o arquivo " + file + " ?")) {
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

    $("#files-list").on("click", ".btn-view-file",function () {
        var index = $(".btn-view-file").index(this);
        var file = $(".file").eq(index).data("file");
        var content = $("#details .modal-content");
        content.html('<div class="modal-body">Aguarde..</div>');
        $.ajax({
            url: app_path + "file",
            dataType: "json",
            type: "POST",
            data: {file: file},
            success: function (data) {
                var template = new EJS({url: app_assets + "ejs/file-view.ejs"}).render({data: data, url: url, app_path: app_path});
                content.html(template);
            }
        });
    });
    /*
     * Edit file
     */

    $("#files-list").on("click", ".btn-edit-file",function () {
        var index = $(".btn-edit-file").index(this);
        var file = $(".file").eq(index).data("file");
        var content = $("#edit .modal-content");
        content.html('<div class="modal-body">Aguarde..</div>');
        $.ajax({
            url: app_path + "file",
            dataType: "json",
            type: "POST",
            data: {file: file},
            success: function (data) {
                var template = new EJS({url: app_assets + "ejs/file-edit.ejs"}).render({data: data, url: url, app_path: app_path});
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
                if(data.error){
                    msg.html('<div class="alert alert-danger">'+data.message+'</div>');
                }else{
                    msg.html('<div class="alert alert-success">'+data.message+'</div>');
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
    content_files.on("click", ".btn-page", function (e) {
        files_list({
            url: $(this).attr("href")
        });
        e.preventDefault();
        return false;
    });

});