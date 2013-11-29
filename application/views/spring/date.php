<?php $this->load->view('layout/header/header'); ?>

<h2><?php echo $hotel['HotelName']; ?></h2>

<?php echo $hotel['HotelCatchCopy']; ?><br />
<?php echo img(array('src' => $hotel['PictureURL'], 'alt' => $hotel['HotelName'])); ?><br />

<?php if(empty($stocks)): ?>
プランに空きがありませんでした。
<?php else : ?>
<?php if(!empty($stocks)): ?>
    <h2><?php echo $future->date; ?>のプラン</h2>
    <?php foreach ($stocks as $stock) : ?>
        <?php echo anchor('spring/plan/'.$spring->id.'/'.$stock['Hotel']['HotelID'].'/'.$spring->area_id.'/'.$future->date.'/'.$stock['PlanCD'], $stock['PlanName']); ?><br />
        <?php echo img(array('src' => $stock['PlanPicture'][0]['PlanPictureURL'], 'alt' => $stock['PlanName'])); ?><br />
    <?php endforeach; ?>
<?php endif;?>
<?php endif;?>

<?php if(!empty($plans)): ?>
    <h2><?php echo $future->date; ?>のその他プラン</h2>
    <?php foreach ($plans as $plan) : ?>
        <?php echo anchor('spring/plan/'.$spring->id.'/'.$plan['Hotel']['HotelID'].'/'.$spring->area_id.'/'.$future->date.'/'.$plan['PlanCD'], $plan['PlanName']); ?><br />
        <?php echo img(array('src' => $plan['PlanPicture'][0]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><br />
    <?php endforeach; ?>
<?php endif;?>

<?php $this->load->view('layout/footer/footer'); ?>
