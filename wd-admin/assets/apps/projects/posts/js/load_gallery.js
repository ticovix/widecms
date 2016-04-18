Dropzone.autoDiscover = false;
$(function () {
    myDropzone = new Dropzone("#dropzone_gallery");
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
});