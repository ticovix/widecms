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
        <meta charset="utf-8">
        <title>
            <?php echo (!empty($title)) ? $title . '| Wide CMS' : 'Wide CMS'; ?>
        </title>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/bootstrap-3.3.2/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/stylesheets/theme.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.css') ?>">
        <?php if($this->data_user['allow_dev']){?>
        <link rel="stylesheet" href="<?php echo base_url('assets/plugins/switchery/css/switchery.css') ?>">
        <?php }?>
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
                        <img src="<?php if (!empty($profile['image'])) { ?><?php echo base_url('/upload/'.$profile['image']); } else { echo base_url('assets/images/no_image.gif'); } ?>" class="image-profile-sm img-circle">
                        <?php echo $profile['name'] ?>
                        <i class="fa fa-fw fa-caret-down"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a tabindex="-1" href="<?php echo base_url('logout') ?>">Sair</a></li>
                    </ul>
                </li>
            </ul>
            <div class="pull-right dev-mode">
                <?php if ($this->data_user['allow_dev']) { ?>Modo desenvolvedor <input type="checkbox" class="js-switch" <?php if ($this->data_user['dev_mode']) { ?>checked<?php } ?> /> <?php } ?>
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
                    foreach ($navigation as $menu) {
                        $app = $menu["app"];
                        $name = $menu["name"];
                        $image = $menu["image"];
                        if ($app != 'projects') {
                            $active = $app;
                        } else {
                            $active = [$app, 'project'];
                        }
                        ?>
                        <li class="<?php echo is_nav_active($this->uri->segment(1), 'project'); ?>">
                            <a href="<?php echo base_url('app/'.$app);?>" class="<?php echo is_nav_active($this->uri->segment(1), $active); ?>"> 
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