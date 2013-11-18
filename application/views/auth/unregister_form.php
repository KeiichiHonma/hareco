<?php $this->load->view('layout/header/login'); ?>
<?php
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
?>
<div id="wrapper" class="cf">
    <div id="account">

    	<!-- header -->
        <div id="header">
        	<span><?php echo anchor('', img(array("src" => "images/title.png", "tilte" => ""))); ?></span>
        </div>
    	<!-- header -->
    	<!-- contents -->
    	<div id="contents_account" class="cf">
        	<div class="left">
            <h2>アカウント削除</h2>

	    	<p>
            アカウントを削除し、あなたのshabel上の全てのアカウントデータを消去します。<br />
            <strong style="color:#F00">重要: 一度削除されたアカウントデータは二度と回復できません!</strong>
            <div class="box_delete">あなたが過去に投稿したお題、解答は全て別アカウントに切り替え、掲載され続けます。<br />投稿全ての削除を求める方は<strong>inf&#111;&#64;e&#99;&#104;o&#101;&#115;.c&#111;&#46;&#106;&#112;</strong>にご連絡ください。</div>

            </p>
            <?php echo form_open($this->uri->uri_string()); ?>
                <div>
                <?php echo form_label('パスワードを入力してください:', $password['id']); ?>
                <?php echo form_password($password); ?>
                <?php echo form_error($password['name']); ?><?php if (isset($errors[$password['name']])) :?><div style="color: red;"><?php echo $errors[$password['name']]; ?></div><?php endif; ?>
                </div>
                <div>
                <input type="button" value="cancel" onClick="javascript:window.history.back();" />
                <?php echo form_submit('cancel', 'Delete account'); ?>
                </div>
            <?php echo form_close(); ?>
            </div>
        </div>
        <p class="backLink"><a href="../index.html">トップページに戻る</a> | <a href="javascript:window.history.back();">一つ前のページに戻る</a></p>
    	<!-- contents -->

    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
