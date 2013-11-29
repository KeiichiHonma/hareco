<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>出かけるなら晴れがいい-ハレコ</title>
<meta name="keywords" content="タイ,バンコク,チケット,購入,割引券,割引,クーポン,クーポンサイト,バウチャー" />
<meta name="description" content="タイ・バンコクのチケットをサイト名で購入！タイ・バンコクのクーポンがとても安い！お得なクーポン/バウチャーサイトです。" />
</head>
<body>
    <h2><?php echo $areas[$area_id]->area_name; ?>晴れの予定</h2>
    <?php foreach ($futures as $future) : ?>
    <?php echo anchor('date/'.$future->date,$future->date); ?>予想天気：<?php echo $future->daytime; ?><br />
    <?php endforeach; ?>
    <?php if(!empty($springs)) : ?>
    <h2>[晴れの日に<?php echo $springs[0]->spring_area_name.'-'.$areas[$area_id]->area_name; ?>近辺の温泉へ行く]</h2>
    <dl style="font-size:80%;">
    <?php foreach ($springs as $spring) : ?>
    <dd style="float:left;"><?php echo anchor('spring/show/'.$spring->id,$spring->spring_name); ?></dd>
    <?php endforeach; ?>
    <?php endif; ?>
    </dl>
</body>
</html>
