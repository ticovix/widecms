<?php

if(is_file('install.php')){
    header('Location: install.php');
    die();
}

echo utf8_decode('<h1>Parece que ocorreu tudo bem com a instalação :] <br> <a href="wd-admin">Crie um projeto.</a></h1>');