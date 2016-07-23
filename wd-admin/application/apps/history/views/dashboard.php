<div class="row">
    <?php
    if (isset($history['result']) && count($history['result'])>0) {
        foreach ($history['result'] as $arr) {
            $login = $arr['login'];
            $name = $arr['name'];
            $last_name = $arr['last_name'];
            $image = $arr['image'];
            $message = $arr['message'];
            $date = diff_date_today($arr['date']);
            ?>
            <div class="col-sm-12 form-group">
                <a href="<?php echo base_url('apps/users/profile/'.$login)?>" class="pull-left"><img src="<?php
                    if (is_file('../wd-content/upload/' . $image)) {
                        echo wd_base_url('wd-content/upload/' . $image);
                    } else {
                        echo base_url('assets/images/user.png');
                    }
                    ?>" alt="<?php echo $name ?>" class="img-circle img-thumbnail img-profile-history" height="56"></a> 
                    <strong><a href="<?php echo base_url('apps/users/profile/'.$login)?>"><?php echo $name ?></a></strong> <br><?php echo $message; ?><br>
                    <small><?php echo $date;?></small>
            </div>
            <?php
        }
    }else{
        echo "<div class='col-sm-12'>Nenhuma atividade recente.</div>";
    }
    ?>
</div>