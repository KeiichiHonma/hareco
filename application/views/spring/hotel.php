<?php $this->load->view('layout/header/header'); ?>

<?php echo $hotel['HotelName']; ?><br />
<!--
<?php foreach ($stocks as $date =>  $stock) : ?>
<?php foreach ($stock as $plan) : ?>
<?php echo $plan['PlanName'].key($plan['stay']['date']).anchor($plan['stay']['PlanDetailURL'], 'じゃらんで予約'); ?><br />
<?php endforeach; ?>
<?php endforeach; ?>
-->
<h2><?php echo $hotel['HotelName']; ?></h2>
<?php echo $hotel['HotelCatchCopy']; ?><br />
<?php echo img(array('src' => $hotel['PictureURL'], 'alt' => $hotel['HotelName'])); ?><br />

<h2>[プラン一覧]</h2>
<?php foreach ($plans as $plan) : ?>
<?php echo $plan['PlanName']; ?><br />
<?php echo img(array('src' => $plan['PlanPicture'][0]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><br />
<?php endforeach; ?>

<h2><?php echo $hotel['HotelName'].'に晴れでいける連休'; ?></h2>
<?php foreach ($holiday_futures as $holiday_future) : ?>
<?php echo anchor('spring/date/'.$spring->id.'/'.$hotel['HotelID'].'/'.$spring->area_id.'/'.$holiday_future->date, $holiday_future->date).'天気予想：'.$holiday_future->daytime.'気温：'.$holiday_future->temperature_max.'℃ / '.$holiday_future->temperature_min.'℃'; ?><br />

<?php endforeach; ?>


<?php $this->load->view('layout/footer/footer'); ?>
