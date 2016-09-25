            </div>
            <!-- footer content -->
            <footer>
                <div class="pull-right">
                    Wide CMS - 1.0
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
    </div>
    <script type="text/javascript">
        var url = '<?php echo base_url() ?>';
        var base_url = '<?php echo wd_base_url() ?>';
        var app = '<?php echo (defined('APP') ? APP : ''); ?>';
        var app_path = '<?php echo (defined('APP_PATH') ? base_url(APP_PATH) . '/' : ''); ?>';
        var app_assets = '<?php echo (defined('APP_ASSETS') ? base_url(APP_ASSETS) . '/' : ''); ?>';
    </script>
    <!-- jQuery -->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url('assets/plugins/fastclick/fastclick.js') ?>"></script>
    <!-- Jquery Cookie -->
    <script src="<?php echo base_url('assets/js/jquery.cookie.js') ?>"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo base_url('assets/js/custom.js') ?>"></script>
    <?php if ($this->data_user['allow_dev']) { ?>
        <script src="<?php echo base_url('assets/plugins/switchery/js/switchery.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/dev_mode.js') ?>"></script>
        <?php
    }
    ?>
    <!-- Personalized -->
    <?php echo put_js(); ?>
    </body>
</html>