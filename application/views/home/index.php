<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width,user-scalable=0" />
<title>ハレコ</title>
<link rel="shortcut icon" href="/images/favicon.ico">
<link rel="stylesheet" type="text/css" media="all" href="css/master.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.bxslider.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.sidr.dark.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.10.3.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>

<script type="text/javascript" src="js/css_browser_selector.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/jquery.bxslider.js"></script>
<script src="js/jquery.sidr.min.js"></script>
<!--[if IE 6]><script type="text/javascript" src="/js/DD_belatedPNG.js"></script><![endif]-->
<!--[if IE 8]><script type="text/javascript" src="js/jquery.backgroundSize.js"></script><![endif]-->
<!--[if lte IE 9]><script type="text/javascript" src="js/textshadow.js"></script><![endif]--> 
<script type="text/javascript">
$(function(){
    /*- スライダー */
    $('#slider').bxSlider({
        auto:true,
        speed:1000,
        mode: 'fade',
        hideControlOnEnd:false,
        pager:false,
        captions: false
    });    
    $('#slider').append('<div class="gradationLeft"></div><div class="gradationRight"></div>');
    /* 検索ボックス */
    $(".focus").focus(function(){
    if(this.value == "晴れる日のどちらにおでかけですか？　ex.ディズニーランド、箱根温泉、札幌、ニセコ"){
            $(this).val("").css("color","#333");
            }
        });
        $(".focus").blur(function(){
            if(this.value == ""){
            $(this).val("晴れる日のどちらにおでかけですか？　ex.ディズニーランド、箱根温泉、札幌、ニセコ").css("color","#a0a09f");
        }
    });
    /* カレンダー */
    $("#datepicker").datepicker();            
    /* PC用プルダウンメニュー */
    $(".navPc li").click(function() {
        $(this).children('ul').fadeToggle(300);
        $(this).nextAll().children('ul').hide();
        $(this).prevAll().children('ul').hide();
    });
    /* スマホ用メニュー */
    $('#right-menu').sidr({
      name: 'sidr-right',
      side: 'right'
    });
    /* リンク画像マウスオーバー処理 */
    $("a img, div.box a").hover(function(){
       $(this).fadeTo("fast", 0.7);
    },function(){
       $(this).fadeTo("fast", 1.0);
    });
    /* IE8 background-size対策 */
    jQuery('#cloud,#header h1 a,#header h2, #header .navPc li a.ttl').css({backgroundSize: "cover"});
});
</script>
</head>

<body id="ind">
<!-- 
//////////////////////////////////////////////////////////////////////////////
header
//////////////////////////////////////////////////////////////////////////////
-->
<div id="header" class="cf">
    <div id="headerInner" class="cf">
        <h1><a href="#">ハレコ</a></h1>
        <h2>晴れてよかった！を創るレコメンドサービス</h2>
        <!-- PC用ナビゲーション -->    
        <ul class="navPc">
            <li><a href="javascript:void(0)" class="ttl nav03"><span>▼祝日から探す</span></a>
                <ul>
                    <li><a href="#">プルダウンプルダウンプルダウンプルダウン</a></li>
                    <li><a href="#">プルダウン</a></li>
                    <li><a href="#">プルダウン</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0)" class="ttl nav02"><span>▼都市から探す</span></a>
                <ul>
                    <li><a href="#">プルダウン</a></li>
                    <li><a href="#">プルダウン</a></li>
                    <li><a href="#">プルダウン</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0)" class="ttl nav01"><span>▼エリアから探す</span></a>
                <ul>
                    <li><a href="#">プルダウン</a></li>
                    <li><a href="#">プルダウン</a></li>
                    <li><a href="#">プルダウン</a></li>
                </ul>
            </li>
        </ul>

        <!-- スマホ用ナビゲーション -->
        <div class="navSp">
            <span><a id="right-menu" href="javascript:void(0)">スマホ用ナビゲーション</a></span>
            <div id="sidr-right">
                <ul>
                    <li class="ttl">エリアから探す</li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                    <li class="ttl">都市から探す</li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                    <li class="ttl">都市から探す</li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                        <li><a href="#">メニュー</a></li>
                </ul>
            </div>
        </div>
        <div id="cloud">
            <h3>予測正解率</h3>
            <span>67%</span>
        </div>
    </div>
    <!-- パンクズ -->
    <div id="breadcrumb">
        <div id="breadcrumbInner" class="cf">
            <span><a href="#">都市</a></span>
            <span><a href="#">釧路</a></span>
        </div>
    </div>
</div>


