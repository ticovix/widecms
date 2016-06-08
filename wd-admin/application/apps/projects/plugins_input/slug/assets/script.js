$(function () {
    var slug = function (str, transform_space) {
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
        var to = "aaaaaeeeeeiiiiooooouuuunc-----";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        str = str.replace(/\s/, transform_space).replace('--', '-');

        return str;
    };

    $(".slug").keyup(function () {
        var val = $(this).val();
        $(this).val(slug(val, '-'));
    });

});
