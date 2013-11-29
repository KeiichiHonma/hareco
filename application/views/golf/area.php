<?php $this->load->view('layout/header/header'); ?>
<?php foreach ($courses as $course) : ?>
<?php echo anchor('golf/show/'.$area->id.'/'.$course['golfCourseId'], $course['golfCourseName']); ?><br />
<?php echo $course['golfCourseCaption']; ?><br />
<?php echo img(array('src' => $course['golfCourseImageUrl'], 'alt' => $course['golfCourseName'])); ?><br />

<?php endforeach; ?>

<?php $this->load->view('layout/footer/footer'); ?>
