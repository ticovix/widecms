<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<div class="header">
    <h1 class="page-title"> 
        <img src="<?php if (!empty($PROFILE['image'])) { ?><?php echo base_url() ?>assets/images/<?php echo $PROFILE['image'] ?><?php } else { ?><?php echo base_url() ?>assets/images/no_image.gif<?php } ?>" align="left" class="image-profile-md img-circle"> 
        Seja bem vindo <?php echo $PROFILE['name'] ?>
    </h1>
</div>

<div class="container-fluid">
    <div class="col-sm-12"> 
        <div class="row apps-home">
            <?php
            $count = 0;
            foreach ($APPS as $menu) {
                $app = $menu['app'];
                $name = $menu['name'];
                $image = $menu['image'];
                $path_image = 'assets/apps/' . $app . '/' . $image;
                $path_image_default = 'assets/images/noicon.jpg';
                if (!is_file($path_image)) {
                    $path_image = $path_image_default;
                }
                if ($count > 0 && ($count % 6) == 0) {
                    ?>
                </div><div class="row apps-home">
                <?php } ?>
                <a href="<?php echo base_url('apps/' . $app) ?>" class="col-sm-2 col-xs-6 app center-block">
                    <img src="<?php echo base_url($path_image); ?>">
                    <p><?php echo $name ?></p>
                </a>
                <?php
                $count++;
            }
            ?>
        </div>
    </div>
</div>