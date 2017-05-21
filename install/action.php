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
    $language = trim(filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING));

    $request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

    $dir_config = 'wd-admin/application/config/config.php';
    $dir_database = 'wd-admin/application/config/database.php';
    $dir_sql_database = 'install/widecms_db.sql';
    $error = '';
    $success = false;

    if ($request_method === 'POST') {
        if (empty($database) or empty($login) or empty($name_user) or empty($email_user) or empty($login_user) or empty($password_user)) {
            throw new Exception('All fields marked with an asterisk (*) are required.');
        }

        if (strpos($email_user, '@') === false or strpos($email_user, '.') === false) {
            throw new Exception('Enter your email correctly, it will be important if you forget the admin password and need to recover.');
        }

        if (!is_writable($dir_config)) {
            throw new Exception('Can not write to "config.php" file in "' . $dir_config . '".');
        }

        if (!is_writable($dir_database)) {
            throw new Exception('Can not write to "database.php" file in "' . $dir_database . '".');
        }

        if (!is_writable($dir_sql_database)) {
            throw new Exception('Can not write to file "widecms_db.sql" in "' . $dir_sql_database . '".');
        }

        if (empty($host)) {
            $host = 'localhost';
        }

        if (!class_exists('mysqli')) {
            throw new Exception('Class mysqli was not found.');
        }

        error_reporting(0);
        $conn = new mysqli($host, $login, $password);
        if (mysqli_connect_errno()) {
            $errors_conn = 'Could not connect to Mysql! <br><br>';
            $errors_conn .= '<strong>Debugging error: </strong>' . mysqli_connect_error();
            throw new Exception($errors_conn);
        }

        require_once('wd-admin/application/helpers/passwordhash_helper.php');
        $PasswordHash = new PasswordHash(8, FALSE);
        $encrypt_key = $PasswordHash->HashPassword(rand(0, 99999) . time());
        $sql = str_replace(array('[[database]]', '[[name_user]]', '[[email_user]]', '[[login_user]]', '[[password_user]]',), array($database, $name_user, $email_user, $login_user, $PasswordHash->HashPassword($password_user)), file_get_contents($dir_sql_database));

        $query = $conn->multi_query($sql);

        if (!$query) {
            throw new Exception('Could not create the default CMS tables in the database.<br><br> Error: ' . $conn->error);
        }

        $config = str_replace(array('[[encryption_key]]', '[[language]]'), array($encrypt_key, $language), file_get_contents($dir_config));
        $save_config = file_put_contents($dir_config, $config);
        if (!$save_config) {
            $error = '* Could not create a unique key for the config.php file in "' . $dir_config . '"<br>';
        }

        $config_database = str_replace(array('[[hostname]]', '[[login]]', '[[password]]', '[[database]]'), array($host, $login, $password, $database), file_get_contents($dir_database));
        $save_config_database = file_put_contents($dir_database, $config_database);
        if (!$save_config_database) {
            $error .= '* Could not create a single key for the database.php file in "' . $dir_database . '"<br>';
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