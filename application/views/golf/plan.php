<?php $this->load->view('layout/header/header'); ?>

<h2><?php echo $course['golfCourseName']; ?></h2>
<?php echo $course['golfCourseCaption']; ?><br />
<?php for($index = 1; $index<=10; $index++) : ?>
    <?php
        if(isset($course['golfCourseImageUrl'.$index]) && $course['golfCourseImageUrl'.$index] != ''){
            echo img(array('src' => $course['golfCourseImageUrl'.$index]));
        }else{
            break;
        }
    ?><br />
<?php endfor; ?>

<?php if(empty($target_plan)): ?>
    プランに空きがありませんでした。
<?php else : ?>
    <h3><?php echo $target_plan['planInfo']['planName']; ?></h3>
    <table>
        <tr>
            <td>price</td>
            <td><?php echo $target_plan['planInfo']['price']; ?></td>
        </tr>
        <tr>
            <td>other</td>
            <td><?php echo $target_plan['planInfo']['other']; ?></td>
        </tr>
        <tr>
            <td>予約</td>
            <td><?php echo anchor($target_plan['planInfo']['callInfo']['reservePageUrlPC'], 'GORAで予約する！'); ?></td>
        </tr>
    </table>
<?php endif;?>

<?php if(!empty($etc_plan)): ?>
    <h2><?php echo $course['golfCourseName']; ?>その他のプラン</h2>
    <?php foreach ($etc_plans as $etc_plan) : ?>
        <?php echo anchor('golf/plan/'.$area->id.'/'.$etc_plan['golfCourseId'].'/'.$future->date.'/'.$etc_plan['planInfo']['planId'], $etc_plan['golfCourseName'].'-'.$etc_plan['planInfo']['planName']); ?><br />
        <?php echo img(array('src' => $etc_plan['golfCourseImageUrl'])); ?><br />
    <?php endforeach; ?>
<?php endif;?>


<?php if(!empty($etc_area_plans)): ?>
    <h2><?php echo $area->todoufuken_name.' '.$future->date; ?>のゴルフコースプラン</h2>
    <?php foreach ($etc_area_plans as $etc_area_plan) : ?>
        <?php echo anchor('golf/plan/'.$area->id.'/'.$etc_area_plan['golfCourseId'].'/'.$future->date.'/'.$etc_area_plan['planInfo']['planId'], $etc_area_plan['golfCourseName'].'-'.$etc_area_plan['planInfo']['planName']); ?><br />
        <?php echo img(array('src' => $etc_area_plan['golfCourseImageUrl'])); ?><br />
    <?php endforeach; ?>
<?php endif;?>

<?php $this->load->view('layout/footer/footer'); ?>
