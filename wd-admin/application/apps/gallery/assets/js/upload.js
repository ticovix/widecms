$(function () {
    var container_modal = $(".main_container > .right_col");
    var async_default = $.ajaxSetup().async;
    var csrf = $.ajaxSetup().data.csrf_test_name;
    async(false);
    var permissions = $.getJSON(url + "apps/gallery/list-permissions").responseJSON;
    var lang = $.getJSON(url + "apps/gallery/list-lang").responseJSON;
    async(async_default);
    var hash = 0;

    function async(bool) {
        $.ajaxSetup({
            async: bool
        });
    }

    // Carrega os plugins e o modal apenas uma vez para o template
    var template_modal = new EJS({url: url + "application/apps/gallery/assets/ejs/gallery_modal/modal-upload.ejs"}).render({
        perm: permissions,
        lang: lang,
        url: url,
        csrf_test_name: csrf
    });
    // Inclui modal no corpo do cms
    container_modal.append(template_modal);
    // Carrega Dropzone
    if (typeof Dropzone == 'function') {
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#dropzone_gallery");
        // Atualizar lista de arquivos ao fazer upload
        myDropzone.on("complete", function () {
            list_files({});
        });
    }
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

    jQuery.fn.gallery = function (settings) {
        var btn_upload = $(this);
        var modal = $(".gallery-upload-modal");
        var saved_list = [];

        var config = {
            limit_select: null, // multiple default
            complete: null, // callback function
            files_selecteds: [], // String or Object
            reset_selecteds: false,
            /*Config upload*/
            extensions_allowed: null,
            image_resize: null,
            image_y: null,
            image_x: null,
            image_ratio: null,
            image_ratio_x: null,
            image_ratio_y: null,
            image_ratio_crop: null,
            image_ratio_fill: null,
            image_background_color: null,
            image_convert: null,
            image_text: null,
            image_text_color: null,
            image_text_background: null,
            image_text_opacity: null,
            image_text_background_opacity: null,
            image_text_padding: null,
            image_text_position: null,
            image_text_direction: null,
            image_text_x: null,
            image_text_y: null,
            image_thumbnails: null
        }
        if (settings) {
            $.extend(config, settings);
        }
        config.hash = hash++;
        saved_list = JSON.parse(JSON.stringify(config.files_selecteds));
        var modal_current = "#gallery-upload-" + config.hash;

        container_modal.on("click", modal_current + " #btn-save-change", function () {
            saved_list = JSON.parse(JSON.stringify(config.files_selecteds));
            config.files_selecteds = [];
            // Callback
            if (typeof config.complete == 'function') {
                config.complete(saved_list);
            }
            $(".gallery-upload-modal").modal('hide');
        });

        container_modal.on("click", modal_current + " #list-files .btn-add", function () {
            var index = $(".btn-add").index(this);
            var file = $(".file").eq(index).data('file');
            if (config.limit_select === 1) {
                config.files_selecteds = new Array();
                config.files_selecteds[0] = file;
                saved_list = JSON.parse(JSON.stringify(config.files_selecteds));
                // Callback
                if(config.reset_selecteds){
                    reset_selecteds();
                }
                if (typeof config.complete == 'function') {
                    config.complete(file);
                }
                $(".gallery-upload-modal").modal('hide');
            } else {
                if ($(".image-file").eq(index).hasClass("active")) {
                    delete_file(file);
                    $(".image-file").eq(index).removeClass("active");
                } else {
                    add_file(file);
                    $(".image-file").eq(index).addClass("active");
                }
            }
        });

        btn_upload.click(function () {
            // Trata lista de arquivos selecionados
            treat_list_selected();
            //Exibe modal
            $(".gallery-upload-modal").modal("show");
            $(".gallery-upload-modal").attr("id", modal_current.replace("#", ""));
            if (permissions.app !== true || config.limit_select === 1) {
                $(".gallery-upload-modal .modal-footer").addClass("hide");
            } else {
                $(".gallery-upload-modal .modal-footer").removeClass("hide");
            }

            // Configura upload
            $(modal_current + " #upload_config").val(JSON.stringify({
                extensions_allowed: config.extensions_allowed,
                image_resize: config.image_resize,
                image_y: config.image_y,
                image_x: config.image_x,
                image_ratio: config.image_ratio,
                image_ratio_x: config.image_ratio_x,
                image_ratio_y: config.image_ratio_y,
                image_ratio_crop: config.image_ratio_crop,
                image_ratio_fill: config.image_ratio_fill,
                image_background_color: config.image_background_color,
                image_convert: config.image_convert,
                image_text: config.image_text,
                image_text_color: config.image_text_color,
                image_text_background: config.image_text_background,
                image_text_opacity: config.image_text_opacity,
                image_text_background_opacity: config.image_text_background_opacity,
                image_text_padding: config.image_text_padding,
                image_text_position: config.image_text_position,
                image_text_direction: config.image_text_direction,
                image_text_x: config.image_text_x,
                image_text_y: config.image_text_y,
                image_thumbnails: config.image_thumbnails,
            }));

            // Lista arquivos
            list_files({});
        });

        /*
         * Pesquisa
         */
        $(document).on("submit", "#search-files", function (e) {
            var keyword = $("#search-field").val();
            list_files({
                url: url + 'apps/gallery/files-list?search=' + keyword
            });
            e.preventDefault();
            return false;
        });


        /*
         * Paginação
         */
        container_modal.on("click", "#list-files .btn-page", function (e) {
            list_files({
                url: $(this).attr("href")
            });
            e.preventDefault();
            return false;
        });

        function treat_list_selected() {
            config.files_selecteds = JSON.parse(JSON.stringify(saved_list));
            var files = config.files_selecteds;
            if (files !== null && typeof files != 'object') {
                files = new Array();
                files[0] = files;
            }
        }

        function add_file(file) {
            var files = config.files_selecteds;
            if (files == undefined || typeof files != "object") {
                files = new Object();
            }
            var file_current = Object.keys(files).length;
            files[file_current] = file;
            //config.files_selecteds = files;
        }

        function delete_file(file) {
            var files = config.files_selecteds;
            if (files == undefined || typeof files != "object") {
                files = new Object();
            }
            var new_list = new Array();
            var total = Object.keys(files).length;
            var x = 0;
            if (typeof files[0] === "undefined") {
                files = new Array(files);
                total = 1;
            }
            for (var i = 0; i < total; i++) {
                var file_current = files[i];
                if (file_current != file) {
                    new_list[x] = file_current;
                    x++;
                }
            }
            config.files_selecteds = new_list;
        }
        /*
         * Função para listar arquivos no modal
         */
        function list_files(param) {
            var content = $(".gallery-upload-modal #files-content");
            if (param.url === '' || param.url === undefined) {
                param.url = url + "apps/gallery/files-list"
            }
            content.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>');
            var config_upload = $(".gallery-upload-modal #upload_config").val();
            $.ajax({
                url: param.url,
                dataType: "json",
                type: "POST",
                data: {limit: 12, config: config_upload},
                success: function (data) {
                    var file_list = "list-files.ejs";
                    var template = new EJS({url: url + "application/apps/gallery/assets/ejs/gallery_modal/" + file_list}).render({
                        data: data,
                        url: url,
                        lang: lang,
                        config: config,
                        saved_list: saved_list
                    });
                    content.html(template);
                }
            });
        }
        
        function reset_selecteds() {
            config.files_selecteds = [];
            saved_list = [];
        }
    };
});