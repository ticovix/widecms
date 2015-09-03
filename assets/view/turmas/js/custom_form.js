$(function () {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
    $('#ano').datepicker({
        startView: '1',
        todayHighlight: true,
        format: 'mm/yyyy',
        language: 'pt-BR',
        todayBtn: "linked",
        viewMode: "months", 
        minViewMode: "months"
    });

});

