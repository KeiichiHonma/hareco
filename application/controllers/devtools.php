<?php
class Devtools extends CI_Controller {

    var $CI;
    function __construct(){
        parent::__construct();
        $this->CI =& get_instance();
        if ( ! $this->input->is_cli_request() )     {
            //die('Permission denied.');
        }

        $this->load->library('tank_auth');
        $this->load->library('weather_lib');
        //connect database
        $this->load->database();
    }
    
    private $handleTagList = array('td');
    private $html = '';
    private $csv_data = array();
    
    //dev exe///////////////////////////////////////////////////////////////////////////////////////////
    //過去データ取り込み
    function importWeatherForDesignated(){
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        $this->load->model('Weather_model');

        $dir = '/usr/local/apache2/htdocs/mirai_tenki/csv/';
        $dh = opendir($dir);

        $csvs = array();
        while(false !== ($fn = readdir($dh))){
            if($fn !== '.' && $fn !== '..' && !is_dir($dir.$fn)){
                array_push($csvs, $fn);
            }
        }
        closedir($dh);
        asort($csvs);
        foreach ($csvs as $csv_file){
            //$csv_file = '47401data.csv';
            $ex_filename = explode('data',$csv_file);//47401data.csv
            $jma_block_no = $ex_filename[0];
            $area = $areas[$jma_block_no];
            $fp=@fopen($dir.$csv_file,"r");
            $line = 0;
            $weatherData = array();
            print date("y/n/d/H:i:s")."\n";
            while ($CSVRow = @$this->_fgetcsv_reg($fp,1024)){//ファイルを一行ずつ読み込む
                $ymd = explode('/',$CSVRow[0]);
                $weatherData[$line]['code'] = $area->region_id.'_'.$area->todoufuken_id.'_'.$area->id.'_'.$jma_block_no.'_'.$ymd[0].'_'.$ymd[1].'_'.$ymd[2];
                $weatherData[$line]['date'] = $ymd[0].'-'.$ymd[1].'-'.$ymd[2];
                $weatherData[$line]['year'] = $ymd[0];
                $weatherData[$line]['month'] = $ymd[1];
                $weatherData[$line]['day'] = $ymd[2];
                
                $weatherData[$line]['region_id'] = $area->region_id;
                $weatherData[$line]['todoufuken_id'] = $area->todoufuken_id;
                $weatherData[$line]['area_id'] = $area->id;
                $weatherData[$line]['jma_prec_no'] = $area->jma_prec_no;
                $weatherData[$line]['jma_block_no'] = $area->jma_block_no;
                
                //天気判定///////////////////////////////////////////////////////////////////////////////////////////////
                $weatherData[$line]['is_summary'] = 0;
                //雨（1時間に1ミリ以上）じゃなくて、最初に出現する天気が「晴」又は「快晴」がある又は「後晴れ」があり且つ、「後雨」「後霧雨」がないこと且つ、「みぞれ」「雪」「あられ」「雹」「雷」がないこと
                $weatherData[$line]['is_daytime_shine'] = $this->weather_lib->isShine($CSVRow[19],$CSVRow[4]) ? 0 : 1;
                $weatherData[$line]['is_night_shine'] = $this->weather_lib->isShine($CSVRow[20],$CSVRow[4]) ? 0 : 1;
                
                //どれだけの確率で雨（1時間に1ミリ以上）が降ったかを算出した物が降水確率です。
                $weatherData[$line]['is_rain'] = $this->weather_lib->isRain($CSVRow[4]) ? 0 : 1;

                //雪が1cm降雪したら
                $weatherData[$line]['is_snow'] = $this->weather_lib->isSnow($CSVRow[17]) ? 0 : 1;
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
                
                $weatherData[$line]['atmosphere_spot'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[1]);
                $weatherData[$line]['atmosphere_ocean'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[2]);
                $weatherData[$line]['precipitation_total'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[3]);
                $weatherData[$line]['precipitation_one_hour'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[4]);
                $weatherData[$line]['precipitation_ten_minute'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[5]);
                $weatherData[$line]['temperature_average'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[6]);
                $weatherData[$line]['temperature_max'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[7]);
                $weatherData[$line]['temperature_min'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[8]);
                $weatherData[$line]['moisture_average'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[9]);
                $weatherData[$line]['moisture_min'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[10]);
                $weatherData[$line]['wind_speed_average'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[11]);
                $weatherData[$line]['wind_speed_max'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[12]);
                $weatherData[$line]['wind_speed_max_direction'] = mb_convert_encoding($CSVRow[13], "UTF-8","SJIS");
                $weatherData[$line]['instant_wind_speed_max'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[14]);
                $weatherData[$line]['instant_wind_speed_max_direction'] = mb_convert_encoding($CSVRow[15], "UTF-8","SJIS");
                $weatherData[$line]['sunshine_hours'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[16]);
                $weatherData[$line]['snowfall'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[17]);
                $weatherData[$line]['snow_deepest'] = $this->weather_lib->checkCsvRowForNumeric($CSVRow[18]);
                $weatherData[$line]['daytime'] = mb_convert_encoding($CSVRow[19], "UTF-8","SJIS");
                $weatherData[$line]['night'] = mb_convert_encoding($CSVRow[20], "UTF-8","SJIS");
                $weatherData[$line]['created'] = date("Y-m-d H:i:s", time());
                //$this->Weather_model->insertWeather($weatherData);
                $line++;
            }
            fclose( $fp );
            $this->Weather_model->insertBatchWeather($weatherData);
            if ( copy ($dir.$csv_file, $dir.'finish/'.$csv_file) ){
                unlink($dir.$csv_file);
                print 'done:'.$csv_file."\n";
            }
            print date("y/n/d/H:i:s")."\n";
        }
    }
    
