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
