<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<div class="row">
    <?php
    if ($widgets) {
        foreach ($widgets as $widget) {
            $title = $widget['title'];
            $app = $widget['app'];
            $col = $widget['col'];
            $icon = (isset($widget['icon'])) ? $widget['icon'] : 'fa-exclamation-triangle';
            $content = $widget['content'];
            ?>
            <div class="col-sm-<?php echo $col?> col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            <?php
                            if (strpos($icon, '/') == false && strpos($icon, 'fa-') >= 0) {
                                ?>
                                <i class="fa fa-fw <?php echo $icon ?>"></i>
                                <?php
                            } else {
                                ?>
                                <img src="<?php echo base_url('application/apps/' . $app . '/assets/' . $icon) ?>" class="fa">
                                <?php
                            }
                            echo $title;
                            ?>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="display: block;">
                        <?php echo $content ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>