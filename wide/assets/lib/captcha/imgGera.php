<?
/******************************************************
**
**	Nome do Arquivo: imgGera.php
**	Data de Criaчуo: 01/05/2007
**	Autor: Thiago Felipe Festa - thiagofesta@gmail.com
**	кltima alteraчуo: 
**	Modificado por: 
**
******************************************************/

// Incluo a classe que gera a imagem.
require_once ("imagem.class.php");

// Instтncio a imagem
$imagem = new Imagem;
$imagem->geraImagem();

?>