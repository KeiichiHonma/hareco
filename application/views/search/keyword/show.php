<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>出かけるなら晴れがいい-ハレコ</title>
<meta name="keywords" content="タイ,バンコク,チケット,購入,割引券,割引,クーポン,クーポンサイト,バウチャー" />
<meta name="description" content="タイ・バンコクのチケットをサイト名で購入！タイ・バンコクのクーポンがとても安い！お得なクーポン/バウチャーサイトです。" />
</head>
<body>
    <h2><?php echo $keyword; ?>晴れの予定</h2>
    <?php foreach ($futures as $future) : ?>
    <?php echo anchor('area/date/'.$area_id.'/'.$future->date,$future->date); ?>予想天気：<?php echo $future->daytime; ?><br />
    <?php endforeach; ?>
    <?php if(!empty($springs)) : ?>
        <?php if(isset($s_area_hotels)) : ?>
            <h2>[晴れの日に<?php echo $keyword; ?>近辺の温泉へ行く]</h2>
            <?php foreach ($s_area_hotels as $hotel) : ?>
                <?php echo anchor('spring/hotel/'.$springs[0]->id.'/'.$hotel['HotelID'].'/'.$springs[0]->area_id.'/s_area', $hotel['HotelName']); ?><br />
                <?php echo $hotel['HotelCatchCopy']; ?><br />
                <?php echo img(array('src' => $hotel['PictureURL'], 'alt' => $hotel['HotelName'])); ?><br />
            <?php endforeach; ?>
        <?php else: ?>
            <h2>[晴れの日に<?php echo $springs[0]->spring_area_name.'-'.$keyword; ?>近辺の温泉へ行く]</h2>
            <?php foreach ($springs as $spring) : ?>
                <?php if(isset($o_area_hotels[$spring->id])) : ?>
                    <h3><?php echo anchor('spring/show/'.$spring->id,$spring->spring_name); ?></h3>
                    <?php foreach ($o_area_hotels[$spring->id] as $hotel) : ?>
                        <?php echo anchor('spring/hotel/'.$spring->id.'/'.$hotel['HotelID'].'/'.$spring->area_id, $hotel['HotelName']); ?><br />
                        <?php echo $hotel['HotelCatchCopy']; ?><br />
                        <?php echo img(array('src' => $hotel['PictureURL'], 'alt' => $hotel['HotelName'])); ?><br />
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
    </dl>
</body>
</html>
