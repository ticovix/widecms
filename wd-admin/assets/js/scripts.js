$(function(){
    $.ajaxSetup({
        data: {
            csrf_test_name: $.cookie('csrf_cookie_wide')
        }
    });

    $('#dashboard-menu').metisMenu();
});

