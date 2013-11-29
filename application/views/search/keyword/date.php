<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>出かけるなら晴れがいい-ハレコ</title>
<meta name="keywords" content="タイ,バンコク,チケット,購入,割引券,割引,クーポン,クーポンサイト,バウチャー" />
<meta name="description" content="タイ・バンコクのチケットをサイト名で購入！タイ・バンコクのクーポンがとても安い！お得なクーポン/バウチャーサイトです。" />

<link rel="stylesheet" href="/css/default.css">
<link rel="stylesheet" href="/css/default.date.css">

<link rel='stylesheet' href='/css/fullcalendar/jquery-ui.min.css' />
<link href='/css/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='/css/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />

<script src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
 
<script src="/js/picker.js"></script>
<script src="/js/picker.date.js"></script>
<script src="/js/legacy.js"></script>

<script src='/js/fullcalendar/jquery-ui.custom.min.js'></script>
<script src='/js/fullcalendar/fullcalendar.min.js'></script>

<script type="text/javascript">
    $(document).ready(function() {
    
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        
        $('#calendar').fullCalendar({
            theme: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: true,
            events: [
                {
                    title: 'All Day Event',
                    start: new Date(y, m, 1)
                },
                {
                    title: 'Long Event',
                    start: new Date(y, m, d-5),
                    end: new Date(y, m, d-2)
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: new Date(y, m, d-3, 16, 0),
                    allDay: false
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: new Date(y, m, d+4, 16, 0),
                    allDay: false
                },
                {
                    title: 'Meeting',
                    start: new Date(y, m, d, 10, 30),
                    allDay: false
                },
                {
                    title: 'Lunch',
                    start: new Date(y, m, d, 12, 0),
                    end: new Date(y, m, d, 14, 0),
                    allDay: false
                },
                {
                    title: 'Birthday Party',
                    start: new Date(y, m, d+1, 19, 0),
                    end: new Date(y, m, d+1, 22, 30),
                    allDay: false
                },
                {
                    title: 'Click for Google',
                    start: new Date(y, m, 28),
                    end: new Date(y, m, 29),
                    url: 'http://google.com/'
                }
            ]
        });
        
    });
    $(function() {
        $('#demo001').pickadate();
    });
</script>

<style type="text/css">
<!--

    #calendar {
        width: 600px;
        margin: 0 auto;
        }
.data2_s
{
  background-color: #9ba8ca;
  border-collapse: collapse;
}
.data2_s th
{
  padding-top: 0em;
  padding-right: 0.4em;
  padding-bottom: 0em;
  padding-left: 0.4em;
  background-color: #dadfec;
  color: #333333;
  font-weight: normal;
  border-top-width: 2px;
  border-right-width-value: 2px;
  border-bottom-width: 2px;
  border-left-width-value: 2px;
  border-top-style: solid;
  border-right-style-value: solid;
  border-bottom-style: solid;
  border-left-style-value: solid;
  border-top-color: #9ba8ca;
  border-right-color-value: #9ba8ca;
  border-bottom-color: #9ba8ca;
  border-left-color-value: #9ba8ca;
  border-image-source: none;
  border-image-slice: 100% 100% 100% 100%;
  border-image-width: 1 1 1 1;
  border-image-outset: 0 0 0 0;
  border-image-repeat: stretch stretch;
  white-space: nowrap;
}
.data2_s td
{
  padding-top: 0em;
  padding-right: 0.4em;
  padding-bottom: 0em;
  padding-left: 0.4em;
  background-color: #ffffff;
  border-top-width: 2px;
  border-right-width-value: 2px;
  border-bottom-width: 2px;
  border-left-width-value: 2px;
  border-top-style: solid;
  border-right-style-value: solid;
  border-bottom-style: solid;
  border-left-style-value: solid;
  border-top-color: #9ba8ca;
  border-right-color-value: #9ba8ca;
  border-bottom-color: #9ba8ca;
  border-left-color-value: #9ba8ca;
  border-image-source: none;
  border-image-slice: 100% 100% 100% 100%;
  border-image-width: 1 1 1 1;
  border-image-outset: 0 0 0 0;
  border-image-repeat: stretch stretch;
  white-space: nowrap;
}
-->
</style>
</head>
<body>
<fieldset class="fieldset">
  <input id="demo001"  type="text" placeholder="クリックしてください">
</fieldset>
    <h2><?php echo $keyword.'-'.$week_futures[0]->date; ?>の天気予想</h2>
    <table>
        <tr>
        <?php foreach ($week_futures as $week_future) : ?>
            <td>
            <span style="font-weight:bold;<?php if($week_future->date == $week_futures[0]->date) echo 'color:red';  ?>"><?php echo $week_future->date; ?></span><br />
            <?php foreach ($week_future as $value) : ?>
                <?php echo $value; ?><br />
            <?php endforeach; ?>
            </td>
        <?php endforeach; ?>
        </tr>
    </table>
    
    <h2><?php echo $keyword.'-'.$week_futures[0]->date; ?>の記録データ</h2>
    <table border=1>
         <tr><td rowspan=<?php echo $count_daytimes + 1; ?>><?php echo key($daytimes) . reset($daytimes); ?></td><td>天気</td><td>登場数</td></tr>
         <?php foreach ($daytimes as $daytime_weather => $daytime_count) : ?>
         <tr><td><?php echo $daytime_weather; ?></td><td><?php echo $daytime_count; ?></td></tr>
         <?php endforeach; ?>
         <tr><td><?php echo key($feel_temperatures) . reset($feel_temperatures); ?></td><td colspan="2">温度の図</td></tr>
    </table>

