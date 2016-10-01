$(function(){
    $("input[type=text]").setMask();
    $(".btn-config-field").click(function(){
        var index_dropmenu = $(".dropdown-menu").index($(this).parent(".dropdown-menu"));
        var field = $(this).parent(".dropdown-menu").data("field");
        var value = $(this).children("a").data("value");
        $("#type_search_"+field).val(value);
        $(".dropdown-menu").eq(index_dropmenu).children(".btn-config-field").removeClass("active");
        $(this).addClass("active");
    });
    $(".register-current a").click(function(){
        $(".register-current").unbind("click");
    });
    $(".register-current").click(function(){
        var index = $(".register-current").index(this);
        var checked = $(".register-current").eq(index).hasClass("checked");
        if(checked===true){
            $(".multiple_delete").eq(index).removeAttr("checked");
            $(this).removeClass('checked');
        }else{
            $(".multiple_delete").eq(index).prop("checked","checked");
            $(this).addClass('checked');
        }
        var is_checked = $(".multiple_delete").is(":checked");
        if(is_checked){
            $("#btn-del-selected").removeClass("hide");
        }else{
            $("#btn-del-selected").addClass("hide");
        }
    });
});