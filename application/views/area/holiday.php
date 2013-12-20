<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">

        <div class="guide">
            <?php
                $i = 0;
                $count = count($all_areas);
                $before_region_id = '';
                $end_dl = FALSE;
            ?>
            <?php foreach ($holiday_futures as $holiday_future) : ?>
            <?php
                if($before_region_id != $holiday_future->region_id){
                    //if($i != 0 || $i != $count) echo '</dl>';
                    echo ($i != 0 || $i != $count ? '<h2 style="clear:both;">' : '<h2>').$all_regions[$holiday_future->region_id]->region_name.'</h2>';
                }
                $before_region_id = $holiday_future->region_id;
            ?>
                    <?php
                        $from_time = mktime(0,0,0,$holiday_future->month,$holiday_future->day,$holiday_future->year);
                        $to_time = $from_time + 86400;
                        $to_ymd = date("Y-m-d",$to_time);
                    ?>
                    <div class="box" style="margin-bottom:20px;">
                        <a href="<?php echo '/area/date/'.$holiday_future->area_id.'/'.$holiday_future->date; ?>">
                        <div class="photo"><img src="/images/area/<?php echo $holiday_future->area_id; ?>.jpg" alt="" /><div class="shadow">&nbsp;</div><span><?php echo $all_areas[$holiday_future->area_id]->area_name; ?></span></div>
                        <div class="icon"><img src="/images/icon_weather_01.png" alt="<?php echo $holiday_future->daytime; ?>" /></div>
                        <div class="text">
                            <div class="date">
                            <?php echo $holiday_future->month.'/'.$holiday_future->day; ?><?php echo get_day_of_the_week($holiday_future->day_of_the_week,array_key_exists($holiday_future->date,$all_holidays),TRUE); ?>
                            ～
                            <?php echo date("n/j",$to_time) ?><?php echo get_day_of_the_week(date("N",$to_time),array_key_exists($to_ymd,$all_holidays),TRUE); ?>
                            </div>
                            <div class="catch"><?php echo $holiday_future->holiday_sequence; ?>日連続晴れ予想</div>
                        </div>
                        </a>
                    </div>
            <?php $i++; ?>
            <?php endforeach; ?>

        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
