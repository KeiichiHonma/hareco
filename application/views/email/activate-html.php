<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>【<?php echo $site_name; ?>】アカウント登録手続きのご案内</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo $site_name; ?>へのご登録ありがとうございます！</h2>
登録はまだ完了していません。<br />
下記のリンクへアクセスして<?php echo $site_name; ?>をはじめましょう。
<br />
<nobr><a href="<?php echo site_url(INTL_LANG.'auth/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url(INTL_LANG.'auth/activate/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
<br />
※URLをクリックしても登録完了できない場合は、お手数ですが<br />
上記URLをコピーし、ブラウザのアドレス入力欄に貼り付けてアクセスしてください。
<br />
<br />
この登録URLの有効時間はメール受信から<?php echo $activation_period; ?>時間以内です。<br />
有効期限を過ぎてもアクセスがない場合はURLが無効となりますので、<br />
再度、<?php echo $site_name; ?>アカウント登録画面よりメールアドレスの送信をお願いいたします。
<br />
<br />
▼<?php echo $site_name; ?>アカウント登録<br />
<?php echo anchor('auth/register', site_url('auth/register')); ?>
<br />
<br />
・メンテナンス中の場合、アカウント登録手続きを行うことはできません。<br />
・このメールアドレスは配信専用ですので、メッセージを返信しないようお願いいたします。<br />
・本メール内容に身に覚えが無い場合には、恐れ入りますが当メールを破棄してください。<br />
<br />
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br />
バンコクでクーポン探すなら - <?php echo $site_name; ?> -<br />
<?php echo site_url(INTL_LANG); ?><br />
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br />
週刊WISE http://wisebk.com/<br />
Copyright (C) RyDEEN Co., Ltd. All Rights Reserved
</td>
</tr>
</table>
</div>
</body>
</html>