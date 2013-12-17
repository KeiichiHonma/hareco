<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<style type="text/css">
<!--
#ind #mainImage .photo01{ background-image:url(images/slide/tokyo.jpg); }
#ind #mainImage .photo02{ background-image:url(images/slide/spring.jpg); }
#ind #mainImage .photo03{ background-image:url(images/slide/sapporo.jpg); }
-->
</style>
<div id="mainImage">
<div id="mainImageInner">
    <!-- キャッチコピー/検索ボックス -->
    <div id="copy">
        <h2>晴れてよかった！を創る。</h2>
        <h3>でかけるなら晴れがいい。世界初、天気予測エンジンで晴れを提案するサービス「ハレコ」</h3>
        <div id="searchBox">
            <div id="searchBoxInner">
                <?php echo form_open('/search','method="get" onsubmit="s_confirm();return false;" id="search"'); ?>
                    <input type="text" name="keyword" value="<?php echo $this->lang->line('search_box_default'); ?>" class="focus" /><input type="text" name="date" value="日付を選択" id="datepicker" /><input type="image" src="images/btn_search.png" align="top" alt="検索" class="btnSearch" />
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <div id="slider">
        <!-- 画像01 -->
        <div class="boxPhoto photo01">
            
            <div class="boxInner">
                <div class="desc cf"><img src="images/information.png" alt="" />ハレコは過去50年の過去データを元に、独自の天気予測エンジンを開発して未来の天気を予測しています。</div>
                <div class="minWeather cf">
                    <?php $tokyo_datetime = strtotime($slides['area'][30]->date); ?>
                    <a href="<?php echo 'area/date/'.$slides['area'][30]->area_id.'/'.$slides['area'][30]->date; ?>">
                    <h4>東京エリアの天気を予測<br />2日連続で晴れる連休は<?php echo date("n月j日",$tokyo_datetime); ?>-<?php echo date("n月j日",$tokyo_datetime+86400); ?>です</h4>
                    <div class="box">
                        <div class="icon"><img src="images/weather/icon/w_<?php echo $slides['area'][30]->daytime_icon_image; ?>" alt="<?php echo $slides['area'][30]->daytime; ?>" class="icon" /></div>
                        <div class="date"><?php echo date("n/j",$tokyo_datetime); ?></div>
                    </div>
                    <div class="box">
                        <div class="icon"><img src="images/weather/icon/w_<?php echo $slides['area'][30]->tomorrow_daytime_icon_image; ?>" alt="<?php echo $slides['area'][30]->tomorrow_daytime; ?>" class="icon" /></div>
                        <div class="date"><?php echo date("n/j",$tokyo_datetime+86400); ?></div>
                    </div>
                    </a>
                </div>
            </div>
            
        </div>
        
        <!-- 画像02 -->
        <div class="boxPhoto photo02">
            <div class="boxInner">
                <div class="desc cf"><img src="images/information.png" alt="" />1周間以上先の天気の予測正答確率は50%が限界と言われています。<br />ハレコでは6割を超える予測が可能な天気予測エンジンを開発しました。</div>
                <div class="minWeather cf">
                    <?php $hakone_datetime = strtotime($slides['spring']->date); ?>
                    <h4>箱根温泉の天気を予測<br />2日連続で晴れる連休は<?php echo date("n月j日",$hakone_datetime); ?>-<?php echo date("n月j日",$hakone_datetime+86400); ?>です</h4>
                    <div class="box">
                        <div class="icon"><img src="images/weather/icon/w_<?php echo $slides['spring']->daytime_icon_image; ?>" alt="<?php echo $slides['spring']->daytime; ?>" class="icon" /></div>
                        <div class="date"><?php echo date("n/j",$hakone_datetime); ?></div>
                    </div>
                    <div class="box">
                        <div class="icon"><img src="images/weather/icon/w_<?php echo $slides['spring']->tomorrow_daytime_icon_image; ?>" alt="<?php echo $slides['spring']->tomorrow_daytime; ?>" class="icon" /></div>
                        <div class="date"><?php echo date("n/j",$hakone_datetime+86400); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 画像03 -->
        <div class="boxPhoto photo03">
            <div class="boxInner">
                <div class="desc cf"><img src="images/information.png" alt="" />6割を高いか低いかを判断するのはユーザー様自身です。<br />絶対に晴れて欲しいおでかけやイベント等の計画の1つの指標にしてください。</div>
                <div class="minWeather cf">
                    <?php $sapporo_datetime = strtotime($slides['area'][4]->date); ?>
                    <h4>札幌エリアの天気を予測<br />2日連続で晴れる連休は<?php echo date("n月j日",$sapporo_datetime); ?>-<?php echo date("n月j日",$sapporo_datetime+86400); ?>です</h4>
                    <div class="box">
                        <div class="icon"><img src="images/weather/icon/w_<?php echo $slides['area'][4]->daytime_icon_image; ?>" alt="<?php echo $slides['area'][4]->daytime; ?>" class="icon" /></div>
                        <div class="date"><?php echo date("n/j",$sapporo_datetime); ?></div>
                    </div>
                    <div class="box">
                        <div class="icon"><img src="images/weather/icon/w_<?php echo $slides['area'][4]->tomorrow_daytime_icon_image; ?>" alt="<?php echo $slides['area'][4]->tomorrow_daytime; ?>" class="icon" /></div>
                        <div class="date"><?php echo date("n/j",$sapporo_datetime+86400); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">

        <div class="howtoBox cf">
            <h3><span>ハレコの使い方</span></h3>
            
            <div class="step step01">
                <h4>1.お出かけ場所を決める</h4>
                <p>行きたい場所や温泉、家の近くの公園等でかける場所を選びます。</p>
            </div>
            <div class="step step02">
                <h4>2.晴れの提案を受ける</h4>
                <p>ハレコは各エリアの未来に晴れる日程を提案します。</p>
            </div>
            <div class="step step03">
                <h4>3.晴れる予定を選択</h4>
                <p>晴れる日を選んだらおでかけの予定を立ててください。</p>
            </div>
            <div class="step step04">
                <h4>4.晴れてよかった！</h4>
                <p>晴れの日程でおでかけすることができましたね！</p>
            </div>
        </div>

        <div id="weather">
