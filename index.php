<?php

if(is_file('install.php')){
    header('Location: install.php');
    die();
}

echo "<h1>Parece que ocorreu tudo bem com a instalação :] <br> Crie um projeto.</h1>";