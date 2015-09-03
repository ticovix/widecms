<!DOCTYPE html>
<html lang="pt-BR">

    <head>
        <meta name="robots" content="noindex" />
        <meta name="googlebot" content="noindex" />
        <meta charset="utf-8">
        <title>WIDE CMS</title>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Wide Develop">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/lib/bootstrap-3.3.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/stylesheets/theme<?php if (!empty($profile->theme)) { ?>_<?php echo $profile->theme ?><?php } ?>.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lib/font-awesome/css/font-awesome.css">
        <script src="<?php echo base_url() ?>assets/lib/jquery-1.9.1.js" type="text/javascript"></script>

        <?php /* if ($profile->language != 'pt' && !empty($profile->language)) { ?>
          <script type="text/javascript">
          $language = '<?php echo $profile->language ?>';
          </script>
          <script src="http://www.microsoftTranslator.com/ajax/v3/WidgetV3.ashx?siteData=ueOIGRSKkd965FeEGM5JtQ**" type="text/javascript"></script>
          <script src="<?php echo base_url()?>assets/js/translate.js" type="text/javascript"></script>
          <?php } */ ?>
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>assets/images/favicon.png" />
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
                <?php /*if (count($this->pages) > 0 && verify_permission('pages')) { ?>
                    <li id="fat-menu" class="dropdown">
                        <a href="#" role="button" title="Adicionar registro" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-paste"></i> 
                        </a>
                        <?php
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php
                            foreach ($pages as $arr) {
                                $list_functions = $PAGES->list_functions(array("page" => $arr["id"]))->fetchAll(PDO::FETCH_OBJ);
                                ?>
                                <li>
                                    <strong class="page-label">
                                        <?php echo $arr["name"] ?>
                                    </strong>
                                </li>
                                <li>
                                    <?php
                                    foreach ($list_functions as $obj) {
                                        ?>
                                        <a tabindex="-1" href="<?php echo ROUTES::baseLink() ?>/posts/<?php echo $obj->id ?>"><?= $obj->name ?></a>
                                        <?php
                                    }
                                    ?>

                                </li>
                                <?php
                            }
                            ?>

                        </ul>
                    </li>
                    <?php
                }*/
                ?>
                <li id="fat-menu" class="dropdown">
                    <a href="#" role="button" class="dropdown-toggle active notranslate" data-toggle="dropdown">
                        <img src="<?php if (!empty($profile['image'])) { ?><?php echo $data_client->address ?>/<?php echo $data_client->path_upload ?>/<?php echo $profile['image']?><?php } else { ?><?php echo base_url() ?>assets/images/no_image.gif<?php } ?>" class="image-profile-sm img-circle">
                        <?php echo $profile['name'] ?>
                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <?php if (verify_permission('myaccount')) { ?><li><a tabindex="-1" href="<?php echo base_url() ?>myaccount">Minha conta</a></li><?php } ?>
                        <?php if (verify_permission('config')) { ?><li><a tabindex="-1" href="<?php echo base_url() ?>config">Configurações</a></li><?php } ?>
                        <li><a tabindex="-1" href="<?php echo base_url() ?>logout">Sair</a></li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-header">
                <a href="<?php echo base_url() ?>">
                    <span class="first">
                        <img src="<?php echo base_url() ?>assets/images/widedevelop.png" class="logo">
                    </span>
                </a>
            </div>
        </div>

        <?php if ($this->uri->segment(0) != 'home' && $this->uri->segment(0) != '') { ?>
            <div class="sidebar-nav">
                <a href="#dashboard-menu" class="nav-header" data-toggle="collapse"><i class="icon-th"></i>Navegação</a>
                <ul id="dashboard-menu" class="nav nav-list collapse in">
                    <?php
                    foreach ($navigation as $menu) {
                        $url = $menu["url"];
                        $title = $menu["title"];
                        $stats = $menu["stats"];
                        $icon = $menu["icon"];
                        $subpage = $menu["subpage"];
                        $sub_url = $menu["sub_url"];
                        if ($stats == 1 && empty($subpage)) {
                            ?>
                            <li><a href="<?php echo base_url() . $url ?>" <?php if ($this->uri->segment(0) or in_array($this->uri->segment(0), $sub_url)) { ?>class="active"<?php } ?>> <span class="<?= $icon ?>"></span> <?php echo $title ?></a></li>
                            <?php
                        }
                    }
                    ?>

                </ul>
            </div>
        <?php } ?>



        <div class="content" <?php if ($this->uri->segment(0) == 'home' or $this->uri->segment(0) == '') { ?>style="margin:0px;"<?php } ?>>