<?php
    $start_datetime = strtotime("+8 day");
    $start_date = date("Y-n-j",$start_datetime);
    $start_date_string = date("n/j",$start_datetime).get_day_of_the_week(date("N",$start_datetime),array_key_exists($start_date,$all_holidays),TRUE);

    $end_datetime = strtotime("+14 day");
    $end_date = date("Y-n-j",$end_datetime);
    $end_date_string = date("n/j",$end_datetime).get_day_of_the_week(date("N",$end_datetime),array_key_exists($end_date,$all_holidays),TRUE);
?>
<?php
    $i = 1;
    $class_array = array();
    $local_sp_style = array();
    foreach ($futures as $future){
        if($i == 8) break;
        if($future->day_of_the_week == 6){
            $class_array[$i] = 'day0'.$i.' sat';
        }elseif($future->day_of_the_week == 7){
            $class_array[$i] = 'day0'.$i.' sun';
        }else{
            $class_array[$i] = 'day0'.$i;
            $local_sp_style[] = '#weather td.day0'.$i;
        }
        $i++;
    }
?>
<style type="text/css">
<!--
@media only screen and (max-width: 640px){
<?php echo implode(',',$local_sp_style); ?>{ display:none; }
}
-->
</style>


            <h2><?php echo $start_date_string; ?>～<?php echo $end_date_string; ?>の未来天気予想 </h2>
            <table class="weather_index">
                <tr class="title">
                    <th class="cell01">日付</th>
                    <td class="<?php echo $class_array[1]; ?>"><?php echo $futures[0]->month.'/'.$futures[0]->day.get_day_of_the_week($futures[0]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[2]; ?>"><?php echo $futures[0]->month.'/'.$futures[1]->day.get_day_of_the_week($futures[1]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[3]; ?>"><?php echo $futures[0]->month.'/'.$futures[2]->day.get_day_of_the_week($futures[2]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[4]; ?>"><?php echo $futures[0]->month.'/'.$futures[3]->day.get_day_of_the_week($futures[3]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[5]; ?>"><?php echo $futures[0]->month.'/'.$futures[4]->day.get_day_of_the_week($futures[4]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[6]; ?>"><?php echo $futures[0]->month.'/'.$futures[5]->day.get_day_of_the_week($futures[5]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[7]; ?>"><?php echo $futures[0]->month.'/'.$futures[6]->day.get_day_of_the_week($futures[6]->day_of_the_week,FALSE,FALSE) ?></td>
                </tr>
                <tr>
                <th class="cell02"><?php echo $all_areas[$futures[0]->area_id]->area_name; ?></th>
                <?php
                    $td_number = 1;
                    $count = count($futures);
                ?>
                <?php for ($index = 0; $index < $count; $index++) : ?>
                    <?php if ($index > 0 && $index != $count - 1 && $index % 7 == 0) : ?>
                        <?php $td_number = 1; ?>
                        </tr>
                        <tr>
                        <th class="cell02"><?php echo $all_areas[$futures[$index]->area_id]->area_name; ?></th>
                    <?php endif; ?>

                    <td class="<?php echo $class_array[$td_number]; ?>"><img src="images/weather/icon/<?php echo $futures[$index]->daytime_icon_image; ?>" alt="<?php echo $futures[$index]->daytime; ?>" class="icon" /><br /><?php echo $futures[$index]->daytime; ?></td>
                    <?php $td_number++; ?>
                <?php endfor; ?>
            </table>
        </div>

        <div id="guide">

            <h2>おでかけチャンス！<?php echo $this->lang->line('holiday_title'); ?></em></h2>
            <?php foreach ($million_city_holiday_futures as $key => $chunk) : ?>
                <div class="line cf">
                <?php foreach ($chunk as $million_city_holiday_future) : ?>
                    <?php
                        $from_time = mktime(0,0,0,$million_city_holiday_future->month,$million_city_holiday_future->day,$million_city_holiday_future->year);
                        $to_time = $from_time + 86400;
                        $to_ymd = date("Y-m-d",$to_time);
                    ?>
                    
                    <div class="box">
                        <a href="<?php echo 'area/date/'.$million_city_holiday_future->area_id.'/'.$million_city_holiday_future->date; ?>">
                        <div class="photo"><img src="images/area/<?php echo $million_city_holiday_future->area_id; ?>.jpg" alt="" /><div class="shadow">&nbsp;</div><span><?php echo $all_areas[$million_city_holiday_future->area_id]->area_name; ?></span></div>
                        <div class="icon"><img src="images/weather/icon/b_<?php echo $million_city_holiday_future->daytime_icon_image; ?>" alt="<?php echo $million_city_holiday_future->daytime; ?>" /></div>
                        <div class="text">
                            <div class="date">
                            <?php echo $million_city_holiday_future->month.'/'.$million_city_holiday_future->day; ?><?php echo get_day_of_the_week($million_city_holiday_future->day_of_the_week,array_key_exists($million_city_holiday_future->date,$all_holidays),TRUE); ?>
                            ～
                            <?php echo date("n/j",$to_time) ?><?php echo get_day_of_the_week(date("N",$to_time),array_key_exists($to_ymd,$all_holidays),TRUE); ?>
                            </div>
                            <div class="catch"><?php echo $million_city_holiday_future->holiday_sequence; ?>日連続晴れ予想</div>
                        </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div class="allGuideBtn"><a href="/area/holiday">全エリア<?php echo $this->lang->line('holiday_title'); ?></a></div>
<?php $this->load->view('layout/common/leisure_guide'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
