$(function(){
	var pass = null;
        var position_bt = null;
	$(".generate-pass").click(function(){
		position_bt = $(".generate-pass").index(this);
		pass = "";
		var chars = 10; //Número de caracteres da senha
		generate(chars);
		$(".get-password").text(pass);
	});
	$(".bt-ok").click(function(){
		if(position_bt=='') position_bt = 0;
		$(".input-pass").eq(position_bt).val(pass);
		$(".re-input-pass").eq(position_bt).val(pass);
	});
	generate = function(chars) {
		for (var i= 0; i<chars; i++){
			pass += getRandomChar();
		}
	}
	getRandomChar = function(){
		/*
		* matriz contendo em cada linha indices (inicial e final) da tabela ASCII para retornar alguns caracteres.
		* [48, 57] = numeros;
		* [64, 90] = "@" mais letras maiusculas;
		* [97, 122] = letras minusculas;
		*/
		var ascii = [[48, 57],[64,90],[97,122]];
		var i = Math.floor(Math.random()*ascii.length);
		return String.fromCharCode(Math.floor(Math.random()*(ascii[i][1]-ascii[i][0]))+ascii[i][0]);
	}
});
