<?php $this->load->view('layout/header/header'); ?>

<?php echo $hotel['HotelName']; ?><br />

<?php foreach ($stocks as $date =>  $stock) : ?>
<?php foreach ($stock as $plan) : ?>
<?php echo $plan['PlanName'].key($plan['stay']['date']).anchor($plan['stay']['PlanDetailURL'], 'じゃらんで予約'); ?><br />



<?php endforeach; ?>
<?php endforeach; ?>

<?php $this->load->view('layout/footer/footer'); ?>
