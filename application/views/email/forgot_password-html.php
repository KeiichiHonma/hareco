<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>【<?php echo $site_name; ?>】パスワードの再発行依頼を受付ました</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo $site_name; ?>のパスワードの再発行依頼を受付ました</h2>
以下のURLをクリックし、パスワード再発行ページにお進みください。
<br />
<nobr><a href="<?php echo site_url(INTL_LANG.'auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo site_url(INTL_LANG.'auth/reset_password/'.$user_id.'/'.$new_pass_key); ?></a></nobr><br />
<br />
※URLをクリックしても登録完了できない場合は、お手数ですが<br />
上記URLをコピーし、ブラウザのアドレス入力欄に貼り付けてアクセスしてください。
<br />
<br />
本メールは<?php echo $site_name; ?>より自動で発信されております。<br />
もし、心当たりが無い場合は確認のリンクをクリックしないようお願いいたします。<br />
Webサイトでのパスワード変更処理が完了されない場合は、以前のパスワードがそのまま利用可能となっております。<br />
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