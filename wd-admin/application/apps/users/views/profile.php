<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a></li>
    <li><a href="<?php echo base_url_app(); ?>"><?php echo $name_app?></a></li>
    <li class="active"><?php echo $title ?></li>
</ul>

<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <?php echo form_open(); ?>
                <div class="btn-toolbar">

                </div>
                <?php
                echo validation_errors('<p class="alert alert-danger">', '</p>');
                echo form_open(null, ['class' => 'form-horizontal']);
                ?>
                <div class="tab-pane active in" id="home">
                    <div class="row">
                        <div class="col-sm-2">
                            <a href="#gallery" class=" btn-upload" data-toggle="modal">
                                <img src="<?php
                                if (is_file('../wd-content/upload/' . $image)) {
                                    echo wd_base_url('wd-content/upload/' . $image);
                                } else {
                                    echo base_url('assets/images/user.png');
                                }
                                ?>" alt="Avatar" class="img-circle profile_img" id="img-profile">
                            </a>
                        </div>
                        <div class="col-sm-10">
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_name') ?>*</label><br>
                                        <?php echo $email ?>    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_login') ?>*</label><br>
                                        <?php echo $login ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line(APP . '_label_about') ?></label><br>
                                        <?php echo nl2br($about) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $this->lang->line(APP . '_title_activities') ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <?php
                    if (isset($history['result']) && count($history['result']) > 0) {
                        foreach ($history['result'] as $arr) {
                            $login = $arr['login'];
                            $name = $arr['name'];
                            $last_name = $arr['last_name'];
                            $image = $arr['image'];
                            $message = $arr['message'];
                            $date = diff_date_today($arr['date']);
                            ?>
                            <div class="col-sm-12">
                                <a href="<?php echo base_url('apps/users/profile/' . $login) ?>" class="pull-left"><img src="<?php
                                    if (is_file('../wd-content/upload/' . $image)) {
                                        echo wd_base_url('wd-content/upload/' . $image);
                                    } else {
                                        echo base_url('assets/images/user.png');
                                    }
                                    ?>" alt="<?php echo $name ?>" class="img-circle img-thumbnail img-profile-history" height="56"></a> 
                                <strong><a href="<?php echo base_url('apps/users/profile/' . $login) ?>"><?php echo $name ?></a></strong> <br><?php echo $message; ?><br>
                                <small><?php echo $date; ?></small>
                                <hr>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="col-sm-12">'.$this->lang->line(APP . '_msg_activities_not_found').'</div>';
                    }
                    ?>
                </div>
                <?php
                if ($total_history > 10) {
                    ?>
                    <ul class="pagination">
                        <?php echo $pagination; ?>
                    </ul>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>