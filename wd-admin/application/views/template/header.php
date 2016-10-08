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

        <title><?php echo (!empty($title)) ? $title . ' | CMS WIDE' : 'CMS WIDE'; ?></title>

        <!-- Bootstrap -->
        <link href="<?php echo base_url('../vendor/components/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="<?php echo base_url('../vendor/components/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
        <?php if ($this->data_user['allow_dev']) { ?>
            <link rel="stylesheet" href="<?php echo base_url('assets/plugins/switchery/css/switchery.css') ?>">
        <?php } ?>
        <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png') ?>" />
        <!-- Custom Theme Style -->
        <link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet">
        <?php echo $this->include_components->put_css(); ?>
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
                                if (is_file('../wd-content/upload/' . $PROFILE['image'])) {
                                    echo wd_base_url('wd-content/upload/' . $PROFILE['image']);
                                } else {
                                    echo base_url('assets/images/user.png');
                                }
                                ?>" alt="<?php echo $PROFILE['name'] ?>" class="img-circle profile_img" height="56">
                            </div>
                            <div class="profile_info">
                                <span><?php echo $this->lang->line('panel_welcome'); ?></span>
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
                                    <li>
                                        <a href="<?php echo base_url() ?>" class="<?php echo is_nav_active($this->uri->segment(2), array('', 'home'), "current-page"); ?>">
                                            <i class="fa fa-dashboard"></i> <?php echo $this->lang->line('panel_btn_dashboard'); ?>
                                        </a>
                                    </li>
                                    <?php
                                    foreach ($APPS as $menu) {
                                        $app = $menu['app'];
                                        $name = $menu['name'];
                                        $icon = (isset($menu['icon'])) ? $menu['icon'] : 'fa-exclamation-triangle';
                                        $show = (isset($menu['show_nav'])) ? $menu['show_nav'] : 1;
                                        if ($show) {
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
                                        if (is_file('../wd-content/upload/' . $PROFILE['image'])) {
                                            echo wd_base_url('wd-content/upload/' . $PROFILE['image']);
                                        } else {
                                            echo base_url('assets/images/user.png');
                                        }
                                        ?>" alt="<?php echo $PROFILE['name'] ?>" height="29"><?php echo $PROFILE['name'] ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li>
                                            <a href="<?php echo base_url('logout') ?>"><i class="fa fa-sign-out pull-right"></i> <?php echo $this->lang->line('panel_logout'); ?></a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="dev-mode">
                                    <?php if ($PROFILE['allow_dev']) { ?><?php echo $this->lang->line('panel_dev_mode'); ?> <input type="checkbox" id="allow_dev" <?php if ($PROFILE['dev_mode']) { ?>checked<?php } ?> /> <?php } ?>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">