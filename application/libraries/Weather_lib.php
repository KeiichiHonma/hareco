<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Weather_lib
{
    function __construct()
    {
        $this->ci =& get_instance();
    }

    function isShine($weather_result,$precipitation_one_hour)
    {
        //雨（1時間に1ミリ以上）じゃない
        if(!$this->isRain($precipitation_one_hour)){
            return $this->isStringShine($weather_result);
        }
        return FALSE;
    }

    function isRain($precipitation_one_hour)
    {
        //どれだけの確率で雨（1時間に1ミリ以上）が降ったかを算出した物が降水確率です。
        return is_numeric($precipitation_one_hour) && $precipitation_one_hour >= 1 ? TRUE : FALSE;
    }

    function isSnow($snowfall)
    {
        //雪が1cm降雪したら
        return is_numeric($snowfall) && $snowfall >= 1 ? TRUE : FALSE;
    }
    
    //文字列だけでの天気判定
    function isStringShine($weather_string){
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
            return TRUE;//快晴があったら
        }

        if( ($m2 || $m3) && !$pat11 && !$pat12 && !$m4 && !$m5 && $pat7 && !$m8 && !$m9 && !$m10 ) {
          return TRUE;//最初に出現する天気が「晴」又は「後晴れ」があり且つ、「雨」で始まらない、「大雨」「後雨」「後霧雨」「みぞれ」「あられ」「雹」「雷」「雪」がないこと
        }
        
        if( ($m2 || $m3) && $pat7 ) {
          return TRUE;//最初に出現する天気が「晴」又は「後晴れ」があり且つ、「雪」を含む場合。雪は晴れでOK
        }
        return FALSE;
    }

    //文字列だけでの天気判定
    function isStringSnow($weather_string){
        $pat7='/雪/u';//雪を含む
        $m7 = preg_match($pat7, $weather_string);

        if($m7) {
            return TRUE;
        }
        return FALSE;
    }

    function isCorrect($result,$future){
        if($result == '降水なし') $result = '晴';
        if($result == '降水あり') $result = '雨';
        if($future == '降水なし') $result = '晴';
        if($future == '降水あり') $result = '雨';

        $result_one = $this->changeWeatherString(mb_substr($result, 0, 2));
        $future_one = $this->changeWeatherString(mb_substr($future, 0, 2));

        if($result_one == $future_one) return TRUE;
        //一時を含む場合
        if(preg_match("/一時/u", $result)){
            $pat_noti_1 = '一時'.$future_one;
            if(preg_match("/$pat_noti_1/u", $result)) return TRUE;
            if($future_one == '晴'){
                if(preg_match("/快晴/u", $result)) return TRUE;
            }elseif ($future_one == '雨'){
                if(preg_match("/一時大雨/u", $result) || preg_match("/一時霧雨/u", $result)) return TRUE;
            }elseif ($future_one == '雪'){
                if(preg_match("/一時大雪/u", $result) || preg_match("/一時みぞれ/u", $result) || preg_match("/一時あられ/u", $result)) return TRUE;
            }elseif ($future_one == '曇'){
                if(preg_match("/一時薄曇/u", $result)) return TRUE;
            }elseif ($future_one == '霧'){
                if(preg_match("/一時煙霧/u", $result)) return TRUE;
            }
        }
        
        //時々を含む場合
        if(preg_match("/時々/u", $result)){
            $pat_noti_1 = '時々'.$future_one;
            if(preg_match("/$pat_noti_1/u", $result)) return TRUE;
            if($future_one == '晴'){
                if(preg_match("/快晴/u", $result)) return TRUE;
            }elseif ($future_one == '雨'){
                if(preg_match("/時々大雨/u", $result) || preg_match("/時々霧雨/u", $result)) return TRUE;
            }elseif ($future_one == '雪'){
                if(preg_match("/時々大雪/u", $result) || preg_match("/時々みぞれ/u", $result) || preg_match("/時々あられ/u", $result)) return TRUE;
            }elseif ($future_one == '曇'){
                if(preg_match("/時々薄曇/u", $result)) return TRUE;
            }elseif ($future_one == '霧'){
                if(preg_match("/時々煙霧/u", $result)) return TRUE;
            }
        }
        
        //後を含む場合
        if(preg_match("/後/u", $result)){
            $pat_noti_1 = '後'.$future_one;
            $pat_noti_2 = '後時々'.$future_one;
            $pat_noti_3 = '後一時'.$future_one;
            if(preg_match("/$pat_noti_1/u", $result) || preg_match("/$pat_noti_2/u", $result) || preg_match("/$pat_noti_3/u", $result)) return TRUE;
            if($future_one == '晴'){
                if(preg_match("/後快晴/u", $result)) return TRUE;
            }elseif ($future_one == '雨'){
                if(preg_match("/後大雨/u", $result) || preg_match("/後霧雨/u", $result)) return TRUE;
            }elseif ($future_one == '雪'){
                if(preg_match("/後大雪/u", $result) || preg_match("/後みぞれ/u", $result) || preg_match("/後あられ/u", $result)) return TRUE;
            }elseif ($future_one == '曇'){
                if(preg_match("/後薄曇/u", $result)) return TRUE;
            }elseif ($future_one == '霧'){
                if(preg_match("/後煙霧/u", $result)) return TRUE;
            }
        }

        
        return FALSE;
    }
    
    private function changeWeatherString($string){
        //2文字以上の天気 快晴,薄曇,煙霧,砂塵嵐,地吹雪,霧雨,みぞれ,あられ,降水なし,降水あり
        if($string == '快晴'){
            return '晴';
        }elseif ($string == '大雨'){
            return '雨';
        }elseif ($string == '薄曇'){
            return '曇';
        }elseif ($string == '煙霧'){
            return '霧';
        }elseif ($string == '霧雨'){
            return '雨';
        }elseif ($string == '大雪'){
            return '雪';
        }elseif ($string == 'みぞれ'){
            return '雪';
        }elseif ($string == 'あられ'){
            return '雪';
        }
        return mb_substr($string, 0, 1);
    }

    public function changeWeatherHeadString($string){
        if($string == '降水なし') return '晴';
        if($string == '降水あり') return '雨';
        
        //2文字以上の天気 快晴,薄曇,煙霧,砂塵嵐,地吹雪,霧雨,みぞれ,あられ,降水なし,降水あり
        if($string == '快晴'){
            return '晴';
        }elseif ($string == '大雨'){
            return '雨';
        }elseif ($string == '薄曇'){
            return '曇';
        }elseif ($string == '煙霧'){
            return '霧';
        }elseif ($string == '霧雨'){
            return '雨';
        }elseif ($string == '大雪'){
            return '雪';
        }elseif ($string == 'みぞれ'){
            return '雪';
        }elseif ($string == 'あられ'){
            return '雪';
        }
        return mb_substr($string, 0, 1);
    }

    function getFutureWeather($month_day_weathers,$year = 1967){
        $is_rains = array();
        $is_snows = array();
        foreach ($month_day_weathers as $month_day_weather){
            if($month_day_weather->year >= $year){
                $daytimes[] = $month_day_weather->daytime;
                $nights[] = $month_day_weather->night;
                $temperature_maxes[] = $month_day_weather->temperature_max;
                $temperature_mins[] = $month_day_weather->temperature_min;
                if($month_day_weather->is_rain == 0) $is_rains[] = 0;
                if($month_day_weather->is_snow == 0) $is_snows[] = 0;
            }
        }
        if(!isset($temperature_mins)){
var_dump($month_day_weathers);
die();
        }
        //昼の天気予想
        $array_count = array_count_values($daytimes);
        arsort($array_count);

        $f = array_slice($array_count, 0, 1);
        $s = array_slice($array_count, 1, 2);
        
        $daytime_future_weather = key($f);
        $daytime_future_weather_number = reset($f);

        $daytime_future_weather_second = key($s);
        $daytime_future_weather_second_number = reset($s);

        if($daytime_future_weather == '降水なし'){
            $futureData['daytime'] = '晴';
        }elseif($daytime_future_weather == '降水あり'){
            $futureData['daytime'] = '雨';
        }else{
            $futureData['daytime'] = $daytime_future_weather;
        }
        $futureData['daytime_number'] = $daytime_future_weather_number;
        $futureData['daytime_second'] = $daytime_future_weather_second;
        $futureData['daytime_second_number'] = $daytime_future_weather_second_number;
        $futureData['is_daytime_shine'] = $this->isShine($daytime_future_weather,0) ? 0 : 1;

        //夜の天気予報
        $array_count = array_count_values($nights);
        arsort($array_count);
        $f = array_slice($array_count, 0, 1);
        $s = array_slice($array_count, 1, 2);
        
        $night_future_weather = key($f);
        $night_future_weather_number = reset($f);
        
        $night_future_weather_second = key($s);
        $night_future_weather_second_number = reset($s);
        
        if($night_future_weather == '降水なし'){
            $futureData['night'] = '晴';
        }elseif($night_future_weather == '降水あり'){
            $futureData['night'] = '雨';
        }else{
            $futureData['night'] = $night_future_weather;
        }
        $futureData['night_number'] = $night_future_weather_number;
        $futureData['night_second'] = $night_future_weather_second;
        $futureData['night_second_number'] = $night_future_weather_second_number;
        $futureData['is_night_shine'] = $this->isShine($night_future_weather,0) ? 0 : 1;
        
        //最高気温
        $futureData['temperature_max'] = round(array_sum($temperature_maxes) / count($temperature_maxes));
        
        //最低気温
        $futureData['temperature_min'] = round(array_sum($temperature_mins) / count($temperature_mins));
        
        $weather_sample_count = date("Y",time()) - $this->ci->config->item('jma_weather_start_year');
        //降水確率
        $futureData['rain_percentage'] = round(count($is_rains) / $weather_sample_count * 100);

        //降雪確率
        $futureData['snow_percentage'] = round(count($is_snows) / $weather_sample_count * 100);
        
        //雪
        $futureData['is_daytime_snow'] = $this->isStringSnow($daytime_future_weather,0) && $futureData['snow_percentage'] >= 60  ? 0 : 1;
        $futureData['is_night_snow'] = $this->isStringSnow($night_future_weather,0) && $futureData['snow_percentage'] >= 60  ? 0 : 1;

        return $futureData;
    }

    function get_holidays_this_month($year){
        $holidays_url = sprintf(
            'http://www.google.com/calendar/feeds/%s/public/full?alt=json&%s&%s',
            'japanese__ja%40holiday.calendar.google.com',
            'start-min='.$year.'-01-01',
            'start-max='.$year.'-12-31'
        );
        if ( $results = file_get_contents($holidays_url) ) {
                $results = json_decode($results, true);
                $holidays = array();
                foreach ($results['feed']['entry'] as $val ) {
                        $date  = $val['gd$when'][0]['startTime'];
                        $week = date('w',strtotime($date));
                        $title = $val['title']['$t'];
                        $holidays[$date] = $title;
     
                        if( $week == 0) {
                            $nextday = date('Y-m-d',strtotime('+1 day', strtotime($date)));
                            $holidays[$nextday] = '振替休日';
                        }
     
                        $before_yesterday = date('Y-m-d',strtotime('-2 day', strtotime($date)));
     
                        if(isset($holidays[$before_yesterday])){
                            $yesterday = date('Y-m-d',strtotime('-1 day', strtotime($date)));
                            $holidays[$yesterday] = '国民の休日';
                        }
     
                }
                ksort($holidays);
        }
        return $holidays;
    }

    function checkCsvRowForNumeric($column){
        if(is_numeric($column)){
            return $column;
        }elseif($column == '--'){//該当現象、または該当現象による量等がない場合に表示します。
            return 0;
        }else{
            return 9999;
        }
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */