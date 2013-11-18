<?php $this->load->view('layout/header/header'); ?>
<?php
$email = array(
    'name'    => 'email',
    'id'    => 'email',
    'value'    => set_value('email',ENVIRONMENT == 'development' ? 'test2@zeus.corp.813.co.jp' : ''),
    'maxlength'    => 80,
    'class'    => 'text',
    'style' => 'width:300px;',
);
$password = array(
    'name'    => 'password',
    'id'    => 'password',
    'value' => set_value('password'),
    'maxlength'    => $this->config->item('password_max_length', 'tank_auth'),
    'class'    => 'text',
);
$confirm_password = array(
    'name'    => 'confirm_password',
    'id'    => 'confirm_password',
    'value' => set_value('confirm_password'),
    'maxlength'    => $this->config->item('password_max_length', 'tank_auth'),
    'class'    => 'text',
);
$first_name = array(
    'name'    => 'first_name',
    'id'    => 'first_name',
    'value' => set_value('first_name',ENVIRONMENT == 'development' ? 'Keiichi' : ''),
    'maxlength'    => $this->config->item('first_name_max_length', 'tank_auth'),
    'style' => 'width:100px;',
    'class'    => 'text',
    'placeholder'    => 'First',
);

$middle_name = array(
    'name'    => 'middle_name',
    'id'    => 'middle_name',
    'value' => set_value('middle_name',ENVIRONMENT == 'development' ? 'anzai' : ''),
    'maxlength'    => $this->config->item('middle_name_max_length', 'tank_auth'),
    'style' => 'width:100px;',
    'class'    => 'text',
    'placeholder'    => 'Middle',
);

$last_name = array(
    'name'    => 'last_name',
    'id'    => 'last_name',
    'value' => set_value('last_name',ENVIRONMENT == 'development' ? 'Honma' : ''),
    'maxlength'    => $this->config->item('last_name_max_length', 'tank_auth'),
    'style' => 'width:100px;',
    'class'    => 'text',
    'placeholder'    => 'Last',
);

