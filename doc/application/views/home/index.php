<!-- Page Content -->
<div class="container">
    <!-- Portfolio Section -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $about->title ?></h1>
            <?php echo $about->text ?>
        </div>
    </div>
    <!-- /.row -->
    <!-- Marketing Icons Section -->
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">
                Por que usar?
            </h2>
        </div>
        <?php
        if ($reason) {
            foreach($reason as $obj) {
                ?>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4><i class="fa fa-fw fa-<?php echo $obj->icon?>"></i> <?php echo $obj->title?></h4>
                        </div>
                        <div class="panel-body">
                            <p><?php echo $obj->description?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <!-- /.row -->

</div>