<?php $this->load->view('layout/header/headerAbox'); ?>
<?php
$title_language = 'title_'.$this->config->item('language_min');
$copy_language = 'copy_'.$this->config->item('language_min');
?>
<script type="text/javascript">
$(function() {
    $('#send').click(function() {
        var share_emails = $("#emails").val();
        var share_comment = $("#comment").val();
        if(share_emails.length > 0 && share_comment.length > 0){
            $.post("<?php echo force_lang_base_url('coupon/send/'.$coupon->id); ?>",{<?php echo $csrf_token; ?>:"<?php echo $csrf_hash; ?>",emails:share_emails,comment:share_comment},function(result){
                if(result == 'success'){
                    alert("<?php echo $this->lang->line('coupon_share_send_result'); ?>");
                }else{
                    alert(result);
                }
            });
        }else{
            alert("<?php echo $this->lang->line('coupon_share_send_error'); ?>");
        }
    });

    $("#emails").focus(function() {
        if($(this).val() == $(this).attr('defaultValue'))
            $(this).val('');
    }).blur(function() {
        if(jQuery.trim($(this).val()) == "") {
            $(this).val($(this).attr('defaultValue'));
        }
    });
    $("#comment").focus(function() {
        if($(this).val() == $(this).attr('defaultValue'))
            $(this).val('');
    }).blur(function() {
        if(jQuery.trim($(this).val()) == "") {
            $(this).val($(this).attr('defaultValue'));
        }
    });
});

</script>

<div style="padding: 0px" id="page_c" class="tafPoContent n2">
    <form style="margin:0;" action="" name="tellAFriendForm" method="POST" id="formInThePopUp">
        <table class="emailFields" style="width:470px;">
            <tr>
                <td colspan="2"><h1 class="l2"><span><?php echo $this->lang->line('coupon_share_title'); ?></span></h1></td>
            </tr>
            <tr>
                <td>
                    <div class="share_box_l">
                        <div class="tn_img">
                            <img src="/<?php echo $coupon->thumbnail_filepath.'?'.strtotime($coupon->modified); ?>" class="image_resize">
                        </div>
                    </div>
                </td>
                <td align="left" style="padding:5px;"><p class="url_break"><?php echo $coupon->$title_language;?></p></td>
            </tr>
            <?php if($this->tank_auth->is_logged_in()) : ?>
            <tr>
                <th style="text-align: right; vertical-align: top;" nowrap>
                    <span style="width: 50px;"><?php echo $this->lang->line('coupon_share_sender'); ?>: </span>
                </th>
                <td><?php echo $user->username.'('.$user->email.')'; ?>
                </td>
            </tr>
            <tr>
                <th style="text-align: right; vertical-align: top;"><?php echo $this->lang->line('coupon_share_receiver'); ?>: </th>
                <td>
                <textarea id="emails" name="emails" style="padding:5px;width: 96%; height: 60px; resize: none" defaultValue="<?php echo $this->lang->line('coupon_share_receiver_default'); ?>"><?php echo $this->lang->line('coupon_share_receiver_default'); ?></textarea>
                </td>
            </tr>
            <tr>
                <th style="text-align: right; vertical-align: top;"><?php echo $this->lang->line('coupon_share_comment'); ?>: </th>
                <td>
                <textarea id="comment" name="comment" style="padding:5px;width: 96%; height: 60px; resize: none" defaultValue="<?php echo $this->lang->line('coupon_share_comment_default'); ?>"><?php echo $this->lang->line('coupon_share_comment_default'); ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="padding-top:20px;">
                    <div><a href="#" id="send" rel="close" class="bt_m_o"><?php echo $this->lang->line('coupon_share_send'); ?></a></div>
                </td>
            </tr>
            <?php else : ?>
            <tr>
                <td colspan="2" align="center" style="padding-top:20px;">
                    <?php echo $this->lang->line('coupon_share_not_login_message'); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="padding-top:20px;">
                    <div><?php echo anchor('auth/login', $this->lang->line('coupon_share_push_login'),'class="bt_m_o"'); ?></div>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        <div style="clear:both"/>
    </form>
</div>
<?php $this->load->view('layout/footer/footerAbox'); ?>
