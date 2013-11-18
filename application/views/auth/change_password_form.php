<?php $this->load->view('layout/header/header'); ?>
<?php
$old_password = array(
    'name'    => 'old_password',
    'id'    => 'old_password',
    'value' => set_value('old_password'),
    'size'     => 30,
    'autocomplete' => 'off',
    'class'    => 'text',
);
$new_password = array(
    'name'    => 'new_password',
    'id'    => 'new_password',
    'maxlength'    => $this->config->item('password_max_length', 'tank_auth'),
    'size'    => 30,
    'autocomplete' => 'off',
    'class'    => 'text',
);
$confirm_new_password = array(
    'name'    => 'confirm_new_password',
    'id'    => 'confirm_new_password',
    'maxlength'    => $this->config->item('password_max_length', 'tank_auth'),
    'size'     => 30,
    'autocomplete' => 'off',
    'class'    => 'text',
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
                            <h1 class="l1"><?php echo $this->lang->line('auth_password_edit_title'); ?></h1>

                                <div class="clearfix signupPageArea">
                                    <div>
                                   <ul>
                                    <li><?php echo anchor('auth/setting', $this->lang->line('auth_setting_edit_title')); ?></li>
                                       <li><?php echo anchor('auth/change_email', $this->lang->line('auth_email_edit_title')); ?></li>
                                       <li><?php echo $this->lang->line('auth_password_edit_title'); ?></li>
                                   </ul>
                                   </div>
                                </div>


                                <?php echo form_open($this->uri->uri_string()); ?>
                                <input type="hidden" value="step1" name="mode">
                                <table class="table_o" style="width:100%">
                                    <tr class="g"><td class="line title"><?php echo $this->lang->line('auth_old_password'); ?></td><td class="line"><?php echo form_password($old_password); ?><p><?php echo anchor('auth/forgot_password/', $this->lang->line('Forgot password')); ?></p><?php echo form_error($old_password['name']); ?><?php if (isset($errors[$old_password['name']])) echo "<p class='errorMessage'>{$errors[$old_password['name']]}</p>"; ?></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('auth_new_password'); ?></td><td><?php echo form_password($new_password); ?><p><?php echo $this->config->item('password_min_length', 'tank_auth'); ?>文字以上<?php echo $this->config->item('password_max_length', 'tank_auth'); ?>文字以内の半角英数字</p><?php echo form_error($new_password['name']); ?></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('auth_new_password_confirm'); ?></td><td><?php echo form_password($confirm_new_password); ?><p><?php echo $this->config->item('password_min_length', 'tank_auth'); ?>文字以上<?php echo $this->config->item('password_max_length', 'tank_auth'); ?>文字以内の半角英数字</p><?php echo form_error($confirm_new_password['name']); ?></td></tr>
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
