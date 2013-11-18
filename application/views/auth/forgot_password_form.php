<?php $this->load->view('layout/header/header'); ?>
<?php
$login = array(
    'name'    => 'login',
    'id'    => 'login',
    'class'    => 'text',
    'value' => set_value('login'),
    'maxlength'    => 80,
    'style' => 'width:200px;',
);
$login_label = $this->lang->line('Email');
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
                            <h1 class="l1"><?php echo $this->lang->line('auth_forgot_password_title'); ?></h1>
                            <p><?php echo nl2br($this->lang->line('auth_reset_password_message')); ?></p>
                                <?php echo form_open($this->uri->uri_string()); ?>
                                <table class="table_o" style="width:100%">
                                    <tr class="g">
                                        <td class="line title"><?php echo form_label($login_label, $login['id']); ?></td>
                                        <td class="line"><?php echo form_input($login); ?><div style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div></td>
                                    </tr>
                                </table>
                                <div class="btnErea">
                                    <input type="submit" name="btn_login_forget" class="submit" value="<?php echo $this->lang->line('Get a new password'); ?>">
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