    private $update_datas = array(
        array('2_4_11_47584_2000_1_11','2000','1','11','33','47584'),
        array('2_4_11_47584_2000_1_12','2000','1','12','33','47584'),
        array('3_8_23_47629_2011_8_13','2011','8','13','40','47629'),
        array('3_8_23_47629_2011_8_14','2011','8','14','40','47629'),
    );
    
    //過去データインポート更新
    function importUpdateWeatherForDesignated(){
        $this->load->model('Area_model');
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        
        include('application/libraries/simple_html_dom.php');
        
        $ret = FALSE;
        unset($this->html);
        $this->html = '';
        $time = time();
        $weatherData = array();
        
        foreach ($this->update_datas as $update_data){
            $year = $update_data[1];
            $month = $update_data[2];
            $day = $update_data[3];
            $jma_prec_no = $update_data[4];
            $jma_block_no = $update_data[5];

            $url = 'http://www.data.jma.go.jp/obd/stats/etrn/view/daily_s1.php?prec_no='.$jma_prec_no.'&block_no='.$jma_block_no.'&year='.$year.'&month='.$month.'&day=&view=';//各地点の年月日ごとの詳細気象データ
            $this->html = file_get_html($url);

            foreach ($this->handleTagList as $tag){
                foreach($this->html->find($tag) as $key => $element){
                    switch ($tag){
                        case 'td':
                            if(FALSE !== strstr($element->outertext,'data_0_0') || FALSE !== strstr($element->outertext,'data_1t_0') || FALSE !== strstr($element->outertext,'data_0_1b')){
                                if($element->innertext == '') $element->innertext = 'null';
                                $string = str_replace(array('&nbsp;',')',']'),array('','',''),$element->innertext);
                                $this->csv_data['data'][$year.'/'.$month][] = trim($string);
                            }
                            
                        break;

                    }
                }
            }
            $count = 20;//項目数
            $result = array();
            $target_index = $day - 1;
            $update_jma_data = array();
            foreach ($this->csv_data['data'] as $year_month => $array){
                $array_chunk = array_chunk($array,$count);
                $update_jma_data = $array_chunk[$target_index];
            }

            $this->html->clear();
            $this->csv_data['data'] = array();

            $weatherData['code'] = $areas[$jma_block_no]->region_id.'_'.$areas[$jma_block_no]->todoufuken_id.'_'.$areas[$jma_block_no]->id.'_'.$jma_block_no.'_'.$year.'_'.$month.'_'.$day;
            $weatherData['date'] = $year.'-'.$month.'-'.$day;
            $weatherData['year'] = $year;
            $weatherData['month'] = $month;
            $weatherData['day'] = $day;
            
            $weatherData['region_id'] = $areas[$jma_block_no]->region_id;
            $weatherData['todoufuken_id'] = $areas[$jma_block_no]->todoufuken_id;
            $weatherData['area_id'] = $areas[$jma_block_no]->id;
            $weatherData['jma_prec_no'] = $areas[$jma_block_no]->jma_prec_no;
            $weatherData['jma_block_no'] = $areas[$jma_block_no]->jma_block_no;
            
            //天気判定///////////////////////////////////////////////////////////////////////////////////////////////
            $weatherData['is_summary'] = 0;
            //雨（1時間に1ミリ以上）じゃなくて、最初に出現する天気が「晴」又は「快晴」がある又は「後晴れ」があり且つ、「後雨」「後霧雨」がないこと且つ、「みぞれ」「雪」「あられ」「雹」「雷」がないこと
            $weatherData['is_daytime_shine'] = $this->weather_lib->isShine($update_jma_data[18],$update_jma_data[3]) ? 0 : 1;
            $weatherData['is_night_shine'] = $this->weather_lib->isShine($update_jma_data[19],$update_jma_data[3]) ? 0 : 1;
            
            //どれだけの確率で雨（1時間に1ミリ以上）が降ったかを算出した物が降水確率です。
            $weatherData['is_rain'] = $this->weather_lib->isRain($update_jma_data[3]) ? 0 : 1;

            //雪が1cm降雪したら
            $weatherData['is_snow'] = $this->weather_lib->isSnow($update_jma_data[16]) ? 0 : 1;
            /////////////////////////////////////////////////////////////////////////////////////////////////////////
            
            $weatherData['atmosphere_spot'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[0]);
            $weatherData['atmosphere_ocean'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[1]);
            $weatherData['precipitation_total'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[2]);
            $weatherData['precipitation_one_hour'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[3]);
            $weatherData['precipitation_ten_minute'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[4]);
            $weatherData['temperature_average'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[5]);
            $weatherData['temperature_max'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[6]);
            $weatherData['temperature_min'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[7]);
            $weatherData['moisture_average'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[8]);
            $weatherData['moisture_min'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[9]);
            $weatherData['wind_speed_average'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[10]);
            $weatherData['wind_speed_max'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[11]);
            $weatherData['wind_speed_max_direction'] = $update_jma_data[12];
            $weatherData['instant_wind_speed_max'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[13]);
            $weatherData['instant_wind_speed_max_direction'] = $update_jma_data[14];
            $weatherData['sunshine_hours'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[15]);
            $weatherData['snowfall'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[16]);
            $weatherData['snow_deepest'] = $this->weather_lib->checkCsvRowForNumeric($update_jma_data[17]);
            $weatherData['daytime'] = $update_jma_data[18];
            $weatherData['night'] = $update_jma_data[19];
            $weatherData['created'] = date("Y-m-d H:i:s", time());
            $this->Weather_model->updateWeather($areas[$jma_block_no]->id,$year,$month,$day,$weatherData);
            print $update_data[0]."\n";
        }
    }
    
