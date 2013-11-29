<?php $this->load->view('layout/header/header'); ?>

<h2><?php echo $hotel['HotelName']; ?></h2>
<?php echo $hotel['HotelCatchCopy']; ?><br />
<?php echo img(array('src' => $hotel['PictureURL'], 'alt' => $hotel['HotelName'])); ?><br />

<?php if(empty($target_plan)): ?>
    プランに空きがありませんでした。
<?php else : ?>
    <h3><?php echo $target_plan['PlanName']; ?></h3>
<table>
    <tr>
        <td>RoomName</td>
        <td><?php echo $target_plan['RoomName']; ?></td>
    </tr>
    <tr>
        <td>PlanPictureURL</td>
        <td>
            <?php foreach ($target_plan['PlanPicture'] as $picture) : ?>
            <?php $cap = isset($picture['PlanPictureCaption']) ? $picture['PlanPictureCaption'] : ''; ?>
            <?php echo img(array('src' => $picture['PlanPictureURL'], 'alt' => $cap)); ?><br />
            <?php echo $cap; ?><br />
            <?php endforeach; ?>
        </td>
    </tr>
    <tr>
        <td>facilities</td>
        <td><?php echo $target_plan['Facilities']; ?></td>
    </tr>
    <tr>
        <td>PlanCheckIn</td>
        <td><?php echo $target_plan['PlanCheckIn']; ?></td>
    </tr>
    <tr>
        <td>PlanCheckOut</td>
        <td><?php echo $target_plan['PlanCheckOut']; ?></td>
    </tr>
    <tr>
        <td>Meal</td>
        <td><?php echo $target_plan['Meal']; ?></td>
    </tr>
    <tr>
        <td>SampleRate</td>
        <td><?php echo $target_plan['SampleRate']; ?></td>
    </tr>
</table>
<?php endif;?>

<?php if(!empty($etc_plan)): ?>
    <h2><?php echo $hotel['HotelName']; ?>その他のプラン</h2>
    <?php foreach ($etc_plan as $plan) : ?>
    <?php echo anchor('spring/plan/'.$spring->id.'/'.$plan['Hotel']['HotelID'].'/'.$spring->area_id.'/'.$future->date.'/'.$plan['PlanCD'], $plan['PlanName']); ?><br />
    <?php echo img(array('src' => $plan['PlanPicture'][0]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><br />
    <?php endforeach; ?>
<?php endif;?>


<h2><?php echo $future->date; ?>のその他プラン</h2>
<?php foreach ($plans as $plan) : ?>
<?php echo anchor('spring/plan/'.$spring->id.'/'.$plan['Hotel']['HotelID'].'/'.$spring->area_id.'/'.$future->date.'/'.$plan['PlanCD'], $plan['PlanName']); ?><br />
<?php echo img(array('src' => $plan['PlanPicture'][0]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><br />
<?php endforeach; ?>

<?php $this->load->view('layout/footer/footer'); ?>
