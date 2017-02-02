<?php
if (!is_dir('vendor')) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'You must install composer dependencies.';
    exit(1); // EXIT_ERROR
}
require_once('install/action.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta name="robots" content="noindex" />
        <meta name="googlebot" content="noindex" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Instalação do CMS WIDE</title>
        <!-- Bootstrap -->
        <link href="vendor/components/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="vendor/components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- Fav Icon -->
        <link rel="icon" type="image/png" href="wd-admin/assets/images/favicon.png" />
        <style>
            body{
                background: #eee;
            }
            .header{
                margin-bottom: 20px;
            }
            .logo{
                margin-top: 30px;
                margin-bottom: 30px;
                max-width: 280px;
            }
            .title {
                text-align: center;
                margin-bottom: 5px;
            }
            label{
                font-weight: normal;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row header">
                <div class="col-lg-offset-3 col-sm-6">
                    <div align="center">
                        <img src="wd-admin/assets/images/widecms.png" class="logo img-responsive">
                        <strong>Ready to shorten your working time?</strong><br>Fill in the information below:
                    </div>
                </div>
            </div>
            <?php
            if ($success or ! empty($error)) {
                ?>
                <div class="row">
                    <div class="col-lg-offset-3 col-sm-6">
                        <?php
                        if (!empty($error)) {
                            ?>
                            <div class="alert alert-danger"><?php echo $error ?></div>
                            <?php
                        }
                        ?>
                        <?php
                        if ($success) {
                            ?>
                            <div class="alert alert-success">Configuration Complete! <a href="wd-admin/">Click here</a> and start creating your projects.</div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            if (!$success) {
                ?>
                <form method="post">
                    <div class="row">
                        <div class="col-lg-offset-3 col-sm-6">
                            <div class="title">
                                <strong>Configure the database</strong>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="host">Host</label>
                                        <input type="text" id="host" name="host" value="<?php echo $host ?>" class="form-control" placeholder="Padrão: localhost">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="database">Database *</label>
                                        <input type="text" id="database" name="database" value="<?php echo $database ?>" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="login">Login *</label>
                                        <input type="text" id="login" name="login" value="<?php echo $login ?>" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" value="<?php echo $password ?>" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-3 col-sm-6">
                            <div class="title">
                                <strong>Configure your WIDECMS user</strong>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name_user">Name *</label>
                                        <input type="text" id="name_user" name="name_user" value="<?php echo $name_user ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email_user">E-mail *</label>
                                        <input type="email" id="email_user" name="email_user" value="<?php echo $email_user ?>" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="login_user">Login *</label>
                                        <input type="text" id="login_user" name="login_user" value="<?php echo $login_user ?>" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password_user">Password *</label>
                                        <input type="password" id="password_user" name="password_user" value="<?php echo $password_user ?>" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="language">Language *</label>
                                        <select id="language" name="language" class="form-control">
                                            <option value="english" <?php if ($language == 'english') { ?>selected=""<?php } ?>>English (EUA)</option>
                                            <option value="portuguese-br" <?php if ($language == 'portuguese-br') { ?>selected=""<?php } ?>>Portuguese (Brazil)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-3 col-sm-6 text-right form-group">
                            <input type="submit" class="btn btn-success" value="Install">
                        </div>
                    </div>
                </form>
                <?php
            }
            ?>
        </div>
    </body>
</html>