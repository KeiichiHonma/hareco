<?php $this->load->view('layout/header/header'); ?>
<?php
$form_email = array(
    'name'    => 'email',
    'id'    => 'email',
    'value'    => set_value('email',$email),
    'maxlength'    => 80,
    'class'    => 'text',
    'style' => 'width:300px;',
);
?>
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
                            <h1 class="l1"><?php echo $this->lang->line('auth_email_edit_title'); ?></h1>
                                <?php $error_text = form_error('email'); if (!empty($error_text)) $hasError = true; ?>

                                <?php if (!empty($errors)) $hasError = true; ?>
                                <?php if( isset($hasError) && $hasError ) : ?>
                                <div class="signupComp signupError">
                                    <?php echo form_error('email'); ?>
                                    <?php foreach($errors as $error) : ?><p><?php echo $error; ?></p><?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <div class="clearfix signupPageArea">
                                    <div>
                                   <ul>
                                    <li><?php echo anchor('auth/setting', $this->lang->line('auth_setting_edit_title')); ?></li>
                                       <li><?php echo $this->lang->line('auth_email_edit_title'); ?></li>
                                       <li><?php echo anchor('auth/change_password', $this->lang->line('auth_password_edit_title')); ?></li>
                                   </ul>
                                   </div>
                                </div>


                                <?php echo form_open($this->uri->uri_string()); ?>
                                <input type="hidden" value="step1" name="mode">
                                <table class="table_o" style="width:100%">
                                    <tr class="g"><td class="line title"><?php echo $this->lang->line('Email Address'); ?></td><td class="line"><?php echo form_input($form_email); ?><?php echo form_error($email['name']); ?></td></tr>
                                </table>

                                <div class="btnErea">
                                    <input type="submit" name="btn_signup" class="submit" value="<?php echo $this->lang->line('btn_edit'); ?>">
                                </div>
                                <?php echo form_close(); ?>
                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                    <?php $this->load->view('layout/sidebar/sidebar'); ?>
                </div>
            </div>
            <!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>
