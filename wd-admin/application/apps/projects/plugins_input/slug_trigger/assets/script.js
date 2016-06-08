$(function () {
    var slug = function (str, transform_space) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
        var to = "aaaaaeeeeeiiiiooooouuuunc------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, transform_space)
                .replace(/-+/g, transform_space);

        return str;
    };

    $(".slug-trigger").keyup(function () {
        var val = $(this).val();
        $(".slug").val(slug(val, '-'));
    });

});