    //過去データ更新
    function updateWeatherForDesignated(){
        $this->load->model('Area_model');
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        /*
        下記を更新
        ・晴れの定義を変えた
        ・昨日のデータも取り入れる
        yesterday_night
        
        is_daytime_shine
        is_night_shine
        is_yesterday_rain
        is_yesterday_snow
        
        is_yesterday_night_shine
        is_yesterday_night_snow
        */
        foreach ($areas as $area){
            if($area->id >= 50){
                print date("y/n/d/H:i:s")."\n";
                $weathers = $this->Weather_model->getWeathersOrderByAreaId($area->id);
                $yesterday_weather = array('yesterday_night'=>'','is_yesterday_night_shine'=>9,'is_yesterday_rain'=>'9','is_yesterday_snow'=>9);
                //1967/1/1からのループ
                foreach ($weathers as $weather){
                    $data = array();
                    $data['yesterday_night'] = $yesterday_weather['yesterday_night'];
                    $data['is_daytime_shine'] = $this->weather_lib->isShine($weather->daytime,$weather->precipitation_one_hour) ? 0 : 1;
                    $data['is_night_shine'] = $this->weather_lib->isShine($weather->night,$weather->precipitation_one_hour) ? 0 : 1;
                    $data['is_yesterday_night_shine'] = $yesterday_weather['is_yesterday_night_shine'];
                    $data['is_rain'] = $this->weather_lib->isRain($weather->precipitation_one_hour) ? 0 : 1;
                    $data['is_snow'] = $this->weather_lib->isSnow($weather->snowfall) ? 0 : 1;
                    
                    $data['is_yesterday_rain'] = $yesterday_weather['is_yesterday_rain'];
                    $data['is_yesterday_snow'] = $yesterday_weather['is_yesterday_snow'];
                    $yesterday_weather = array('yesterday_night'=>$weather->night,'is_yesterday_night_shine'=>$data['is_night_shine'],'is_yesterday_rain'=>$data['is_rain'],'is_yesterday_snow'=>$data['is_snow']);
                    //$yesterday_weather = array('is_yesterday_rain'=>$data['is_rain'],'is_yesterday_snow'=>$data['is_snow']);
                    //update
                    $this->db->where('id', $weather->id);
                    $this->db->update('weathers', $data);
                }
                echo $area->id.'-'.$area->area_name."\n";
                print date("y/n/d/H:i:s")."\n";
            }
        }
        
    }
    
