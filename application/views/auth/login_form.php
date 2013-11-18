<?php $this->load->view('layout/header/header'); ?>
<?php
$login = array(
    'name'    => 'login',
    'id'    => 'login',
    'class'    => 'text',
    'value' => set_value('login'),
    'maxlength'    => 80
);
$login_label = $this->lang->line('Email');

$password = array(
    'name'    => 'password',
    'id'    => 'password',
    'class'    => 'text'
);
$remember = array(
    'name'    => 'remember',
    'id'    => 'remember',
    'class'    => 'auto_login',
    'value'    => 1,
    'checked'    => set_value('remember'),
);
$captcha = array(
    'name'    => 'captcha',
    'id'    => 'captcha',
    'maxlength'    => 8,
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
                            <h1 class="l1"><?php echo $this->lang->line('auth_login_title'); ?></h1>
                            <?php $message = $this->session->flashdata('message'); ?>
                            <?php if($message) :?>
                            <div class="signupPageArea">
                                <p><?php echo nl2br($this->session->flashdata('message')); ?></p>
                            </div>
                            <?php endif; ?>
                                <?php echo form_open($this->uri->uri_string()); ?>
                                <table class="table_o" style="width:100%">
                                    <tr class="g">
                                        <td class="line title"><?php echo form_label($login_label, $login['id']); ?></td><td class="line"><?php echo form_input($login); ?></td>
                                    </tr>
                                    <tr class="g">
                                        <td class="title"><?php echo form_label($this->lang->line('Password'), $password['id']); ?></td><td><?php echo form_password($password); ?><p><?php echo sprintf($this->lang->line('auth_message_password_more_than'), $this->config->item('password_min_length', 'tank_auth'),$this->config->item('password_max_length', 'tank_auth')); ?></p></td>
                                    </tr>
                                </table>
                                <div class="btnErea">
                                    <input id="remember" class="auto_login" type="checkbox" value="1" name="remember"><?php echo $this->lang->line('auto_login'); ?>
                                    <input type="submit" style="display:block;" name="btn_login" class="submit" value="<?php echo $this->lang->line('btn_login'); ?>">
                                    <span class="foget_pass"><?php echo anchor('auth/forgot_password/', $this->lang->line('Forgot password')); ?></span>
                                </div>
                                <?php echo form_close(); ?>
                                <?php if ($this->config->item('allow_registration', 'tank_auth')) : ?>
                                <div class="signupPageArea">
                                    <p><?php echo $this->lang->line('auth_message_push_registration'); ?></p>
                                    <div><?php echo anchor('auth/register/', $this->lang->line('Register new'),'class="bt_m_o"'); ?></div>
                                </div>
                                <?php endif; ?>
                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                    <?php $this->load->view('layout/sidebar/sidebar'); ?>
                </div>
            </div>
            <!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>
