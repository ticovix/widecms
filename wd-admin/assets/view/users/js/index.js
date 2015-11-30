$(function(){
    var del = false;
    $(".multiple_delete").change(function(){
        del = false;
        $(".multiple_delete").each(function(){
            if($(this).prop('checked')){
                del = true;
                return false;
            }
        });
        if(del){
            $("#btn-del-all").removeClass('hide');
        }else{
            $("#btn-del-all").addClass('hide');
        }
    });
});