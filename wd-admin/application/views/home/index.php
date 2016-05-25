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
            $content = $widget['content'];
            ?>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?php echo $title?></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="display: block;">
                        <?php echo $content?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>