$address = array(
    'name'    => 'address',
    'id'    => 'address',
    'value' => set_value('address',ENVIRONMENT == 'development' ? '25-4, MARUYAMACHO, SHIBUYA-KU, TOKYO' : ''),
    'maxlength'    => $this->config->item('address_max_length', 'tank_auth'),
    'style' => 'width:300px;',
    'class'    => 'text',
);
$zip = array(
    'name'    => 'zip',
    'id'    => 'zip',
    'value' => set_value('zip',ENVIRONMENT == 'development' ? '1550032' : ''),
    'maxlength'    => $this->config->item('zip_max_length', 'tank_auth'),
    'class'    => 'text',
);
$phone = array(
    'name'    => 'phone',
    'id'    => 'phone',
    'value' => set_value('phone',ENVIRONMENT == 'development' ? '09047673962' : ''),
    'maxlength'    => $this->config->item('phone_max_length', 'tank_auth'),
    'style' => 'width:300px;',
    'class'    => 'text',
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
                            <h1 class="l1"><?php echo $this->lang->line('auth_register_title'); ?></h1>

<?php $error_text = form_error('email'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('password'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('confirm_password'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('first_name'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('middle_name'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('last_name'); if (!empty($error_text)) $hasError = true; ?>

<?php $error_text = form_error('address'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('zip'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('phone'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('sex'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('birthday_year'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('need_notify'); if (!empty($error_text)) $hasError = true; ?>
<?php $error_text = form_error('interest '); if (!empty($error_text)) $hasError = true; ?>

<?php if (!empty($errors)) $hasError = true; ?>
<?php if( isset($hasError) && $hasError ) : ?>
<div class="signupComp signupError">
    <?php echo form_error('email'); ?>
    <?php echo form_error('password'); ?>
    <?php echo form_error('confirm_password'); ?>
    <?php echo form_error('first_name'); ?>
    <?php echo form_error('middle_name'); ?>
    <?php echo form_error('last_name'); ?>
    <?php echo form_error('address'); ?>
    <?php echo form_error('zip'); ?>
    <?php echo form_error('phone'); ?>
    <?php echo form_error('sex'); ?>
    <?php echo form_error('birthday_year'); ?>
    <?php echo form_error('need_notify'); ?>
    <?php echo form_error('interest '); ?>
    <?php foreach($errors as $error) : ?><p><?php echo $error; ?></p><?php endforeach; ?>
</div>
<?php endif; ?>
                                <?php echo form_open($this->uri->uri_string()); ?>
                                <input type="hidden" value="step1" name="mode">
                                <table class="table_o" style="width:100%">
                                    <tr class="g"><td class="line title"><?php echo $this->lang->line('Email Address'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td class="line"><?php echo form_input($email); ?></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Password'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td><?php echo form_password($password); ?><p><?php echo $this->config->item('password_min_length', 'tank_auth'); ?>文字以上<?php echo $this->config->item('password_max_length', 'tank_auth'); ?>文字以内の半角英数字</p></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Confirm Password'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td><?php echo form_password($confirm_password); ?><p><?php echo $this->config->item('password_min_length', 'tank_auth'); ?>文字以上<?php echo $this->config->item('password_max_length', 'tank_auth'); ?>文字以内の半角英数字</p></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Username'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td>
                                    
                                    <?php echo form_input($first_name); ?>&nbsp;<?php echo form_input($middle_name); ?>&nbsp;<?php echo form_input($last_name); ?>
                                    
                                    </td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Address'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td><?php echo form_input($address); ?></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Zip'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td><?php echo form_input($zip); ?></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Phone'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td><?php echo form_input($phone); ?></td></tr>
                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Sex'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td>
                                        <td>
                                        <select id="sex" name="sex" class="first-select">
                                        <?php $sex_setting = $this->lang->line('user_profile_sex'); ?>
                                        <?php foreach ($sex_setting as $key => $value) : ?>
                                        <option value="<?php echo $key?>"<?php if ($key == $sex) echo ' selected'; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                        </td>
                                    </tr>

                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Birthday Year'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td>
                                        <td>
                                        <select id="birthday_year" name="birthday_year">
                                        <?php for($index = 1920; $index <= intval(date('Y')); $index++) : ?>
                                            <option value="<?php echo $index; ?>"<?php if ($index == $birthday_year) echo ' selected'; ?>><?php echo $index; ?></option>
                                        <?php endfor; ?>
                                        </select>
                                    </td>
                                    </tr>

                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Email receive setting'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td>
                                        <?php $receive_mail = $this->lang->line('user_profile_receive_mail'); ?>
                                        <td class="radio mt">
                                            <?php for ($index = 0; $index < count($receive_mail); $index++) : ?>
                                            <label><input type="radio" class="radio" value="<?php echo $index; ?>" name="need_notify"<?php if ($index == $need_notify) echo ' checked'; ?>><?php echo $receive_mail[$index]; ?></label>
                                            <?php endfor; ?>
                                        </td>
                                    </tr>

                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Interest'); ?></td>
                                        <?php $user_interest = $this->lang->line('user_interest'); ?>
                                        <td class="radio mt">
                                            <?php for ($index = 0; $index < count($user_interest); $index++) : ?>
                                            <label><input type="checkbox" class="checkbox" value="<?php echo $index; ?>" name="interest[]"<?php if (in_array($index,$interest)) echo ' checked'; ?>><?php echo $user_interest[$index]; ?></label>
                                            <?php endfor; ?>
                                        </td>
                                    </tr>
                                </table>
                                <textarea rows="10" cols="30" class="regulation" readonly><?php echo $this->lang->line('rule_text'); ?></textarea>
                                <div class="btnErea">
                                    <input type="submit" name="btn_signup" class="submit" value="<?php echo $this->lang->line('Register'); ?>">
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
