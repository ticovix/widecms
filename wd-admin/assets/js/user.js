$(function(){
	var $tick = URL()+"/template/images/tick.png";
	var $cross = URL()+"/template/images/cross.png";
	var $loading = URL()+"/template/images/load.gif";

	$(".stats_load").delegate(".modify_stats","click",function(){
		var $this  = $(this);
		var $id    = $this.attr("data-id");
                var $type  = $this.attr("data-type");
		var $title = ($this.attr("title") == "Desativado")?"1":"0";
		var $user  = USER();
		var $position = $(".modify_stats").index(this);

		if($title == 1){
			$(this).hide().attr({"src":$tick,"title":"Ativado"});
		}else{
			$(this).hide().attr({"src":$cross,"title":"Desativado"});
		}
			$(".loading").eq($position).html("<img src='"+$loading+"'>").show();
		$.ajax({
			url: "../view/ajax/user.php",
			type:"POST",
			data:{user:$user,id_stats:$title,nav:$id,type:$type},
			success: function($data){
				$(".modify_stats").eq($position).show();
				$(".loading").eq($position).hide();
			}
		});
	});

	pass = "";
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