<!-- 
//////////////////////////////////////////////////////////////////////////////
main image
//////////////////////////////////////////////////////////////////////////////
-->
<div id="mainImage">
<div id="mainImageInner">
    <!-- キャッチコピー/検索ボックス -->
    <div id="copy">
        <h2>でかけるなら晴れがいい！</h2>
        <h3>みんなで旅をつくるサービス「trippiece」トリッピースそれは今までにない新しい旅の形。</h3>
        <div id="searchBox">
            <div id="searchBoxInner">
                <form>
                    <input type="text" value="どちらにおでかけですか？　ex.箱根温泉、札幌、ニセコ" class="focus" /><input type="text" value="日付を選択" id="datepicker" /><input type="image" src="images/btn_search.png" align="top" alt="検索" class="btnSearch" />
                </form>
            </div>
        </div>
    </div>
    <div id="slider">
        <!-- 画像01 -->
        <div class="boxPhoto photo01">
            <div class="boxInner">
                <div class="minWeather cf">
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="東京" class="icon" /></div>
                        <ul>
                            <li class="date">10月20日</li>
                            <li class="city">東京</li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="晴れのち曇り" class="icon" /></div>
                        <ul>
                            <li class="date">10月21日</li>
                            <li class="city">東京</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 画像02 -->
        <div class="boxPhoto photo02">
            <div class="boxInner">
                <div class="minWeather cf">
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="札幌" class="icon" /></div>
                        <ul>
                            <li class="date">10月20日</li>
                            <li class="city">札幌</li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="晴れのち曇り" class="icon" /></div>
                        <ul>
                            <li class="date">10月21日</li>
                            <li class="city">札幌</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 画像03 -->
        <div class="boxPhoto photo03">
            <div class="boxInner">
                <div class="minWeather cf">
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="大阪" class="icon" /></div>
                        <ul>
                            <li class="date">10月20日</li>
                            <li class="city">大阪</li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="晴れのち曇り" class="icon" /></div>
                        <ul>
                            <li class="date">10月21日</li>
                            <li class="city">東京</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 画像05 -->
        <div class="boxPhoto photo04">
            <div class="boxInner">
                <div class="minWeather cf">
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="大阪" class="icon" /></div>
                        <ul>
                            <li class="date">10月20日</li>
                            <li class="city">大阪</li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="晴れのち曇り" class="icon" /></div>
                        <ul>
                            <li class="date">10月21日</li>
                            <li class="city">東京</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- 画像05 -->
        <div class="boxPhoto photo05">
            <div class="boxInner">
                <div class="minWeather cf">
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="大阪" class="icon" /></div>
                        <ul>
                            <li class="date">10月20日</li>
                            <li class="city">大阪</li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="icon"><img src="images/icon_minWeather_01.png" alt="晴れのち曇り" class="icon" /></div>
                        <ul>
                            <li class="date">10月21日</li>
                            <li class="city">東京</li>
                        </ul>
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

        <div id="weather">
            <h2>11/26(土)～11/27(日)の天気予想 </h2>
            <table>
                <tr class="title">
                    <th class="cell01">日付</th>
                    <td class="day01"><?php echo $futures[0]->month.'/'.$futures[0]->day.get_day_of_the_week($futures[0]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="day02"><?php echo $futures[0]->month.'/'.$futures[1]->day.get_day_of_the_week($futures[1]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="day03"><?php echo $futures[0]->month.'/'.$futures[2]->day.get_day_of_the_week($futures[2]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="day04"><?php echo $futures[0]->month.'/'.$futures[3]->day.get_day_of_the_week($futures[3]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="day05"><?php echo $futures[0]->month.'/'.$futures[4]->day.get_day_of_the_week($futures[4]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="day06"><?php echo $futures[0]->month.'/'.$futures[5]->day.get_day_of_the_week($futures[5]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="day07"><?php echo $futures[0]->month.'/'.$futures[6]->day.get_day_of_the_week($futures[6]->day_of_the_week,FALSE,FALSE) ?></td>
                </tr>
                <tr>
                <th class="cell01"><?php echo $areas[$futures[0]->area_id]->area_name; ?></th>
                <?php
                    $td_number = 1;
                    $count = count($futures);
                ?>
                <?php for ($index = 0; $index < $count; $index++) : ?>
                <?php if ($index > 0 && $index != $count - 1 && $index % 7 == 0) : ?>
                    <?php $td_number = 1; ?>
                </tr>
                <tr>
                <th class="cell01"><?php echo $areas[$futures[$index]->area_id]->area_name; ?></th>
                <?php endif; ?>
                    <td class="day0<?php echo $td_number; ?>"><?php echo $futures[$index]->daytime; ?></td>
                    <?php $td_number++; ?>
                <?php endfor; ?>
            </table>
        </div>

        <div id="guide">
            <h2>おでかけチャンス！[晴]予測連休</em></h2>
            <?php foreach ($million_city_holiday_futures as $key => $chunk) : ?>
                <div class="line0<?php echo $key; ?> cf"> <!-- 上段 -->
                <?php foreach ($chunk as $million_city_holiday_future) : ?>
                    <?php
                        $from_time = mktime(0,0,0,$million_city_holiday_future->month,$million_city_holiday_future->day,$million_city_holiday_future->year);
                        //$to_time = $from_time + ($million_city_holiday_future->holiday_sequence * 86400);
                        $to_time = $from_time + 86400;
                        $from_ymd = $million_city_holiday_future->year.'-'.$million_city_holiday_future->month.'-'.$million_city_holiday_future->day;
                        $to_ymd = date("Y-m-d",$to_time);
                    ?>
                    
                    <div class="box">
                        <a href="<?php echo 'area/show/'.$million_city_holiday_future->area_id; ?>">
                        <div class="photo"><img src="images/photo_guide_01.jpg" alt="" /><div class="shadow">&nbsp;</div><span><?php echo $areas[$million_city_holiday_future->area_id]->area_name; ?></span></div>
                        <div class="icon"><img src="images/icon_weather_01.png" alt="<?php echo $million_city_holiday_future->daytime; ?>" /></div>
                        <div class="text">
                            <div class="date">
                            <?php echo $million_city_holiday_future->month.'/'.$million_city_holiday_future->day; ?><?php echo get_day_of_the_week($million_city_holiday_future->day_of_the_week,array_key_exists($from_ymd,$holidays),TRUE); ?>
                            ～
                            <?php echo date("n/j",$to_time) ?><?php echo get_day_of_the_week(date("N",$to_time),array_key_exists($to_ymd,$holidays),TRUE); ?>
                            </div>
                            <div class="catch"><?php echo $million_city_holiday_future->holiday_sequence; ?>日連続晴れ予想</div>
                        </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div class="allGuideBtn"><a href="#">全エリアの[晴]予測連休</a></div>


            <div id="outing">
            <h2>晴れの日おでかけプランナー</h2>
            <div class="line01 cf">
                <!-- ガイド01 -->
                <div class="box">
                    <a href="#"><div class="photo"><img src="images/photo_outing_01.jpg" alt="" /><span>温泉</span></div></a>
                </div>
                <!-- ガイド02 -->
                <div class="box">
                    <a href="#"><div class="photo"><img src="images/photo_outing_02.jpg" alt="" /><span>ゴルフ場</span></div></a>
                </div>
                <!-- ガイド03 -->
                <div class="box">
                    <a href="#"><div class="photo"><img src="images/photo_outing_03.jpg" alt="" /><span>空港</span></div></a>
                </div>
                <!-- ガイド04 -->
                <div class="box">
                    <a href="#"><div class="photo"><img src="images/photo_outing_04.jpg" alt="" /><span>レジャー・行楽地</span></div></a>
                </div>
                <!-- ガイド05 -->
                <div class="box">
                    <a href="#"><div class="photo"><img src="images/photo_outing_05.jpg" alt="" /><span>スキー・スノーボード</span></div></a>
                </div>
                <!-- ガイド06 -->
                <div class="box">
                    <a href="#"><div class="photo"><img src="images/photo_outing_06.jpg" alt="" /><span>マリン</span></div></a>
                </div>
            </div>
            
            
            <div class="howtoBox cf">
                <h3><span>ハレコの使い方</span></h3>
                
                <div class="step step01">
                    <h4>1.お出かけ場所を決める</h4>
                    <p>テキストテキストテキストテキストテキストテキストテキストテキストテキスト。</p>
                </div>
                <div class="step step02">
                    <h4>2.お出かけ場所を決める</h4>
                    <p>テキストテキストテキストテキストテキストテキストテキストテキストテキスト。</p>
                </div>
                <div class="step step03">
                    <h4>3.お出かけ場所を決める</h4>
                    <p>テキストテキストテキストテキストテキストテキストテキストテキストテキスト。</p>
                </div>
                <div class="step step04">
                    <h4>4.お出かけ場所を決める</h4>
                    <p>テキストテキストテキストテキストテキストテキストテキストテキストテキスト。</p>
                </div>
                
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
