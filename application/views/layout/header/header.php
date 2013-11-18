<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="<?php echo $this->config->item('language_min'); ?>">
<head>
<meta charset="UTF-8">
<title><?php echo isset($header_title) ? $header_title : $this->lang->line('header_title'); ?></title>
<meta name="keywords" content="<?php echo isset($header_keywords) ? $header_keywords : $this->lang->line('header_keywords'); ?>" />
<meta name="description" content="<?php echo isset($header_description) ? $header_description : $this->lang->line('header_description'); ?>" />

<?php echo link_tag("images/favicon.ico", "shortcut icon", "image/x-icon"); ?>

<?php foreach($this->config->item('stylesheets') as $css) : ?>
<?php echo link_tag($css) . "\n"; ?>
<?php endforeach; ?>

<?php foreach($this->config->item('javascripts') as $js) : ?>
<?php echo script_tag($js); ?>
<?php endforeach; ?>

</head>
<body>
