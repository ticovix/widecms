<?php

/*
 * ACTION TO INSTALL THE CMS WIDE
 */
try {
    /* Database */
    $host = trim(filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING));
    $database = trim(filter_input(INPUT_POST, 'database', FILTER_SANITIZE_STRING));
    $login = trim(filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING));
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
    /* CMS */
    $name_user = trim(filter_input(INPUT_POST, 'name_user', FILTER_SANITIZE_STRING));
    $email_user = trim(filter_input(INPUT_POST, 'email_user', FILTER_SANITIZE_STRING));
    $login_user = trim(filter_input(INPUT_POST, 'login_user', FILTER_SANITIZE_STRING));
    $password_user = trim(filter_input(INPUT_POST, 'password_user', FILTER_SANITIZE_STRING));

    $request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

    $dir_config = 'wd-admin/application/config/config.php';
    $dir_database = 'wd-admin/application/config/database.php';
    $dir_sql_database = 'install/db_cmswide.sql';
    $error = '';
    $success = false;

    if ($request_method === 'POST') {
        if (empty($database) or empty($login) or empty($name_user) or empty($email_user) or empty($login_user) or empty($password_user)) {
            throw new Exception('Todos os campos com asterísco (*) são de preenchimento obrigatório.');
        }
        if (strpos($email_user, '@') === false or strpos($email_user, '.') === false) {
            throw new Exception('Digite seu e-mail corretamente, será importante caso você esqueça a senha do admin e precise recuperar.');
        }
        if (!is_writable($dir_config)) {
            throw new Exception('Não é possível escrever no arquivo "config.php" em "' . $dir_config . '".');
        }
        if (!is_writable($dir_database)) {
            throw new Exception('Não é possível escrever no arquivo "database.php" em "' . $dir_database . '".');
        }
        if (!is_writable($dir_sql_database)) {
            throw new Exception('Não é possível escrever no arquivo "db_cmswide.sql" em "' . $dir_sql_database . '".');
        }

        if (empty($host)) {
            $host = 'localhost';
        }
        error_reporting(0);
        $conn = new mysqli($host, $login, $password);
        if (mysqli_connect_errno()) {
            $errors_conn = 'Não foi possível se conectar no Mysql! <br><br>';
            $errors_conn .= '<strong>Debugging error: </strong>' . mysqli_connect_error();
            throw new Exception($errors_conn);
        }
        require_once('wd-admin/application/helpers/passwordhash_helper.php');
        $PasswordHash = new PasswordHash(8, FALSE);
        $encrypt_key = $PasswordHash->HashPassword(rand(0, 9999));
        $sql = file_get_contents($dir_sql_database);
        $sql = str_replace(array('[[database]]', '[[name_user]]', '[[email_user]]', '[[login_user]]', '[[password_user]]',), array($database, $name_user, $email_user, $login_user, $PasswordHash->HashPassword($password_user)), $sql);

        $query = $conn->multi_query($sql);

        if (!$query) {
            throw new Exception('Não foi possível criar as tabelas padrões do CMS no banco de dados.<br><br> Error: ' . $conn->error);
        }

        $config = file_get_contents($dir_config);
        $config = str_replace('[[encryption_key]]', $encrypt_key, $config);
        $save_config = file_put_contents($dir_config, $config);
        if (!$save_config) {
            $error = '* Não foi possível criar uma chave única para o arquivo config.php em "' . $dir_config . '"<br>';
        }

        $config_database = file_get_contents($dir_database);
        $config_database = str_replace(array('[[hostname]]', '[[login]]', '[[password]]', '[[database]]'), array($host, $login, $password, $database), $config_database);
        $save_config_database = file_put_contents($dir_database, $config_database);
        if (!$save_config_database) {
            $error .= '* Não foi possível criar uma chave única para o arquivo database.php em "' . $dir_database . '"<br>';
        }
        require_once('wd-admin/application/helpers/utils_helper.php');
        unlink('install.php');
        forceRemoveDir('install');
        $conn->close();
        $success = true;
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}