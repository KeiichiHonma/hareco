<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>
    <?php
    if(isset($header_title)) {
        echo $header_title;
    } else {
        echo $this->lang->line('header_title');
    }
    ?>
</title>
<?php echo link_tag("images/favicon.ico", "shortcut icon", "image/x-icon"); ?>
<?php foreach($this->config->item('stylesheets') as $css) : ?>
<?php echo link_tag($css) . "\n"; ?>
<?php endforeach; ?>
<?php foreach($this->config->item('javascripts') as $js) : ?>
<?php echo script_tag($js) . "\n"; ?>
<?php endforeach; ?>
<meta name="keywords" content="Balloooooon!" />
<meta name="description" content="タイのバンコクでクーポン探すなら [Balloooooon!]" />
</head>
<body>
<!--
//////////////////////////////////////////////////////////////////////////////
header
//////////////////////////////////////////////////////////////////////////////
-->
<header id="linktop">
    <div class="cf" id="inner">
        <h1>
        <a href="<?php echo 'http://'.DOMAIN.'/'; ?>">Balloooooon!</a></h1>
        <div class="headerRight">
            <ul class="cf">
                <li>管理機能 > </li>
                <li><?php echo anchor('console/coupon/manage', 'クーポン'); ?></li>
                <li><?php echo anchor('console/area/manage', 'エリア'); ?></li>
                <li><?php echo anchor('console/category/manage', 'カテゴリ'); ?></li>
                <li><?php echo anchor('console/promotion/manage', 'プロモーション'); ?></li>
                <li><?php echo anchor('console/magazine/manage', 'メールマガジン'); ?></li>
                <li><?php echo anchor('console/user/manage', 'ユーザー'); ?></li>
                <li><?php echo anchor('console/purchase/manage', '購入管理'); ?></li>
                <li><?php echo anchor('console/news/manage', 'お知らせ'); ?></li>
                <li><?php echo anchor('console/contact/manage', 'お問い合わせ'); ?></li>
                <li><?php echo anchor('auth/logout', 'ログアウト'); ?></li>
            </ul>
        </div>
    </div>
</header>
<!--
//////////////////////////////////////////////////////////////////////////////
entry
//////////////////////////////////////////////////////////////////////////////
-->
<section id="search">
<div class="cf" id="searchArea">
    <div id="category">
        <p class="serviceDiscript">「Balloooooon!」管理画面</p>
    </div>
    <?php if(isset($isPromotionSearch)) : ?>
    <div id="tabContainer">
        <div>
            <?php echo form_open(INTL_LANG.'console/promotion/search', array('method' => 'get')); ?>
            <span>
                <input type="text" placeholder="プロモーション検索" class="search rounded" name="keyword"<?php if (isset($search_keywords)) echo " value='{$search_keywords}'" ?>>
            </span>
            <?php echo form_close(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>
</section>
<!--
//////////////////////////////////////////////////////////////////////////////
end of header
//////////////////////////////////////////////////////////////////////////////
-->
