$(function () {
    var hasInit = false;
    var hash = 1;
    var async = function (bool) {
        $.ajaxSetup({
            async: bool
        });
    }

    var gallery = function (el, settings) {
        var csrf = $.ajaxSetup().data.csrf_test_name;
        var async_default = $.ajaxSetup().async;
        async(false);
        var permissions = $.getJSON(url + "apps/gallery/list-permissions").responseJSON;
        var lang = $.getJSON(url + "apps/gallery/list-lang").responseJSON;
        async(async_default);
        var self = this;
        var config = {
            limit_select: null, // multiple default
            files_selecteds: [], // String or Object
            saved_list: [],
            complete: function (files) {}, // callback function
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
        };
        var selector = {
            modal: ".gallery-upload-modal",
            content_modal: ".gallery-upload-modal #files-content",
            current_modal: null,
            container_modal: ".main_container > .right_col",
            btn_save: ".gallery-upload-modal #btn-save-change",
            btn_add: ".gallery-upload-modal #list-files .btn-add",
            modal_footer: ".modal-footer",
            upload_config: "#upload_config",
            image_file: ".image-file",
            current_file: ".file",
            fancybox: ".fancybox",
            input_search: ".gallery-upload-modal #search-files",
            pagination: ".gallery-upload-modal #list-files .btn-page"
        };
        config = $.extend(config, settings);
        config.saved_list = config.files_selecteds;
        selector.current_modal = "#gallery-upload-" + hash++;
        var fancybox = selector.fancybox;
        var current_modal = selector.current_modal;
        var btn_save = current_modal + selector.btn_save;
        var btn_add = current_modal + selector.btn_add;
        var input_search = current_modal + selector.input_search;
        var pagination = current_modal + selector.pagination;
        var container_modal = selector.container_modal;

        var load_dependencies = function () {
            // Carrega os plugins e o modal apenas uma vez para o template
            var template_modal = new EJS({url: url + "application/apps/gallery/assets/ejs/gallery_modal/modal-upload.ejs"}).render({
                perm: permissions,
                lang: lang,
                url: url,
                csrf_test_name: csrf
            });
            $(container_modal).append(template_modal);
            // Carrega Dropzone
            if (typeof Dropzone == 'function') {
                Dropzone.autoDiscover = false;
                var myDropzone = new Dropzone("#dropzone_gallery");
                // Atualizar lista de arquivos ao fazer upload
                myDropzone.on("complete", function () {
                    list_files({});
                });
            }
        }

        var load_fancybox = function () {
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

        var treat_list_selected = function (el) {
            config.files_selecteds = JSON.parse(JSON.stringify(config.saved_list));
            var files = config.files_selecteds;
            if (files !== null && typeof files != 'object') {
                files = new Array();
                files[0] = files;
            }
        }

        var list_files = function (param) {
            var content = $(selector.content_modal);
            if (param.url === '' || param.url === undefined) {
                param.url = url + "apps/gallery/files-list"
            }

            content.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>');
            var config_upload = $(selector.upload_config).val();
            $.ajax({
                url: param.url,
                dataType: "json",
                type: "POST",
                data: {limit: 12, config: config_upload},
                success: function (data) {
                    var template = new EJS({url: url + "application/apps/gallery/assets/ejs/gallery_modal/list-files.ejs"}).render({
                        data: data,
                        url: url,
                        lang: lang,
                        config: config
                    });
                    content.html(template);
                    load_fancybox();
                }
            });
        }

        // public method
        this.save_list = function () {
            var self = this;
            config.saved_list = JSON.parse(JSON.stringify(config.files_selecteds));
            // Callback
            config.complete(config.saved_list);
        }

        // public method
        this.reset_selecteds = function () {
            config.files_selecteds = [];
            config.saved_list = [];
        }

        // public method
        this.delete_file = function (file) {
            var self = this;
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

        // public method
        this.add_file = function (file) {
            var files = config.files_selecteds;
            if (files == undefined || typeof files != "object") {
                files = new Object();
            }

            var file_current = Object.keys(files).length;
            files[file_current] = file;

            config.files_selecteds = files;
        }

        if (!hasInit) {
            load_dependencies();
            hasInit = true;
        }

        el.click(function (e) {
            var modal = selector.modal;
            var modal_footer = selector.modal_footer;
            var upload_config = selector.upload_config;
            var limit_select = config.limit_select;

            treat_list_selected(el);

            $(modal).modal("show");
            $(modal).attr("id", selector.current_modal.replace("#", ""));
            console.log(limit_select);
            if (permissions.app !== true || limit_select === 1) {
                $(modal_footer).addClass("hide");
            } else {
                $(modal_footer).removeClass("hide");
            }

            $(upload_config).val(JSON.stringify({
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

            list_files({});
        });

        $(container_modal).on("click", btn_save, function () {
            var modal = selector.modal;
            self.save_list();
            $(modal).modal('hide');
        });
        $(container_modal).on("click", btn_add, function () {
            var modal = selector.modal;
            var btn_add = selector.btn_add;
            var index = $(btn_add).index(this);
            var image_file = selector.image_file;
            var limit_select = config.limit_select;
            var file = $(selector.current_file).eq(index).data('file');
            if (limit_select === 1) {
                self.reset_selecteds();
                self.add_file(file);
                self.save_list();

                $(modal).modal('hide');
            } else {
                if ($(image_file).eq(index).hasClass("active")) {
                    self.delete_file(file);
                    $(image_file).eq(index).removeClass("active");
                } else {
                    self.add_file(file);
                    $(image_file).eq(index).addClass("active");
                }
            }
        });

        /*
         * Search
         */
        $(container_modal).on("submit", input_search, function (e) {
            var keyword = $("#search-field").val();
            list_files({
                url: url + 'apps/gallery/files-list?search=' + keyword
            });

            e.preventDefault();
            return false;
        });

        /*
         * Pagination
         */
        $(container_modal).on("click", pagination, function (e) {
            list_files({
                url: $(this).attr("href")
            });

            e.preventDefault();
            return false;
        });
    }

    $.fn.gallery = function (settings) {
        var el = $(this);
        if (el.data('gallery')) {
            return el.data('gallery');
        }

        var plugin = new gallery(el, settings);
        el.data('gallery', plugin);
        return gallery;
    }
});