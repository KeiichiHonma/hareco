<?php $this->load->view('layout/header/header'); ?>
<script type="text/javascript">
$('#contents').corner("round 8px").parent().css('padding', '4px').corner("round 10px");
$('#page h1').corner("6px");
</script>
<?php $this->load->view('layout/common/topicpath'); ?>
            <div id="contents">
                <div id="contents_inner">
                    <!-- ■ MAIN CONTENTS ■ -->
                    <div id="page">
                        <div id="signup" class="c_wrapper">
                            <h1 class="l2"><span><?php echo $this->lang->line('auth_message_register_complete'); ?></span></h1>
                            <div class="signup02">
                            <p><?php echo $this->lang->line('auth_message_registration_completed_1'); ?></p>
                            <span class="btn_g"><?php echo force_anchor('', $this->lang->line('common_link_top')); ?></span>
                            </div>
                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                    <?php $this->load->view('layout/sidebar/sidebar'); ?>
                </div>
            </div>
            <!--/contents-->
<?php $this->load->view('layout/footer/footer'); ?>