    //未来データ生成
    function createFutureForDesignated($is_update = FALSE){
        $time = time();
        $this->load->model('Area_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        
        //全area,指定日を実行
        $target_year = 2013;
        $target_month = 12;
        $end_day = 16;//14時前はマイナス1
        $sampling_year = $this->CI->config->item('jma_weather_start_year');//1993 or 1967
        
        $holidays = $this->weather_lib->get_holidays_this_month($target_year);
        print date("y/n/d/H:i:s")."\n";
        foreach ($areas as $area){
            $yesterday_batch_index = FALSE;
            for ($year=$target_year;$year<=2014;$year++){
                //$yesterday_weather = array('daytime'=>'','daytime_icon_image'=>'','daytime_number'=>0,'is_daytime_shine'=>0,'is_daytime_snow'=>0,'night'=>'','night_icon_image'=>'','night_number'=>0,'is_night_shine'=>0,'is_night_snow'=>0);
                $yesterday_weather = array('daytime'=>'','daytime_icon_image'=>'','daytime_number'=>0,'daytime_type'=>9,'night'=>'','night_icon_image'=>'','night_number'=>0,'night_type'=>9);
                for($month=1;$month<=$target_month;$month++){
                    $today_month = $month;
                    $lastday = date("t", mktime(0,0,0,$month,1,$year));
                    $month_string = $month < 10 ? '0'.$month : $month;
                    for($day=1;$day <= $lastday;$day++){
                        if($year == 2014 && $month == 12 && $day == $end_day ) break;
                        $day_string = $day < 10 ? '0'.$day : $day;
                        $today_day = $day;
                        
                        //想定前日の日付を取得
                        if($month == 1 && $day == 1){
                            $yesterday_year = $year - 2;
                            $yesterday_month = 12;
                            $yesterday_day = 31;
                        }elseif($day == 1){
                            $yesterday_year = $year -1;
                            $yesterday_month = $month - 1;
                            $yesterday_day = date("t", mktime(0,0,0,$yesterday_month,1,$yesterday_year));
                        }else{
                            $yesterday_year = $year -1;
                            $yesterday_month = $month;
                            $yesterday_day = $day - 1;
                        }

                        //前日の実際の結果を取得
                        $real_yesterday_weather = $this->Weather_model->getWeatherByAreaIdByYearByMonthByDay($area->id,$yesterday_year,$yesterday_month,$yesterday_day);
                        $real_yesterday_night = $real_yesterday_weather->night;//昨日の夜の天気
                        
                        //先頭の天気文字で始まる過去データを使用する
                        $head = $this->weather_lib->changeWeatherHeadString($real_yesterday_night);
                        $month_day_weathers = $this->Weather_model->getWeatherByAreaIdByHeadByMonthByDay($area->id,$head,$today_month,$today_day,$sampling_year);

                        //空の場合、サンプリングの数が5つに満たない場合は全体の統計予測で
                        if(empty($month_day_weathers) || count($month_day_weathers) < 3){
                            $month_day_weathers = $this->Weather_model->getWeatherByAreaIdByMonthByDay($area->id,$today_month,$today_day);
                        }
                        //未来データ生成
                        if(!$is_update){
                            $batch_index = $area->id.$year.'-'.$month.'-'.$day;
                            $weather = $this->weather_lib->getFutureWeather($month_day_weathers,$sampling_year);//1993 or 1967
                            $futureData[$batch_index] = $weather;

                            //tomorrow///////////////////////////////////////
                            //daytimne
                            if($yesterday_batch_index !== FALSE){
                                $futureData[$yesterday_batch_index]['tomorrow_daytime'] = $weather['daytime'];
                                $futureData[$yesterday_batch_index]['tomorrow_daytime_icon_image'] = $weather['daytime_icon_image'];
                                $futureData[$yesterday_batch_index]['tomorrow_daytime_number'] = $weather['night_number'];
                                $futureData[$yesterday_batch_index]['tomorrow_daytime_type'] = $weather['daytime_type'];
                            }
                            $yesterday_batch_index = $batch_index;//次のために

                            //yesterday////////////////////////////////
                            //daytimne
                            $futureData[$batch_index]['yesterday_daytime'] = $yesterday_weather['daytime'];
                            $futureData[$batch_index]['yesterday_daytime_icon_image'] = $yesterday_weather['daytime_icon_image'];
                            $futureData[$batch_index]['yesterday_daytime_number'] = $yesterday_weather['daytime_number'];
                            $futureData[$batch_index]['yesterday_daytime_type'] = $yesterday_weather['daytime_type'];
                            
                            //night
                            $futureData[$batch_index]['yesterday_night'] = $yesterday_weather['night'];
                            $futureData[$batch_index]['yesterday_night_icon_image'] = $yesterday_weather['night_icon_image'];
                            $futureData[$batch_index]['yesterday_night_number'] = $yesterday_weather['night_number'];
                            $futureData[$batch_index]['yesterday_night_type'] = $yesterday_weather['night_type'];
                            $yesterday_weather = $weather;//次のために
                            
                            
                            $futureData[$batch_index]['code'] = $area->region_id.'_'.$area->todoufuken_id.'_'.$area->id.'_'.$area->jma_block_no.'_'.$year.'_'.$today_month.'_'.$today_day;
                            $futureData[$batch_index]['region_id'] = $area->region_id;
                            $futureData[$batch_index]['todoufuken_id'] = $area->todoufuken_id;
                            $futureData[$batch_index]['area_id'] = $area->id;
                            $futureData[$batch_index]['jma_prec_no'] = $area->jma_prec_no;
                            $futureData[$batch_index]['jma_block_no'] = $area->jma_block_no;
                            $futureData[$batch_index]['year'] = $year;
                            $futureData[$batch_index]['month'] = $today_month;
                            $futureData[$batch_index]['day'] = $today_day;
                            $futureData[$batch_index]['date'] = $futureData[$batch_index]['year'].'-'.$futureData[$batch_index]['month'].'-'.$futureData[$batch_index]['day'];

                            //初期化用tomorrow///////////////////////////////////////
                            //daytimne
                            $futureData[$batch_index]['tomorrow_daytime'] = '';
                            $futureData[$batch_index]['tomorrow_daytime_icon_image'] = '';
                            $futureData[$batch_index]['tomorrow_daytime_number'] = 0;
                            $futureData[$batch_index]['tomorrow_daytime_type'] = 9;

                            //holiday
                            $futureData[$batch_index]['day_of_the_week'] = date("N",mktime(0,0,0,$month,$day,$year));// 1（月曜日）から 7（日曜日）
                            if(array_key_exists($year.'-'.$month_string.'-'.$day_string,$holidays)){
                                $futureData[$batch_index]['holiday'] = 2;//1土日 2祝日
                            }elseif ($futureData[$batch_index]['day_of_the_week'] == 6 || $futureData[$batch_index]['day_of_the_week'] == 7){
                                $futureData[$batch_index]['holiday'] = 1;//1土日 2祝日
                            }else{
                                $futureData[$batch_index]['holiday'] = 0;
                            }

                            $futureData[$batch_index]['created'] = date("Y-m-d H:i:s", $time);
                            //ここで正答チェックしてしまう
                            $futureData[$batch_index]['is_correct'] = 9;
                            if($year == 2013 && $today_month <= 11 ){
                                if($today_month == 11 && $today_day >= 14){
                                
                                }else{
                                    //指定年月日の過去データ取得
                                    $year_month_day_weather = $this->Weather_model->getWeatherByAreaIdByYearByMonthByDay($area->id,$year,$today_month,$today_day);
                                    //昼の天気で正答確認
                                    $futureData[$batch_index]['is_correct'] =  $this->weather_lib->isCorrect($futureData[$batch_index]['daytime'],$year_month_day_weather->daytime) ? 0 : 1;
                                }
                            }
                        }else{
                            //update
                            $futureData = $this->weather_lib->getFutureWeather($month_day_weathers);
                            
                            $futureData['code'] = $area->region_id.'_'.$area->todoufuken_id.'_'.$area->id.'_'.$area->jma_block_no.'_'.$year.'_'.$today_month.'_'.$today_day;
                            $futureData['region_id'] = $area->region_id;
                            $futureData['todoufuken_id'] = $area->todoufuken_id;
                            $futureData['area_id'] = $area->id;
                            $futureData['jma_prec_no'] = $area->jma_prec_no;
                            $futureData['jma_block_no'] = $area->jma_block_no;
                            
                            $futureData['year'] = $year;
                            $futureData['month'] = $today_month;
                            $futureData['day'] = $today_day;
                            $futureData['date'] = $futureData['year'].'-'.$futureData['month'].'-'.$futureData['day'];
                            $futureData['created'] = date("Y-m-d H:i:s", $time);
                            
                            $this->Future_model->updateFuture($area->id,$year,$month,$day,$futureData);
                            $futureData = array();
                            print $area->area_name.$year.'-'.$month.'-'.$day."\n";
                        }
                    }
                }

            }
            if(!$is_update){
                $this->Future_model->insertBatchFuture($futureData);
                $futureData = array();
                print $area->area_name."\n";
            }
        }
        print date("y/n/d/H:i:s")."\n";
    }

    function updateCorrectForDesignated(){
        $time = time();
        $this->load->model('Area_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        
        $year = 2013;
        $target_month = 12;
        $end_day = 10;
        foreach ($areas as $area){
            for($month=1;$month<=$target_month;$month++){
                $today_month = $month;
                $lastday = date("t", mktime(0,0,0,$month,1,$year));
                
                for($day=1;$day <= $lastday;$day++){
                    if($year == 2013 && $month == 12 && $day == $end_day ) break;
                    $today_day = $day;
                    //指定年月日の過去データ取得
                    $year_month_day_weather = $this->Weather_model->getWeatherByAreaIdByYearByMonthByDay($area->id,$year,$today_month,$today_day);
                    if(!isset($year_month_day_weather->daytime)){
                        echo 'weather-'.$area->id.'-'.$year.'-'.$today_month.'-'.$today_day."\n";
                        die();
                    }
                    //指定年月日の未来データ取得
                    $year_month_day_future = $this->Future_model->getFutureByAreaIdByYearByMonthByDay($area->id,$year,$today_month,$today_day);
                    if(!isset($year_month_day_future->daytime)){
                        echo 'future-'.$area->id.'-'.$year.'-'.$today_month.'-'.$today_day."\n";
                        die();
                    }
                    //昼の天気で正答確認
                    $futureData['is_correct'] =  $this->weather_lib->isCorrect($year_month_day_weather->daytime,$year_month_day_future->daytime) ? 0 : 1;
                    $this->Future_model->updateFuture($area->id,$year,$today_month,$today_day,$futureData);
                }
            }
            echo 'done-'.$area->id."\n";
        }
    }
    
    //休日データ更新
    function updateDayOfTheWeekAndHolidayForDesignated(){
        $this->load->model('Future_model');
        $year = 2014;
        /*
        2013 sample
        array(15) {
          ["2013-01-01"]=>
          string(6) "元日"
          ["2013-01-14"]=>
          string(12) "成人の日"
          ["2013-02-11"]=>
          string(18) "建国記念の日"
          ["2013-04-29"]=>
          string(12) "昭和の日"
          ["2013-05-03"]=>
          string(15) "憲法記念日"
          ["2013-05-04"]=>
          string(15) "国民の休日"
          ["2013-05-05"]=>
          string(15) "こどもの日"
          ["2013-05-06"]=>
          string(12) "振替休日"
          ["2013-07-15"]=>
          string(9) "海の日"
          ["2013-09-16"]=>
          string(12) "敬老の日"
          ["2013-10-14"]=>
          string(12) "体育の日"
          ["2013-11-03"]=>
          string(12) "文化の日"
          ["2013-11-04"]=>
          string(12) "振替休日"
          ["2013-11-23"]=>
          string(18) "勤労感謝の日"
          ["2013-12-23"]=>
          string(15) "天皇誕生日"
        }
        */
        $holidays = $this->weather_lib->get_holidays_this_month($year);
        //全レコード
        for($month=1;$month<=11;$month++){
            $lastday = date("t", mktime(0,0,0,$month,1,$year));
            $month_string = $month < 10 ? '0'.$month : $month;
            for($day=1;($day <= $lastday);$day++){
                if($year == 2014 && $month == 11 && $day == 6 ) break;
                $day_string = $day < 10 ? '0'.$day : $day;
                $futureData['day_of_the_week'] = date("N",mktime(0,0,0,$month,$day,$year));// 1（月曜日）から 7（日曜日）
                if($index = array_key_exists($year.'-'.$month_string.'-'.$day_string,$holidays)){
                    $futureData['holiday'] = 2;//1土日 2祝日
                }elseif ($futureData['day_of_the_week'] == 6 || $futureData['day_of_the_week'] == 7){
                    $futureData['holiday'] = 1;//1土日 2祝日
                }else{
                    $futureData['holiday'] = 0;
                }
                $this->Future_model->updateFutureByYMD($year,$month,$day,$futureData);
                print $year.'-'.$month_string.'-'.$day_string."\n";
            }
        }
    }
    
    //連続の晴れ、連休更新
    function updateSequenceForDesignated(){
        function plus (&$int,$date){
            $int = $int+1;
        }
        $this->load->model('Area_model');
        $this->load->model('Future_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        foreach ($areas as $area){
            //$area->id = 30;
            $futures = $this->Future_model->getFutureByAreaIdOrderDate($area->id);
            $daytime_shine_date_list = array();
            $night_shine_date_list = array();
            $holiday_date_list = array();
            
            //古い日付からチェック
            foreach ($futures as $future){

                //昼の天気
                if($future->daytime_type == 0){
                    if( !array_key_exists($future->date,$daytime_shine_date_list) ){
                        $daytime_shine_date_list[$future->date] = 0;
                    }
                    array_walk($daytime_shine_date_list, "plus");
                }else{
                    //晴れじゃなかったのでupdate
                    if(!empty($daytime_shine_date_list)){
                        foreach ($daytime_shine_date_list as $date => $shine_count){
                            $futureData['daytime_shine_sequence'] =  $shine_count;
                            $this->Future_model->updateFutureByAreaIdAndDate($area->id,$date,$futureData);
                        }
                        $futureData = array();
                        $daytime_shine_date_list = array();//連続初期化
                    }
                }
                
                //夜の天気
                if($future->night_type == 0){
                    if( !array_key_exists($future->date,$night_shine_date_list) ){
                        $night_shine_date_list[$future->date] = 0;
                    }
                    array_walk($night_shine_date_list, "plus");
                }else{
                    //晴れじゃなかったのでupdate
                    if(!empty($night_shine_date_list)){
                        foreach ($night_shine_date_list as $date => $shine_count){
                            $futureData['night_shine_sequence'] =  $shine_count;
                            $this->Future_model->updateFutureByAreaIdAndDate($area->id,$date,$futureData);
                        }
                        $futureData = array();
                        $night_shine_date_list = array();//連続初期化
                    }
                }

                //連休
                if($future->holiday > 0){
                    if( !array_key_exists($future->date,$holiday_date_list) ){
                        $holiday_date_list[$future->date] = 0;
                    }
                    array_walk($holiday_date_list, "plus");
                }else{
                    if (!empty($holiday_date_list)){
                        foreach ($holiday_date_list as $date => $holiday_count){
                            $futureData['holiday_sequence'] =  $holiday_count;
                            $this->Future_model->updateFutureByAreaIdAndDate($area->id,$date,$futureData);
                        }
                        $futureData = array();
                        $holiday_date_list = array();//連続初期化
                    }
                }

            }
            //ループを抜けた後に更新
            if(!empty($daytime_shine_date_list)){
                foreach ($daytime_shine_date_list as $date => $shine_count){
                    $futureData['daytime_shine_sequence'] =  $shine_count;
                    $this->Future_model->updateFutureByAreaIdAndDate($area->id,$date,$futureData);
                    $futureData = array();
                }
                $daytime_shine_date_list = array();//連続初期化
            }
            if(!empty($night_shine_date_list)){
                foreach ($night_shine_date_list as $date => $shine_count){
                    $futureData['night_shine_sequence'] =  $shine_count;
                    $this->Future_model->updateFutureByAreaIdAndDate($area->id,$date,$futureData);
                    $futureData = array();
                }
                $night_shine_date_list = array();//連続初期化
            }
            if (!empty($holiday_date_list)){
                foreach ($holiday_date_list as $date => $holiday_count){
                    $futureData['holiday_sequence'] =  $holiday_count;
                    $this->Future_model->updateFutureByAreaIdAndDate($area->id,$date,$futureData);
                    $futureData = array();
                }
                $holiday_date_list = array();//連続初期化
            }
            echo 'done-sequence'.$area->area_name."\n";
        }
    }

    //的中率
    function updateOddsForDesignated(){
        $this->load->model('Odds_model');
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        
        $time = time();
        $regions = $this->Region_model->getAllRegions();
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        
        $year = 2013;
        $target_month = 11;
        $end_day = 27;
        //全レコード
        for($month=1;$month<=$target_month;$month++){
            $lastday = date("t", mktime(0,0,0,$month,1,$year));
            for($day=1;$day <= $lastday;$day++){
                if($year == 2013 && $month == 11 && $day == $end_day) break;
                $record_count = $this->Future_model->getFutureCountByLesserDate($year.'-'.$month.'-'.$day);
                $correct_count = $this->Future_model->getFutureCountByCorrectByLesserDate($year.'-'.$month.'-'.$day);
                if($record_count->count != 0){
                    $oddsData[$year.'-'.$month.'-'.$day]['date'] = $year.'-'.$month.'-'.$day;
                    $oddsData[$year.'-'.$month.'-'.$day]['percentage'] = round($correct_count->count / $record_count->count * 100);
                    $oddsData[$year.'-'.$month.'-'.$day]['created'] = date("Y-m-d H:i:s", $time);
                }
            }
        }
        $this->Odds_model->insertBatchOddsesOdds($oddsData);

        print 'odds end'."\n";
        $regionsOddsData = array();

        foreach ($regions as $region_id => $region){
            for($month=1;$month <= $target_month;$month++){
                $lastday = date("t", mktime(0,0,0,$month,1,$year));
                for($day=1;$day <= $lastday;$day++){
                    if($year == 2013 && $month == 11 && $day == 7) break;
                    $record_count = $this->Future_model->getFutureCountByRegionIdByLesserDate($region->id,$year.'-'.$month.'-'.$day);
                    $correct_count = $this->Future_model->getFutureCountByRegionIdByCorrectByLesserDate($region->id,$year.'-'.$month.'-'.$day);
                    if($record_count->count != 0){
                        $regionsOddsData[$region->id.'_'.$year.'_'.$month.'_'.$day]['region_id'] = $region->id;
                        $regionsOddsData[$region->id.'_'.$year.'_'.$month.'_'.$day]['date'] = $year.'-'.$month.'-'.$day;
                        $regionsOddsData[$region->id.'_'.$year.'_'.$month.'_'.$day]['percentage'] = round($correct_count->count / $record_count->count * 100);
                        $regionsOddsData[$region->id.'_'.$year.'_'.$month.'_'.$day]['created'] = date("Y-m-d H:i:s", $time);
                    }
                }
            }
            $this->Region_model->insertBatchRegionsOdds($regionsOddsData);
            $regionsOddsData = array();
            print $region->region_name."\n";
        }
        print 'region end'."\n";
        
        foreach ($areas as $area_id => $area){
            for($month=1;$month<=$target_month;$month++){
                $lastday = date("t", mktime(0,0,0,$month,1,$year));
                for($day=1;$day <= $lastday;$day++){
                    if($year == 2013 && $month == 11 && $day == 7) break;
                    $record_count = $this->Future_model->getFutureCountByAreaIdByLesserDate($area->id,$year.'-'.$month.'-'.$day);
                    $correct_count = $this->Future_model->getFutureCountByAreaIdByCorrectByLesserDate($area->id,$year.'-'.$month.'-'.$day);
                    if($record_count->count != 0){
                        $areasOddsData[$year.'-'.$month.'-'.$day.'-'.$area->id]['area_id'] = $area->id;
                        $areasOddsData[$year.'-'.$month.'-'.$day.'-'.$area->id]['date'] = $year.'-'.$month.'-'.$day;
                        $areasOddsData[$year.'-'.$month.'-'.$day.'-'.$area->id]['percentage'] = round($correct_count->count / $record_count->count * 100);
                        $areasOddsData[$year.'-'.$month.'-'.$day.'-'.$area->id]['created'] = date("Y-m-d H:i:s", $time);
                    }
                }
            }
            print $area->area_name."\n";
        }
        $this->Area_model->insertBatchAreasOdds($areasOddsData);
    }

    function _fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $eof = false; // Added for PHP Warning.
        while ( $eof != true ) {
        $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
        $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
        if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';

        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);

        $_csv_data = $_csv_matches[1];

        for ( $_csv_i=0; $_csv_i<count($_csv_data); $_csv_i++ ) {
        $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
        $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }
}
?>
