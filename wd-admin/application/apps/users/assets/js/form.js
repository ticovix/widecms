$(function () {
    var elems = Array.prototype.slice.call(document.querySelectorAll('.check-permission'));
    var i = 0;
    elems.forEach(function (html) {
        eval("switchery" + i + " = new Switchery(html, {size:'small'})");
        i++;
    });
    $('.check-permission').change(function () {
        var page = $(this).data("page");
        var name = $(this).attr('name');
        var is_app = $(this).data('app');
        var sub = $(this).data("sub");
        var checked = $(this).prop('checked');
        if (is_app) {
            $("#" + name + "-list .check-permission").each(function () {
                var index = $(".check-permission").index(this);
                if (!checked) {
                    eval("switchery" + index + ".disable()");
                } else {
                    var sub = $(this).data("sub");
                    var is_check = $(".check-permission").filter(function(index){
                        return ($(this).data("page")==sub);
                    }).prop("checked");
                    if (is_check || sub == '' || sub == undefined) {
                        eval("switchery" + index + ".enable()");
                    }
                }
            });
        } else if (sub == undefined) {
            $(".check-permission").each(function () {
                var sub = $(this).data("sub");
                var index = $(".check-permission").index(this);
                if (sub == page && !checked) {
                    eval("switchery" + index + ".disable()");
                } else if (sub == page && checked) {
                    eval("switchery" + index + ".enable()");
                }
            });
        }
    });

    var pass = null;
    var position_bt = null;
    $(".generate-pass").click(function () {
        position_bt = $(".generate-pass").index(this);
        pass = "";
        var chars = 10; //Número de caracteres da senha
        generate(chars);
        $(".get-password").text(pass);
    });
    $(".bt-ok").click(function () {
        if (position_bt == '')
            position_bt = 0;
        $(".input-pass").eq(position_bt).val(pass);
        $(".re-input-pass").eq(position_bt).val(pass);
    });

    generate = function (chars) {
        for (var i = 0; i < chars; i++) {
            pass += getRandomChar();
        }
    }
    getRandomChar = function () {
        /*
         * matriz contendo em cada linha indices (inicial e final) da tabela ASCII para retornar alguns caracteres.
         * [48, 57] = numeros;
         * [64, 90] = "@" mais letras maiusculas;
         * [97, 122] = letras minusculas;
         */
        var ascii = [[48, 57], [64, 90], [97, 122]];
        var i = Math.floor(Math.random() * ascii.length);
        return String.fromCharCode(Math.floor(Math.random() * (ascii[i][1] - ascii[i][0])) + ascii[i][0]);
    }

});
