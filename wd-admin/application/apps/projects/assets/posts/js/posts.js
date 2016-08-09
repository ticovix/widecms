$(function(){
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