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
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/bootstrap-3.3.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/stylesheets/theme.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/switchery/css/switchery.css">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>assets/images/favicon.png" />
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
                        <img src="<?php if (!empty($profile['image'])) { ?><?php echo base_url() ?>/upload/<?php echo $profile['image'] ?><?php } else { ?><?php echo base_url() ?>assets/images/no_image.gif<?php } ?>" class="image-profile-sm img-circle">
                        <?php echo $profile['name'] ?>
                        <i class="fa fa-fw fa-caret-down"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a tabindex="-1" href="<?php echo base_url() ?>config">Configurações</a></li>
                        <li><a tabindex="-1" href="<?php echo base_url() ?>logout">Sair</a></li>
                    </ul>
                </li>
            </ul>
            <div class="pull-right dev-mode">
                <?php if ($this->data_user['allow_dev']) { ?>Modo desenvolvedor <input type="checkbox" class="js-switch" <?php if ($this->data_user['dev_mode']) { ?>checked<?php } ?> /> <?php } ?>
            </div>
            <div class="navbar-header">
                <a href="<?php echo base_url() ?>">
                    <span class="first">
                        <img src="<?php echo base_url() ?>assets/images/cms_wide_sm.png" class="logo">
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
                        $slug = $menu["slug"];
                        $name = $menu["name"];
                        $icon = $menu["icon"];
                        if ($slug != 'projects') {
                            $active = $slug;
                        } else {
                            $active = [$slug, 'project'];
                        }
                        ?>
                        <li class="<?php echo is_nav_active($this->uri->segment(1), 'project'); ?>">
                            <a href="<?php
                            if ($slug != 'projects' or $this->data_user['dev_mode']) {
                                echo base_url() . $slug;
                            } else {
                                echo 'javascript:void(0);';
                            }
                            ?>" class="<?php echo is_nav_active($this->uri->segment(1), $active); ?>"> 
                                <span class="fa <?php echo $icon ?> fa-fw"></span> <?php echo $name ?>
                                <?php if ($slug == 'projects' && !$this->data_user['dev_mode']) { ?><span class="fa arrow"></span><?php } ?>
                            </a>
                            <?php if ($slug == 'projects' && !$this->data_user['dev_mode']) { ?>
                                <ul class="nav nav-list nav-projects collapse <?php if ($this->uri->segment(1) == 'project') { ?>in<?php } ?>" <?php if ($this->uri->segment(1) != 'project') { ?>aria-expanded="false" style="height: 0px;"<?php } ?>>
                                    <?php
                                    $projects = projects();
                                    if ($projects) {
                                        foreach ($projects as $arr) {
                                            ?>
                                            <li class="<?php echo is_nav_active($this->uri->segment(2), $arr['slug']) ?>">
                                                <a href="<?php echo base_url(); ?>project/<?php echo $arr['slug'] ?>">
                                                    <?php echo $arr['name'] ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <li>Nenhum projeto encontrado.</li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <?php
                            }
                            ?>
                        </li>
                        <?php
                    }
                    ?>

                </ul>
            </div>
        <?php } ?>



        <div class="content" <?php if ($this->uri->segment(1) == 'home' or $this->uri->segment(1) == '') { ?>style="margin:0px;"<?php } ?>>