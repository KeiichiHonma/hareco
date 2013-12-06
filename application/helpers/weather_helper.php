<?php
function get_icon ($weather_string) {
    //基本は始まる文字で選定
    $pat1='/^快晴/u';//快晴で始まる
    $pat2='/^晴/u';//晴で始まる
    $pat3='/^雨/u';//雨で始まる
    $pat4='/^雪/u';//雪で始まる
    $pat5='/^みぞれ/u';//みぞれで始まる
    $pat6='/^あられ/u';//あられで始まる
    $pat7='/^雹/u';//雹で始まる
    $pat8='/^雷/u';//雷で始まる
    $pat9='/^大雨/u';//大雨で始まる
    $pat10='/曇/u';//曇を含む
    
    
    
    
    $pat1='/快晴/u';//快晴を含む
    $pat2='/^晴/u';//晴で始まる
    $pat3='/後晴/u';//後晴を含む
    $pat4='/後雨/u';//後雨を含む
    $pat5='/後霧雨/u';//後霧雨を含む
    $pat6='/みぞれ/u';//みぞれを含む
    $pat7='/雪/u';//雪を含む
    $pat8='/あられ/u';//あられを含む
    $pat9='/雹/u';//雹を含む
    $pat10='/雷/u';//雷を含む
    $pat11='/^雨/u';//雨で始まる
    $pat12='/大雨/u';//大雨を含む

    $m1 = preg_match($pat1, $weather_string);
    $m2 = preg_match($pat2, $weather_string);
    $m3 = preg_match($pat3, $weather_string);
    $m4 = preg_match($pat4, $weather_string);
    $m5 = preg_match($pat5, $weather_string);
    $m6 = preg_match($pat6, $weather_string);
    $m7 = preg_match($pat7, $weather_string);
    $m8 = preg_match($pat8, $weather_string);
    $m9 = preg_match($pat9, $weather_string);
    $m10 = preg_match($pat10, $weather_string);

    if($m1) {
        return 'icon_weather_01.png';//快晴で始まる
    }
    
    if($m2 && $m10) {
        return 'icon_weather_01.png';//晴で始まる
    }
    
    if( ($m2 || $m3) && !$pat11 && !$pat12 && !$m4 && !$m5 && $pat7 && !$m8 && !$m9 && !$m10 ) {
      return TRUE;//最初に出現する天気が「晴」又は「後晴れ」があり且つ、「雨」で始まらない、「大雨」「後雨」「後霧雨」「みぞれ」「あられ」「雹」「雷」「雪」がないこと
    }
    
    if( ($m2 || $m3) && $pat7 ) {
      return TRUE;//最初に出現する天気が「晴」又は「後晴れ」があり且つ、「雪」を含む場合。雪は晴れでOK
    }
}
function get_day_of_the_week ($day_of_the_week,$is_public_holiday,$is_strong = FALSE) {
    $string = $is_public_holiday !== FALSE ? ' class="sun"' : '';
    switch ($day_of_the_week){
        case 1:
            return $is_strong ? '<em'.$string.'>(月)</em>' : '(月)';
        break;
        case 2:
            return $is_strong ? '<em'.$string.'>(火)</em>' : '(火)';
        break;
        case 3:
            return $is_strong ? '<em'.$string.'>(水)</em>' : '(水)';
        break;
        case 4:
            return $is_strong ? '<em'.$string.'>(木)</em>' : '(木)';
        break;
        case 5:
            return $is_strong ? '<em'.$string.'>(金)</em>' : '(金)';
        break;
        case 6:
            return $is_strong ? '<em class="sat">(土)</em>' : '(土)';
        break;
        case 7:
            return $is_strong ? '<em class="sun">(日)</em>' : '(日)';
        break;
    }
}
function get_class_day_of_the_week ($day_of_the_week) {
    switch ($day_of_the_week){
        case 6:
            return 'sat';
        break;
        case 7:
            return 'sun';
        break;
        default:
            return 'undisp';
    }
}
?>
