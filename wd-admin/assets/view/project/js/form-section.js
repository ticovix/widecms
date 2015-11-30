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

    $("#dig_name").keyup(function () {
        var val = $(this).val();
        $("#dir_name").val(slug(val,'-'));
        $("#table_name").val(slug(val,'_'));
    });
    
    $("#fields").on("keyup",".dig_name",function () {
        var val = $(this).val();
        var index = $(".dig_name").index(this);
        $(".column").eq(index).val(slug(val,'_'));
    });
    
    $("#addField").click(function(){
        var html = $(".field").eq(0).html();
        $("#fields").append("<div class='field'>"+html+"</div>");
        var final = $(".field").length;
        $(".field").eq(final).find('input[text]').val("");
    });
    
    
});