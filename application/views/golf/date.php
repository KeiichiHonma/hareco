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

<?php if(empty($plans)): ?>
    プランに空きがありませんでした。
<?php else : ?>
    <h2><?php echo $course['golfCourseName'].' '.$future->date; ?>のプラン</h2>
    <?php foreach ($plans as $plan) : ?>
        <?php echo anchor('golf/plan/'.$area->id.'/'.$plan['golfCourseId'].'/'.$future->date.'/'.$plan['planInfo']['planId'], $plan['planInfo']['planName']); ?><br />
        <?php echo img(array('src' => $plan['golfCourseImageUrl'])); ?><br />
        <?php echo $plan['planInfo']['other']; ?>
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
