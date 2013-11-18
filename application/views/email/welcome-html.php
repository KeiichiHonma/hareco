<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>【<?php echo $site_name; ?>】登録完了メール</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo $site_name; ?>へようこそ！</h2>
はじめまして！<br />
このたびは<?php echo $site_name; ?>にご登録いただき、誠にありがとうございます。<br />
<?php echo $site_name; ?>は、バンコクで使えるクーポンサイトです！
<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url(INTL_LANG.'auth/login/'); ?>" style="color: #3366cc;">さっそく<?php echo $site_name; ?>アクセスしてみよう！<br /></a></b></big>
<br />
<?php if (strlen($username) > 0) : ?>ユーザ名: <?php echo $username; ?><br /><?php endif; ?>
登録メールアドレス: <?php echo $email; ?><br />
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
Copyright (C) RyDEEN Co., Ltd. All Rights Reserved<br />
</td>
</tr>
</table>
</div>
</body>
</html>