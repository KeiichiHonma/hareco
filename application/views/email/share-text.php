<?php
$title_language = 'title_'.$this->config->item('language_min');
$copy_language = 'copy_'.$this->config->item('language_min');
$description_language = 'description_'.$this->config->item('language_min');
?>
<?php echo $user->username; ?>さんからおすすめ情報のご案内です。

<?php echo $comment; ?>


■商品名
<?php echo $coupon->$title_language; ?>

<?php echo $coupon->$copy_language; ?>


■価格
<?php echo $coupon->price.$this->lang->line('unit'); ?>


■商品の詳細
<?php echo $coupon->$description_language; ?>


<?php echo site_url(lang_base_url('coupon/show/'.$coupon->id));?>


・商品の価格や在庫状況は変動することがありますので、ご注意ください。
価格や在庫状況はこのEメール送信時のものですが、お客様が<?php echo $site_name; ?>にアクセスした際に表示されている価格や在庫状況とは異なる場合があります。
・このメールアドレスは配信専用ですので、メッセージを返信しないようお願いいたします。
・本メール内容に身に覚えが無い場合には、恐れ入りますが当メールを破棄してください。

━━━━━━━━━━━━━━━━━━━━━━━━━
バンコクでクーポン探すなら - <?php echo $site_name; ?>

<?php echo site_url(INTL_LANG); ?>

━━━━━━━━━━━━━━━━━━━━━━━━━
週刊WISE http://wisebk.com/
Copyright (C) RyDEEN Co., Ltd. All Rights Reserved
