$(function(){
    var elem = document.querySelector('.js-switch');
    
    var init = new Switchery(elem, {size: 'small'});
    elem.onchange = function() {
        var dev;
        if(elem.checked){
            dev = 1;
        }else{
            dev = 0;
        }
        $.ajax({
            type: 'POST',
            url: url+'app/users/dev-mode',
            data: {dev:dev},
            success: function(){
                setTimeout(function(){
                    window.location.reload();
                },500);
            }
        });
    };
});