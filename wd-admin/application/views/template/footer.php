</div>
<footer>
    <div class="container-fluid">
        <span class='version'>Version 1.0</span>
    </div>
</footer>
<script type="text/javascript">
    var url = '<?php echo base_url() ?>';
    var base_url = '<?php echo wd_base_url() ?>';
    var app = '<?php echo (defined('APP') ? APP : ''); ?>';
    var app_path = '<?php echo (defined('APP_PATH') ? base_url(APP_PATH).'/' : ''); ?>';
    var app_assets = '<?php echo (defined('APP_ASSETS') ? base_url(APP_ASSETS).'/' : ''); ?>';
</script>
<script src="<?php echo base_url('assets/plugins/jquery-1.9.1.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-3.3.2/js/bootstrap.min.js') ?>"></script>
<?php if ($this->data_user['allow_dev']) { ?>
    <script src="<?php echo base_url('assets/plugins/switchery/js/switchery.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/dev_mode.js') ?>"></script>
    <?php
}
?>
<script src="<?php echo base_url('assets/plugins/metisMenu/js/jquery.metisMenu.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.cookie.js') ?>"></script>
<script src="<?php echo base_url('assets/js/scripts.js') ?>"></script>
<?php echo put_js(); ?>
</body>
</html>


