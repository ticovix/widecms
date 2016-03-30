<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
?>
<div class="header">
    <h1 class="page-title"> 
        <img src="<?php if (!empty($profile['image'])) { ?><?php echo base_url() ?>assets/images/<?php echo $profile['image'] ?><?php } else { ?><?php echo base_url()?>assets/images/no_image.gif<?php } ?>" align="left" class="image-profile-md img-circle"> 
        Seja bem vindo <?php echo $profile['name'] ?>
    </h1>
</div>

<div class="container-fluid">
        <div class="col-sm-12"> 
            <div class="row apps-home">
                <?php
                $count = 0;
                foreach ($navigation as $menu)   {
                    $slug = $menu['slug'];
                    $name = $menu['name'];
                    $icon = $menu['icon'];
                        if ($count > 0 && ($count % 6) == 0) {
                            ?>
                        </div><div class="row apps-home">
                        <?php } ?>
                        <a href="<?php echo base_url() . $slug ?>" class="col-sm-2 app">
                            <p><span class="fa <?php echo $icon;?> icon-hm"></span></p>
                            <p><?php echo $name ?></p>
                        </a>
                        <?php
                        $count++;
                    }
                
                ?>
            </div>
        </div>
</div>