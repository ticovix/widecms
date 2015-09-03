<div class="header">
    <div class="stats">
      <!--p class="stat"><span class="number">53</span>tickets</p>
      <p class="stat"><span class="number">27</span>tasks</p>
      <p class="stat"><span class="number">15</span>waiting</p-->
    </div>
    <h1 class="page-title"> 
        <img src="<?php if (!empty($profile->image)) { ?><?php echo $data_client->address ?>/<?php echo $data_client->address ?>/<?php echo $profile->image ?><?php } else { ?><?php echo ROUTES::baseUrl() ?>/template/images/no_image.gif<?php } ?>" align="left" class="image-profile-md img-circle"> 
        Seja bem vindo, <?php echo $profile->name ?>
    </h1>
    <p>Gerencie seu site, e-mails, instale aplicativos. Todas as soluções para sua empresa em um painel.</p>
</div>

<div class="container-fluid">
    <div class="row-fluid dashboard">
        <div class="col-sm-8"> 
            <div class="row apps-home">
                <?php
                $count = 0;
                foreach ($navigation as $menu) {
                    $slug = $menu["slug"];
                    $title = $menu["title"];
                    $stats = $menu["stats"];
                    $icon = $menu["icon"];
                    if ($stats == 1 && !empty($icon) && $slug != 'home') {
                        if ($count > 0 && ($count % 4) == 0) {
                            ?>
                        </div><div class="row apps-home">
                        <?php } ?>
                        <a href="<?php echo base_url() . $slug ?>" class="col-sm-3 app">
                            <p><span class="<?php echo $icon ?> icon-hm"></span></p>
                            <p><?php echo $title ?></p>
                        </a>
                        <?php
                        $count++;
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>