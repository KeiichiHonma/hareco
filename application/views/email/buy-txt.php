<?php
$coupon_title_language = 'coupon_title_'.$this->config->item('language_min');
$site_name = $this->config->item('website_name', 'tank_auth');
?>
<?php echo $site_name; ?>クーポンをご購入いただきありがとうございます。

ご購入受付日：<?php echo date("Y/m/d",time())."\n"; ?>
ご購入番号  ：<?php echo $order."\n"; ?>

[ご購入内容]
<?php
$payments = array();
foreach ($purchases['coupons'] as $coupon_id => $coupon){
print "\n-".$coupon[$coupon_title_language]."\n";
print ' 価格:'.$coupon['price'].'THB'."\n";
print ' 数量:'.$coupon['number']."\n";
print ' 小計:'.$coupon['subtotal'].'THB'."\n";
}
print "\n".'合計金額 : '.$purchases['payment'].'THB'."\n";
?>

[お支払いについてのご案内]

購入日より10日以内に、所定の代金をお振り込みください。
お客様からの入金を確認後、電子メールをお送りいたし、それをもって購入が成立いたします。
この期間内に所定の代金のお支払いがなされない場合、当社は購入がなかったものとして 取り扱います。ご注意ください。
支払先についてはご登録のメールアドレス宛てに送信しておりますので、ご確認ください。


・ご購入の内容変更や取消しについては、サイト上の「ご利用にガイド」をご一読いただきますようにお願いいたします。
・このメールアドレスは連絡専用ですので、メッセージを返信しないようお願いいたします。
・本メール内容に身に覚えが無い場合には、恐れ入りますが当メールを破棄してください。

━━━━━━━━━━━━━━━━━━━━━━━━━
バンコクでクーポン探すなら - <?php echo $site_name; ?>

<?php echo site_url(INTL_LANG); ?>

━━━━━━━━━━━━━━━━━━━━━━━━━
週刊WISE http://wisebk.com/
Copyright (C) RyDEEN Co., Ltd. All Rights Reserved