<div id='calendar'></div>

    <h2><?php echo $keyword.'-'.$week_futures[0]->date; ?>の過去データ</h2>
    <table class="data2_s">
        <tr class="mtx"><th rowspan="4">日</th><th colspan="2">気圧(hPa)</th><th rowspan="2" colspan="3">降水量(mm)</th><th rowspan="2" colspan="3">気温(℃)</th><th rowspan="2" colspan="2">湿度(％)</th><th rowspan="2" colspan="5">風向・風速(m/s)</th><th rowspan="4">日照<br>時間<br>(h)</th><th rowspan="2" colspan="2">雪(cm)</th><th rowspan="2" colspan="2">天気概況</th></tr>
        <tr class="mtx"><th>現地</th><th colspan="1">海面</th></tr>
        <tr class="mtx"><th rowspan="2">平均</th><th rowspan="2">平均</th><th rowspan="2">合計</th><th colspan="2">最大</th><th rowspan="2">平均</th><th rowspan="2">最高</th><th rowspan="2">最低</th><th rowspan="2">平均</th><th rowspan="2">最小</th><th rowspan="2">平均<br>風速</th><th colspan="2">最大風速</th><th colspan="2">最大瞬間風速</th><th colspan="1">降雪</th><th colspan="1">最深積雪</th><th rowspan="2">昼<br>(06:00-18:00)</th><th rowspan="2">夜<br>(18:00-翌日06:00)</th></tr>
        <tr class="mtx"><th>1時間</th><th>10分間</th><th>風速</th><th>風向</th><th>風速</th><th>風向</th><th>合計</th><th>値</th></tr>
        <?php foreach ($month_day_weathers as $month_day_weather) : ?>
        <tr class="mtx" style="text-align:right;">
            <td><?php echo $month_day_weather->date; ?></td>
            <td><?php echo $month_day_weather->atmosphere_spot; ?></td>
            <td><?php echo $month_day_weather->atmosphere_ocean; ?></td>
            <td><?php echo $month_day_weather->precipitation_total; ?></td>
            <td><?php echo $month_day_weather->precipitation_one_hour; ?></td>
            <td><?php echo $month_day_weather->precipitation_ten_minute; ?></td>
            <td><?php echo $month_day_weather->temperature_average; ?></td>
            <td><?php echo $month_day_weather->temperature_max; ?></td>
            <td><?php echo $month_day_weather->temperature_min; ?></td>
            <td><?php echo $month_day_weather->moisture_average; ?></td>
            <td><?php echo $month_day_weather->moisture_min; ?></td>
            <td><?php echo $month_day_weather->wind_speed_average; ?></td>
            <td><?php echo $month_day_weather->wind_speed_max; ?></td>
            <td style="text-align:left"><?php echo $month_day_weather->wind_speed_max_direction; ?></td>
            <td><?php echo $month_day_weather->instant_wind_speed_max; ?></td>
            <td style="text-align:left"><?php echo $month_day_weather->instant_wind_speed_max_direction; ?></td>
            <td><?php echo $month_day_weather->sunshine_hours; ?></td>
            <td><?php echo $month_day_weather->snowfall; ?></td>
            <td><?php echo $month_day_weather->snow_deepest; ?></td>
            <td style="text-align:left"><?php echo $month_day_weather->daytime; ?></td>
            <td style="text-align:left"><?php echo $month_day_weather->night; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    
    <h2><?php echo $keyword; ?>晴れの休日</h2>
    <?php foreach ($etc_futures as $etc_future) : ?>
    <?php echo anchor('date/'.$etc_future->date,$etc_future->date); ?>予想天気：<?php echo $etc_future->daytime; ?><br />
    <?php endforeach; ?>

    <?php if(!empty($springs)) : ?>
        <?php if( !empty($s_area_plans) ) : ?>
            <h2>[晴れ予想の<?php echo $week_futures[0]->date; ?>に<?php echo $keyword; ?>近辺の温泉プラン]</h2>
            <?php foreach ($s_area_plans as $plan) : ?>
                <?php echo anchor('spring/plan/'.$springs[0]->id.'/'.$plan['Hotel']['HotelID'].'/'.$springs[0]->area_id.'/'.$week_futures[0]->date.'/'.$plan['PlanCD'], $plan['PlanName']); ?><br />
                <?php echo img(array('src' => $plan['PlanPicture'][0]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><br />
            <?php endforeach; ?>
        <?php else: ?>
            <h2>[晴れ予想の<?php echo $week_futures[0]->date; ?>に<?php echo $springs[0]->spring_area_name.'-'.$keyword; ?>近辺の温泉プラン]</h2>
            <?php foreach ($springs as $spring) : ?>
                <?php if(isset($o_area_plans[$spring->id])) : ?>
                    <h3><?php echo anchor('spring/show/'.$spring->id,$spring->spring_name); ?></h3>
                    <?php foreach ($o_area_plans[$spring->id] as $plan) : ?>
                        <?php echo anchor('spring/plan/'.$spring->id.'/'.$plan['Hotel']['HotelID'].'/'.$spring->area_id.'/'.$week_futures[0]->date.'/'.$plan['PlanCD'], $plan['PlanName']); ?><br />
                        <?php echo img(array('src' => $plan['PlanPicture'][0]['PlanPictureURL'], 'alt' => $plan['PlanName'])); ?><br />
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
    </dl>



</body>
</html>
