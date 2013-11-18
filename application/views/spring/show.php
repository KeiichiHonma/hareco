<?php $this->load->view('layout/header/header'); ?>



<?php foreach ($hotels as $hotel) : ?>
<?php echo anchor('hotel/show/'.$area_id.'/'.$spring_id.'/'.$hotel['HotelID'], $hotel['HotelName']); ?><br />
<?php endforeach; ?>

<?php $this->load->view('layout/footer/footer'); ?>
