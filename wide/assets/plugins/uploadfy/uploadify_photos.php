<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

require_once"../../../model/conexao.php";
$targetFolder = "{$_GET[diretorio]}/template/upload"; // Relative to the root
$id_page = $_GET["id_page"];
$id_gallery = $_GET["id_gallery"];
//$targetFolder = '/admin/includes/imagens/galeria'; // Relative to the root
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/');
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','JPG'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	define("_IMAGE_PATH",$targetFile);
	// max dimensions allowed:
	if (in_array($fileParts['extension'],$fileTypes)) {
		$photo = $_FILES['Filedata']['tmp_name'];
		$real_name = strtolower($_FILES['Filedata']['name']);
		$nm = md5($real_name);
    $x=1;
    while(true){
        $image_name = _IMAGE_PATH."/${nm}${x}.jpg";
		$img = _IMAGE_PATH."/${nm}${x}";
        if(!is_file($image_name))break;
        $x++;
    }
    $nome = $nm.$x;
		$imagem = $_FILES['Filedata'][tmp_name]; //pegando a url da iamgem que sera criada a minatura
		$nm = $_FILES['Filedata'][name]; // pegando o nome d aimagem
		$im = imagecreatefromjpeg($imagem); //criar uma amostra da imagem original
		$largurao = imagesx($im);// pegar a largura da amostra
		$alturao = imagesy($im);// pegar a altura da amostra
		$alturad = 250; // definir a altura da miniatura em px
		$largurad = ($largurao*$alturad)/$alturao;// calcula a largura da imagem a partir da altura da miniatura
		$nova = imagecreatetruecolor($largurad,$alturad);//criar uma imagem em branco
		imagecopyresampled($nova,$im,0,0 ,0,0,$largurad,$alturad,$largurao, $alturao);//copiar sobre a imagem em branco a amostra diminuindo conforma as especificações da miniatura
		imagejpeg($nova,$targetFile.'/'.$nome."_thumb.gif");//salva a imagem cria na pasta imagem
		imagedestroy($nova);//libera a memoria usada na miniatura
		imagedestroy($im);//libera a memoria usada na amostra
		
		move_uploaded_file($tempFile,$targetFile.'/'.$nome.".jpg");
		
		$data = date("y-m-d h:i:s");
		$sql = mysql_query("insert into photos_gallery (photo,id_page,id_gallery)values('{$nome}.jpg','$id_page','$id_gallery')");
		echo '1';
	}else {
		echo 'Invalid file type.';
	}
}
?>