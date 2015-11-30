$(function(){
    $.ajaxSetup({
        data: {
            csrf_test_name: $.cookie('csrf_cookie_wide')
        }
    });
    
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
            url: url+'users/dev-mode',
            data: {dev:dev},
            success: function(){
                setTimeout(function(){
                    window.location.reload();
                },500);
            }
        });
    };
    
    $('#dashboard-menu').metisMenu();
});

