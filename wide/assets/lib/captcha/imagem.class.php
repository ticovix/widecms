<?
/******************************************************
**
**	Nome do Arquivo: imagem.class.php
**	Data de Criaчуo: 01/05/2007
**	Autor: Thiago Felipe Festa - thiagofesta@gmail.com
**	кltima alteraчуo: 
**	Modificado por: 
**
******************************************************/

/*****************************************
**	Classe		-	Imagem
**	Descriчуo	-	Cria a imagem
******************************************/

// Inicio a sessуo, pois estamos trabalhando com sessѕes.
session_start(); 

class Imagem{
	var $carac;
	
	function geraImagem(){
	
		// Seleciona uma imagem que estс na pasta bg/ com o nome 0.jpg р 9.jpg,
		// estс imagem que vai ser o fundo da nossa imagem de seguranчa.
		$fundo = "bg/";
		$fundo .= rand(0,9);
		$fundo .= ".jpg";
		
		// Cria a imagem.
		$imagem = imagecreatefromjpeg($fundo);

		// seta o $this->carac que щ a sessуo carac.
		$this->carac = $_SESSION["carac"];
		
		// percorre o array carac, e traz os valores.
		foreach($this->carac as $linha) {
			// Aqui crio a cor de cada caractere, RGB.
			$cor = imagecolorallocate($imagem, $linha["corR"], $linha["corG"], $linha["corB"]);
			// desenho o lugar dos caracteres de acordo com as posiчѕes x e y.
			imagestring($imagem, $linha["tam"], $linha["x"], $linha["y"], $linha["c"], $cor);
		}
		
		// ele informa que isso щ um arquivo PNG
		header("Content-type: image/png");
		
		// cria a imagem PNG
		imagepng($imagem);
		
	}

}

?>