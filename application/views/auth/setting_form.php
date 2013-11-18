<?php $this->load->view('layout/header/header'); ?>
<?php
/*
$form_email = array(
    'name'    => 'email',
    'id'    => 'email',
    'value'    => set_value('email',$email),
    'maxlength'    => 80,
    'class'    => 'mail',
    'style' => 'width:300px;',
);
*/

$first_name = array(
    'name'    => 'first_name',
    'id'    => 'first_name',
    //'value' => set_value('first_name',ENVIRONMENT == 'development' ? 'Keiichi Honma' : ''),
    'value' => set_value('first_name',$first_name),
    'maxlength'    => $this->config->item('first_name_max_length', 'tank_auth'),
    'style' => 'width:100px;',
    'class'    => 'text',
    'placeholder'    => 'First',
);

$middle_name = array(
    'name'    => 'middle_name',
    'id'    => 'middle_name',
    //'value' => set_value('middle_name',ENVIRONMENT == 'development' ? 'Keiichi Honma' : ''),
    'value' => set_value('middle_name',$middle_name),
    'maxlength'    => $this->config->item('middle_name_max_length', 'tank_auth'),
    'style' => 'width:100px;',
    'class'    => 'text',
    'placeholder'    => 'Middle',
);

$last_name = array(
    'name'    => 'last_name',
    'id'    => 'last_name',
    //'value' => set_value('last_name',ENVIRONMENT == 'development' ? 'Keiichi Honma' : ''),
    'value' => set_value('last_name',$last_name),
    'maxlength'    => $this->config->item('last_name_max_length', 'tank_auth'),
    'style' => 'width:100px;',
    'class'    => 'text',
    'placeholder'    => 'Last',
);

$form_address = array(
    'name'    => 'address',
    'id'    => 'address',
    'value' => set_value('address',$address),
    'maxlength'    => $this->config->item('address_max_length', 'tank_auth'),
    'style' => 'width:300px;',
    'class'    => 'text',
);

$form_zip = array(
    'name'    => 'zip',
    'id'    => 'zip',
    'value' => set_value('zip',$zip),
    'maxlength'    => $this->config->item('zip_max_length', 'tank_auth'),
    'style' => 'width:300px;',
    'class'    => 'text',
);
$form_phone = array(
    'name'    => 'phone',
    'id'    => 'phone',
    'value' => set_value('phone',$phone),
    'maxlength'    => $this->config->item('phone_max_length', 'tank_auth'),
    'style' => 'width:300px;',
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
                            <h1 class="l1"><?php echo $this->lang->line('auth_setting_title'); ?></h1>
                                <?php $message = $this->session->flashdata('message'); ?>
                                <?php if($message) :?>
                                <div class="signupPageArea">
                                    <p><?php echo nl2br($this->session->flashdata('message')); ?></p>
                                </div>
                                <?php endif; ?>
                                <div class="clearfix signupPageArea">
                                   <div>
                                   <ul>
                                       <li><?php echo $this->lang->line('auth_setting_edit_title'); ?></li>
                                       <li><?php echo anchor('auth/change_email', $this->lang->line('auth_email_edit_title')); ?></li>
                                       <li><?php echo anchor('auth/change_password', $this->lang->line('auth_password_edit_title')); ?></li>
                                   </ul>
                                   </div>
                                </div>


                                <?php echo form_open($this->uri->uri_string()); ?>
                                <input type="hidden" value="step1" name="mode">
                                <table class="table_o" style="width:100%">
                                    <tr class="g"><td class="line title"><?php echo $this->lang->line('Username'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td class="line">

                                    <?php echo form_input($first_name); ?>&nbsp;<?php echo form_input($middle_name); ?>&nbsp;<?php echo form_input($last_name); ?>
                                    <?php echo form_error('first_name'); ?><?php echo form_error('middle_name'); ?><?php echo form_error('last_name'); ?>
                                    
                                    </td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Address'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td><?php echo form_input($form_address); ?><?php echo form_error('address'); ?></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Zip'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td><?php echo form_input($form_zip); ?><?php echo form_error('zip'); ?></td></tr>
                                    <tr class="g"><td class="title"><?php echo $this->lang->line('Phone'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td><td><?php echo form_input($form_phone); ?><?php echo form_error('phone'); ?></td></tr>
                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Sex'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td>
                                        <td class="radio mt">
                                        <?php $sex_setting = $this->lang->line('user_profile_sex'); ?>
                                        <?php if (!empty($sex)) $user->sex = $sex; ?>
                                        <?php foreach ($sex_setting as $key => $value) : ?>
                                        <label><input type="radio" class="radio" value="<?php echo $key?>" name="sex"<?php if ($key == $user->sex) echo ' checked'; ?>><?php echo $value; ?></label>
                                        <?php endforeach; ?>
                                        <?php echo form_error('sex'); ?>
                                        </td>

                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Birthday Year'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td>
                                        <td class="text">
                                        <select id="birthday_year" name="birthday_year">
                                            <?php for($index = 1940; $index <= intval(date('Y')); $index++) : ?>
                                                <option value="<?php echo $index; ?>"<?php if ($index == $birthday_year) echo ' selected'; ?>><?php echo $index; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <?php echo form_error('birthday_year'); ?>
                                        </td>
                                    </tr>

                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Email receive setting'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td>
                                        <?php $receive_mail = $this->lang->line('user_profile_receive_mail'); ?>
                                        <td class="radio mt">
                                            <?php for ($index = 0; $index < count($receive_mail); $index++) : ?>
                                            <label><input type="radio" class="radio" value="<?php echo $index; ?>" name="need_notify"<?php if ($index == $user->need_notify) echo ' checked'; ?>><?php echo $receive_mail[$index]; ?></label>
                                            <?php endfor; ?>
                                            <?php echo form_error('need_notify'); ?>
                                        </td>
                                    </tr>

                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Interest'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td>
                                        <?php $user_interest = $this->lang->line('user_interest'); ?>
                                        <td class="radio mt">
                                            <?php for ($index = 0; $index < count($user_interest); $index++) : ?>
                                            <label><input type="checkbox" class="checkbox" value="<?php echo $index; ?>" name="interest[]"<?php if (in_array($index,$interest)) echo ' checked'; ?>><?php echo $user_interest[$index]; ?></label>
                                            <?php endfor; ?>
                                            <?php echo form_error('interest'); ?>
                                        </td>
                                    </tr>
                                    <tr class="g">
                                        <td class="title"><?php echo $this->lang->line('Language'); ?><p><img alt="必須" src="/images/icon_must.png"></p></td>
                                        <?php $user_profile_language = $this->lang->line('user_profile_language'); ?>
                                        <td class="radio mt">
                                            <?php for ($index = 0; $index < count($user_profile_language); $index++) : ?>
                                            <?php if (!($language === false)) $user->language = $language; ?>
                                            <label><input type="radio" class="radio" value="<?php echo $index; ?>" name="language"<?php if ($index == $user->language) echo ' checked'; ?>><?php echo $user_profile_language[$index]; ?></label><br>
                                            <?php endfor; ?>
                                            <?php echo form_error('language'); ?>
                                        </td>
                                    </tr>
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
