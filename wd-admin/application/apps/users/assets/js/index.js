$(function(){
    var del = false;
    $(".multiple_delete").change(function(){
        var is_checked = $(".multiple_delete").is(":checked");
        if(is_checked){
            $("#btn-del-all").removeClass("hide");
        }else{
            $("#btn-del-all").addClass("hide");
        }
    });
});