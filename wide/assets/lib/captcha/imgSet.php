<?
/******************************************************
**
**	Nome do Arquivo: imgSet.php
**	Data de Criaзгo: 01/05/2007
**	Autor: Thiago Felipe Festa - thiagofesta@gmail.com
**	Ъltima alteraзгo: 
**	Modificado por: 
**
******************************************************/

// Destruo o $carac, para nгo ficar sobrepondo varias vezes.
unset($carac);
	// Crio uma imagem com 6 caracteres
	for ($i = 0; $i < 6 ;$i++) {
		// Seleciona de 0 а 2, onde 0 = letra maiъscula, 1 = minъscula e 2 = nъmero.
		$tipo = rand(0,2);
		switch($tipo) {
			// se pegou 0 ele cria uma letra maiъscula de A а Z.
			// com um porйm, o chr(rand(65,90)), a funзгo chr retorna um caractere especнfico, e o rand seleciona aleatуrio.
			// o motivo de estar rand(56,90), pois temos que usar de acordo com a tabela ASCII e o A = 65 e o Z = 90.
			case 0 : $str = chr(rand(65,90)); break;
			// mesma coisa que antes, mais aqui й minъsculo.
			case 1 : $str = chr(rand(97,122)); break;
			// mesma coisa que antes sу que aqui sгo nъmeros.
			case 2 : $str = chr(rand(48,57)); break;
			// caso ocorra algun erro no rand tipo ele para por aqui.
			default : break; break;
		}
		
		// Gera um tamanho para a fonte de 3 а 5.
		$tamanho = rand(15,15);
		
		// Seleciona as cor RGB, menos muito clara, pois o fundo й branco, por isso de 0 а 200.
		$sel_corR = rand(0,200);
		$sel_corG = rand(0,200);
		$sel_corB = rand(0,200);
		
		// Joga os caracteres em um determinado lugar da imagem, x e y, sendo x sempre ele mais ele, pra nгo perder a ordem.
		// Nome que comeзa em 10 e termina em 30, pois temos 6 caracteres, e nossa imagem tem 180px,
		// por isso que vai ser de 30 em 30. e 10 para nгo ficar um caractere em cima do outro.
		$x += rand(10,30);
		// o Y vai de 0 a 30, nгo a 50, pois pode cortar pois nossa imagem й de 50 px de altura.
		$y = rand(0,30);
		
		// Gera um array carac, com os dados que й o caractere, a cor do caractere, a posiзгo x e y.
		$carac[] = array("c" => $str, "tam" => $tamanho, "corR" => $sel_corR, "corG" => $sel_corG, "corB" => $sel_corB, "x" => $x, "y" => $y);
		
		// crio a variavel para usar e criar a sessгo logo abaixo com os caracteres que forгo criados.
		$auth .= $str;
	}

// Crio a sessгo carac, que й igual ao array dos caracteres, que utiliza para montar a imagem la na classe.
$_SESSION["carac"] = $carac;

// Crio a sessгo autenticaIMG com os caracteres que estгo na imagem, que utilizo para verificar se o cara digitou certo.
$_SESSION["autenticaIMG"] = $auth;

?>