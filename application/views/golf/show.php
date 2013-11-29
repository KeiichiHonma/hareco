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

<h2><?php echo $course['golfCourseName'].'に晴れでいける連休'; ?></h2>
<?php foreach ($holiday_futures as $holiday_future) : ?>
<?php echo anchor('golf/date/'.$area->id.'/'.$course['golfCourseId'].'/'.$holiday_future->date, $holiday_future->date).'天気予想：'.$holiday_future->daytime.'気温：'.$holiday_future->temperature_max.'℃ / '.$holiday_future->temperature_min.'℃'; ?><br />

<?php endforeach; ?>


<?php $this->load->view('layout/footer/footer'); ?>
