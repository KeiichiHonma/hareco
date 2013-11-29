<?php $this->load->view('layout/header/header'); ?>

<?php foreach ($hotels as $hotel) : ?>
<?php echo anchor('spring/hotel/'.$spring->id.'/'.$hotel['HotelID'].'/'.$spring->area_id, $hotel['HotelName']); ?><br />
<?php echo $hotel['HotelCatchCopy']; ?><br />
<?php echo img(array('src' => $hotel['PictureURL'], 'alt' => $hotel['HotelName'])); ?><br />
<?php endforeach; ?>

<?php $this->load->view('layout/footer/footer'); ?>
