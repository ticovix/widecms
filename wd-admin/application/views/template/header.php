<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
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

        <title><?php echo (!empty($title)) ? $title . ' | Wide CMS' : 'Wide CMS'; ?></title>

        <!-- Bootstrap -->
        <link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
        <?php if ($this->data_user['allow_dev']) { ?>
            <link rel="stylesheet" href="<?php echo base_url('assets/plugins/switchery/css/switchery.css') ?>">
        <?php } ?>
        <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png') ?>" />
        <!-- Custom Theme Style -->
        <link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet">
        <?php echo put_css(); ?>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="<?php echo base_url(); ?>" class="site_title">
                                <img src="<?php echo base_url('assets/images/cms_wide_sm.png') ?>">
                            </a>
                        </div>

                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <div class="profile">
                            <div class="profile_pic">
                                <img src="<?php
                                if (is_file('../wd-content/upload/'.$PROFILE['image'])) {
                                    echo wd_base_url('wd-content/upload/' . $PROFILE['image']);
                                } else {
                                    echo base_url('assets/images/user.png');
                                }
                                ?>" alt="<?php echo $PROFILE['name'] ?>" class="img-circle profile_img" height="56">
                            </div>
                            <div class="profile_info">
                                <span>Bem vindo,</span>
                                <h2><?php echo $PROFILE['name'] ?></h2>
                            </div>
                        </div>
                        <!-- /menu profile quick info -->

                        <br />

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="clearfix"></div>
                            <div class="menu_section">
                                <ul class="nav side-menu">
                                    <?php
                                    foreach ($APPS as $menu) {
                                        $app = $menu["app"];
                                        $name = $menu["name"];
                                        $icon = $menu["icon"];
                                        ?>
                                        <li class="<?php echo is_nav_active($this->uri->segment(2), $app, "current-page"); ?>">
                                            <a href="<?php echo base_url('apps/' . $app); ?>"> 
                                                <?php
                                                if (strpos($icon, '/') == false && strpos($icon, 'fa-') >= 0) {
                                                    ?>
                                                    <i class="fa <?php echo $icon ?>"></i>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <img src="<?php echo base_url('application/apps/' . $app . '/assets/' . $icon) ?>" class="fa">
                                                    <?php
                                                }
                                                echo $name;
                                                ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>

                        </div>
                        <!-- /sidebar menu -->
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">

                    <div class="nav_menu">
                        <nav class="" role="navigation">
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>

                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="<?php
                                        if (is_file('../wd-content/upload/'.$PROFILE['image'])) {
                                            echo wd_base_url('wd-content/upload/' . $PROFILE['image']);
                                        } else {
                                            echo base_url('assets/images/user.png');
                                        }
                                        ?>" alt="<?php echo $PROFILE['name'] ?>" height="29"><?php echo $PROFILE['name'] ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li>
                                            <a href="<?php echo base_url('logout') ?>"><i class="fa fa-sign-out pull-right"></i> Sair</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="dev-mode">
<?php if ($PROFILE['allow_dev']) { ?>Modo desenvolvedor <input type="checkbox" class="js-switch" <?php if ($PROFILE['dev_mode']) { ?>checked<?php } ?> /> <?php } ?>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
                <!-- /top navigation -->

                <?php
                /*
                  ?>
                  <!DOCTYPE html>
                  <html lang="pt-BR">

                  <head>

                  <meta charset="utf-8">

                  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/bootstrap-3.3.2/css/bootstrap.min.css') ?>">
                  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/stylesheets/theme.css') ?>">
                  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.css') ?>">
                  <?php if ($this->data_user['allow_dev']) { ?>
                  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/switchery/css/switchery.css') ?>">
                  <?php } ?>
                  <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png') ?>" />
                  <?php echo put_css(); ?>
                  </head>

                  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
                  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
                  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
                  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
                  <!--[if (gt IE 9)|!(IE)]><!-->
                  <body>
                  <!--<![endif]-->
                  <div class="navbar">
                  <ul class="nav pull-right navbar-nav">
                  <li id="fat-menu" class="dropdown">
                  <a href="#" role="button" class="dropdown-toggle active" data-toggle="dropdown">
                  <img src="<?php if (!empty($PROFILE['image'])) { ?><?php echo base_url('/upload/' . $PROFILE['image']);
                  } else {
                  echo base_url('assets/images/no_image.gif');
                  } ?>" class="image-profile-sm img-circle">
                  <?php echo $PROFILE['name'] ?>
                  <i class="fa fa-fw fa-caret-down"></i>
                  </a>

                  <ul class="dropdown-menu dropdown-menu-right">
                  <li><a tabindex="-1" href="<?php echo base_url('logout') ?>">Sair</a></li>
                  </ul>
                  </li>
                  </ul>
                  <div class="pull-right dev-mode">
                  <?php if ($PROFILE['allow_dev']) { ?>Modo desenvolvedor <input type="checkbox" class="js-switch" <?php if ($PROFILE['dev_mode']) { ?>checked<?php } ?> /> <?php } ?>
                  </div>
                  <div class="navbar-header">
                  <a href="<?php echo base_url() ?>">
                  <span class="first">
                  <img src="<?php echo base_url('assets/images/cms_wide_sm.png') ?>" class="logo">
                  </span>
                  </a>
                  </div>
                  </div>

                  <?php if ($this->uri->segment(1) != 'home' && $this->uri->segment(1) != '') { ?>
                  <div class="sidebar-nav">
                  <a href="#dashboard-menu" class="nav-header" data-toggle="collapse"><i class="icon-th"></i>Navegação</a>
                  <ul id="dashboard-menu" class="nav nav-list collapse in">
                  <?php
                  foreach ($APPS as $menu) {
                  $app = $menu["app"];
                  $name = $menu["name"];
                  $image = $menu["image"];
                  if ($app != 'projects') {
                  $active = $app;
                  } else {
                  $active = [$app, 'project'];
                  }
                  ?>
                  <li>
                  <a href="<?php echo base_url('apps/' . $app); ?>" class="<?php echo is_nav_active($this->uri->segment(2), $active); ?>">
                  <?php echo $name ?>
                  </a>
                  </li>
                  <?php
                  }
                  ?>

                  </ul>
                  </div>
                  <?php } ?>



                  <div class="content" <?php if ($this->uri->segment(1) == 'home' or $this->uri->segment(1) == '') { ?>style="margin:0px;"<?php } ?>>
                 * <?php
                 * */
                ?>
 