<?php
class Tools extends CI_Controller {

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

    public function sitemap()
    {

        try {
            $file= '/usr/local/apache2/htdocs/hareco/sitemap.xml';
            $this->_get_sitemap_data('main');
            $this->_make_file($file,$this->sitemap_line);

            $file= '/usr/local/apache2/htdocs/hareco/sitemap_area_date.xml';
            $this->_get_sitemap_data('area_date');
            $this->_make_file($file,$this->sitemap_line);

            $file= '/usr/local/apache2/htdocs/hareco/sitemap_spring_date.xml';
            $this->_get_sitemap_data('spring_date');
            $this->_make_file($file,$this->sitemap_line);

            $file= '/usr/local/apache2/htdocs/hareco/sitemap_airport_date.xml';
            $this->_get_sitemap_data('airport_date');
            $this->_make_file($file,$this->sitemap_line);

            $file= '/usr/local/apache2/htdocs/hareco/sitemap_leisure_date.xml';
            $this->_get_sitemap_data('leisure_date');
            $this->_make_file($file,$this->sitemap_line);

            print 'Sitemap: update success';
        } catch (Exception $e) { 
            print 'Error: ' . $e->getMessage();
        }
    }

/*
天気データの仕様
　・気象庁発表のデータは14時に確定データがでる。14時すぎに更新
　報値     毎日3時頃 *1 *2
　確定値     毎日14時頃 *2
　例）2013年11月6日の場合、11月4日のデータを取得。2014年11月6日の予測をたてて、結果ちぇっく
*/
    //daily exe///////////////////////////////////////////////////////////////////////////////////////////
    //天気情報がある箇所のみ
    private $jma_block_no_data = array(
        '11'=>array('47401'),
        '12'=>array('47407'),
        //'13'=>array('0024','0025','47404','47406','0028','1112','1213','1216','1217','1218'),
        '14'=>array('47412'),
        //'15'=>array('0038','0039','0040','0041','0042','0043','0044','47413','0046','0047','0048','0049','0050','0999','1068','1270','1271','1287','1400','1401','1402','1419','1566','1567'),
        //'16'=>array('0051','0052','47411','0054','0055','47433','0057','47421','0059','0060','0061','1072','1091','1208','1615'),
        '17'=>array('47409'),
        //'18'=>array('0084','0085','0086','0087','0088','47420','1056','1196','1266','1489','1547'),
        '19'=>array('47418'),
        '20'=>array('47417'),
        '21'=>array('47423'),
        //'22'=>array('0136','0137','0138','0139','0140','0141','47426','1097','1198','1202','1264','1276','1288','1340','1430','1594'),
        '23'=>array('47430'),
        //'24'=>array('0153','0154','0156','47428','1199','1200','1205','1513'),
        '31'=>array('47575'),
        '32'=>array('47582'),
        '33'=>array('47584'),
        '34'=>array('47590'),
        '35'=>array('47588'),
        '36'=>array('47595'),
        '40'=>array('47629'),
        '41'=>array('47615'),
        '42'=>array('47624'),
        '43'=>array('47626'),
        '44'=>array('47662', '47971'),
        '45'=>array('47648'),
        '46'=>array('47670'),
        '48'=>array('47610'),
        '49'=>array('47638'),
        '50'=>array('47656'),
        '51'=>array('47636'),
        '52'=>array('47632'),
        '53'=>array('47651'),
        '54'=>array('47604'),
        '55'=>array('47607'),
        '56'=>array('47605'),
        '57'=>array('47616'),
        '60'=>array('47761'),
        '61'=>array('47759'),
        '62'=>array('47772'),
        '63'=>array('47770'),
        '64'=>array('47780'),
        '65'=>array('47777'),
        '66'=>array('47768'),
        '67'=>array('47765'),
        '68'=>array('47741'),
        '69'=>array('47746'),
        '71'=>array('47895'),
        '72'=>array('47891'),
        '73'=>array('47887'),
        '74'=>array('47893'),
        '81'=>array('47762'),
        '82'=>array('47807'),
        '83'=>array('47815'),
        //'84'=>array('47812'),佐世保はない
        '84'=>array('47817'),//長崎
        '85'=>array('47813'),
        '86'=>array('47819'),
        '87'=>array('47830'),
        '88'=>array('47827','47909'),
        '91'=>array('47936','47945','47918')
    );
    private $handleTagList = array('td');
    private $html = '';
    private $csv_data = array();
    
    function doDaily($back_day = 1){

        $this->importWeatherBackDay($back_day);
        $this->updateWeatherYesterdayWeather($back_day);//昨日のデータ更新
        $this->createFutureForNextYearDaily($back_day);
        $this->updateFutureForTomorrow($back_day);
        $this->updateCorrectBackDay($back_day);
        $this->updateSequenceBackDay($back_day);
        $this->updateOddsBackDay($back_day);

    }
    
    //昨日の天気結果を取得
    function importWeatherBackDay($back_day = 1){
        $this->load->model('Area_model');
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        
        require_once('application/libraries/simple_html_dom.php');
        
        $ret = FALSE;
        unset($this->html);
        $this->html = '';
        $time = time();
        $weatherData = array();
        //指定日分
        for ($i = $back_day; $i > 0; $i--){
            $back_day_string = strval("-".$i." day");
            $back_day_year_month_day = date("Y/n/d",strtotime($back_day_string));//??日前
            $back_day_ymd = explode('/',$back_day_year_month_day);

            //yesterday
            $i_p = $i+1;
            $back_day_yesterday_string = strval("-".$i_p." day");
            $back_day_yesterday_year_month_day = date("Y/n/d",strtotime($back_day_yesterday_string));//??日前
            $back_day_yesterday_ymd = explode('/',$back_day_yesterday_year_month_day);
            
            foreach ($this->jma_block_no_data as $jma_prec_no => $jma_block_nos){
                foreach ($jma_block_nos as $jma_block_no){
                    $url = 'http://www.data.jma.go.jp/obd/stats/etrn/view/daily_s1.php?prec_no='.$jma_prec_no.'&block_no='.$jma_block_no.'&year='.$back_day_ymd[0].'&month='.$back_day_ymd[1].'&day=&view=';//各地点の年月日ごとの詳細気象データ
                    $this->html = file_get_html($url);

                    foreach ($this->handleTagList as $tag){
                        foreach($this->html->find($tag) as $key => $element){
                            switch ($tag){
                                case 'td':
                                    if(FALSE !== strstr($element->outertext,'data_0_0') || FALSE !== strstr($element->outertext,'data_1t_0') || FALSE !== strstr($element->outertext,'data_0_1b')){
                                        if($element->innertext == '') $element->innertext = 'null';
                                        $string = str_replace(array('&nbsp;',')',']'),array('','',''),$element->innertext);
                                        $this->csv_data['data'][$back_day_ymd[0].'/'.$back_day_ymd[1]][] = trim($string);
                                    }
                                    
                                break;

                            }
                        }
                    }
                    $count = 20;//項目数
                    $result = array();
                    $target_index = $back_day_ymd[2] - 1;
                    $back_day_jma_data = array();
                    foreach ($this->csv_data['data'] as $year_month => $array){
                        $array_chunk = array_chunk($array,$count);
                        $back_day_jma_data = $array_chunk[$target_index];
                    }
                    $this->html->clear();
                    $this->csv_data['data'] = array();
                    $array_key = $jma_block_no.$back_day_ymd[0].$back_day_ymd[1].$back_day_ymd[2];
                    
                    //$yesterday_weather = $this->Weather_model->getWeatherByAreaIdByYearByMonthByDay($areas[$jma_block_no]->id,$back_day_yesterday_ymd[0],$back_day_yesterday_ymd[1],$back_day_yesterday_ymd[2]);

                    $weatherData[$array_key]['code'] = $areas[$jma_block_no]->region_id.'_'.$areas[$jma_block_no]->todoufuken_id.'_'.$areas[$jma_block_no]->id.'_'.$jma_block_no.'_'.$back_day_ymd[0].'_'.$back_day_ymd[1].'_'.$back_day_ymd[2];
                    $weatherData[$array_key]['date'] = $back_day_ymd[0].'-'.$back_day_ymd[1].'-'.$back_day_ymd[2];
                    $weatherData[$array_key]['year'] = $back_day_ymd[0];
                    $weatherData[$array_key]['month'] = $back_day_ymd[1];
                    $weatherData[$array_key]['day'] = $back_day_ymd[2];
                    
                    $weatherData[$array_key]['region_id'] = $areas[$jma_block_no]->region_id;
                    $weatherData[$array_key]['todoufuken_id'] = $areas[$jma_block_no]->todoufuken_id;
                    $weatherData[$array_key]['area_id'] = $areas[$jma_block_no]->id;
                    $weatherData[$array_key]['jma_prec_no'] = $areas[$jma_block_no]->jma_prec_no;
                    $weatherData[$array_key]['jma_block_no'] = $areas[$jma_block_no]->jma_block_no;
                    
                    //天気判定///////////////////////////////////////////////////////////////////////////////////////////////
                    $weatherData[$array_key]['is_summary'] = 0;
                    //雨（1時間に1ミリ以上）じゃなくて、最初に出現する天気が「晴」又は「快晴」がある又は「後晴れ」があり且つ、「後雨」「後霧雨」がないこと且つ、「みぞれ」「雪」「あられ」「雹」「雷」がないこと
                    $weatherData[$array_key]['is_daytime_shine'] = $this->weather_lib->isShine($back_day_jma_data[18],$back_day_jma_data[3]) ? 0 : 1;
                    $weatherData[$array_key]['is_night_shine'] = $this->weather_lib->isShine($back_day_jma_data[19],$back_day_jma_data[3]) ? 0 : 1;
                    //$weatherData[$array_key]['is_yesterday_night_shine'] = $yesterday_weather->is_night_shine;
                    //$weatherData[$array_key]['is_yesterday_snow'] = $yesterday_weather->is_snow;
                    
                    //どれだけの確率で雨（1時間に1ミリ以上）が降ったかを算出した物が降水確率です。
                    $weatherData[$array_key]['is_rain'] = $this->weather_lib->isRain($back_day_jma_data[3]) ? 0 : 1;
                    //$weatherData[$array_key]['is_yesterday_rain'] = $yesterday_weather->is_rain;
                    
                    //雪が1cm降雪したら
                    $weatherData[$array_key]['is_snow'] = $this->weather_lib->isSnow($back_day_jma_data[16]) ? 0 : 1;
                    //$weatherData[$array_key]['is_yesterday_snow'] = $yesterday_weather->is_snow;

                    /////////////////////////////////////////////////////////////////////////////////////////////////////////
                    
                    $weatherData[$array_key]['atmosphere_spot'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[0]);
                    $weatherData[$array_key]['atmosphere_ocean'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[1]);
                    $weatherData[$array_key]['precipitation_total'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[2]);
                    $weatherData[$array_key]['precipitation_one_hour'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[3]);
                    $weatherData[$array_key]['precipitation_ten_minute'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[4]);
                    $weatherData[$array_key]['temperature_average'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[5]);
                    $weatherData[$array_key]['temperature_max'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[6]);
                    $weatherData[$array_key]['temperature_min'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[7]);
                    $weatherData[$array_key]['moisture_average'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[8]);
                    $weatherData[$array_key]['moisture_min'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[9]);
                    $weatherData[$array_key]['wind_speed_average'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[10]);
                    $weatherData[$array_key]['wind_speed_max'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[11]);
                    $weatherData[$array_key]['wind_speed_max_direction'] = $back_day_jma_data[12];
                    $weatherData[$array_key]['instant_wind_speed_max'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[13]);
                    $weatherData[$array_key]['instant_wind_speed_max_direction'] = $back_day_jma_data[14];
                    $weatherData[$array_key]['sunshine_hours'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[15]);
                    $weatherData[$array_key]['snowfall'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[16]);
                    $weatherData[$array_key]['snow_deepest'] = $this->weather_lib->checkCsvRowForNumeric($back_day_jma_data[17]);
                    $weatherData[$array_key]['daytime'] = $back_day_jma_data[18];
                    $weatherData[$array_key]['night'] = $back_day_jma_data[19];
                    //$weatherData[$array_key]['yesterday_night'] = $yesterday_weather->night;
                    $weatherData[$array_key]['created'] = date("Y-m-d H:i:s", time());
                    print $back_day_year_month_day.'-'.$jma_block_no."\n";
                }
            }
        }
        $this->Weather_model->insertBatchWeather($weatherData);
    }

    //昨日の天気結果を取得
    function updateWeatherYesterdayWeather($back_day = 1){
        $this->load->model('Area_model');
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();

        $ret = FALSE;
        unset($this->html);
        $this->html = '';
        $time = time();
        $weatherData = array();
        //指定日分
        for ($i = $back_day; $i > 0; $i--){
            $back_day_string = strval("-".$i." day");
            $back_day_year_month_day = date("Y/n/d",strtotime($back_day_string));//??日前
            $back_day_ymd = explode('/',$back_day_year_month_day);
            //yesterday
            $i_p = $i+1;
            $back_day_yesterday_string = strval("-".$i_p." day");
            $back_day_yesterday_year_month_day = date("Y/n/d",strtotime($back_day_yesterday_string));//??日前
            $back_day_yesterday_ymd = explode('/',$back_day_yesterday_year_month_day);
            
            foreach ($this->jma_block_no_data as $jma_prec_no => $jma_block_nos){
                foreach ($jma_block_nos as $jma_block_no){
                    $array_key = $jma_block_no.$back_day_ymd[0].$back_day_ymd[1].$back_day_ymd[2];
                    
                    $yesterday_weather = $this->Weather_model->getWeatherByAreaIdByYearByMonthByDay($areas[$jma_block_no]->id,$back_day_yesterday_ymd[0],$back_day_yesterday_ymd[1],$back_day_yesterday_ymd[2]);
                    $weatherData['yesterday_night'] = $yesterday_weather->night;
                    $weatherData['is_yesterday_night_shine'] = $yesterday_weather->is_night_shine;
                    
                    //どれだけの確率で雨（1時間に1ミリ以上）が降ったかを算出した物が降水確率です。
                    $weatherData['is_yesterday_rain'] = $yesterday_weather->is_rain;
                    
                    //雪が1cm降雪したら
                    $weatherData['is_yesterday_snow'] = $yesterday_weather->is_snow;
                    
                    //update
                    $this->db->where('area_id', $areas[$jma_block_no]->id);
                    $this->db->where('year', $back_day_ymd[0]);
                    $this->db->where('month', $back_day_ymd[1]);
                    $this->db->where('day', $back_day_ymd[2]);
                    $this->db->update('weathers', $weatherData);
                    
                    print $back_day_year_month_day.'-'.$jma_block_no."\n";
                }
            }
        }
    }

    //来年の今日の未来を予測
    function createFutureForNextYearDaily($back_day = 1){
        $time = time();
        $this->load->model('Area_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        
        $holidays = null;
        $sampling_year = $this->config->item('jma_weather_start_year');
        //指定日の1日前のデータを取得した後、その1日前の予測が可能になるので、-1日する。例）11月1日の0時、10月31日のデータを持ってきたので、翌年の10月31日の予測ができる
        for ($i = $back_day; $i > 0; $i--){
            $back_day_string = strval("-".$i." day");
            $back_day_year_month_day = date("Y/n/d",strtotime($back_day_string));//??日前
            $back_day_ymd = explode('/',$back_day_year_month_day);

            $next_year = $back_day_ymd[0] + 1;
            $back_day_month = $back_day_ymd[1];
            $back_day_day = $back_day_ymd[2];
            
            //holiday
            if(is_null($holidays)) $holidays = $this->weather_lib->get_holidays_this_month($back_day_ymd[0]);
            $month_string = $back_day_month < 10 ? '0'.$back_day_month : $back_day_month;
            
            foreach ($areas as $area){
                //前日の日付を取得
                $i_p = $i+1;
                $back_day_yesterday_string = strval("-".$i_p." day");
                $back_day_yesterday_year_month_day = date("Y/n/d",strtotime($back_day_yesterday_string));//??日前
                $back_day_yesterday_ymd = explode('/',$back_day_year_month_day);

                //$month_day_weathers = $this->Weather_model->getWeatherByAreaIdByMonthByDay($area->id,$back_day_month,$back_day_day);
                //前日の実際の結果を取得
                $real_yesterday_weather = $this->Weather_model->getWeatherByAreaIdByYearByMonthByDay($area->id,$back_day_yesterday_ymd[0],$back_day_yesterday_ymd[1],$back_day_yesterday_ymd[2]);
                $real_yesterday_night = $real_yesterday_weather->night;//昨日の夜の天気
                
                //先頭の天気文字で始まる過去データを使用する
                $head = $this->weather_lib->changeWeatherHeadString($real_yesterday_night);
                $month_day_weathers = $this->Weather_model->getWeatherByAreaIdByHeadByMonthByDay($area->id,$head,$back_day_month,$back_day_day,$sampling_year);

                //空の場合、サンプリングの数が5つに満たない場合は全体の統計予測で
                if(empty($month_day_weathers) || count($month_day_weathers) < 5){
                    $month_day_weathers = $this->Weather_model->getWeatherByAreaIdByMonthByDay($area->id,$back_day_month,$back_day_day);
                }

                $futureData[$area->id.$back_day_year_month_day] = $this->weather_lib->getFutureWeather($month_day_weathers,$sampling_year);
                
                $futureData[$area->id.$back_day_year_month_day]['code'] = $area->region_id.'_'.$area->todoufuken_id.'_'.$area->id.'_'.$area->jma_block_no.'_'.$next_year.'_'.$back_day_month.'_'.$back_day_day;
                $futureData[$area->id.$back_day_year_month_day]['region_id'] = $area->region_id;
                $futureData[$area->id.$back_day_year_month_day]['todoufuken_id'] = $area->todoufuken_id;
                $futureData[$area->id.$back_day_year_month_day]['area_id'] = $area->id;
                $futureData[$area->id.$back_day_year_month_day]['jma_prec_no'] = $area->jma_prec_no;
                $futureData[$area->id.$back_day_year_month_day]['jma_block_no'] = $area->jma_block_no;
                $futureData[$area->id.$back_day_year_month_day]['year'] = $next_year;
                $futureData[$area->id.$back_day_year_month_day]['month'] = $back_day_month;
                $futureData[$area->id.$back_day_year_month_day]['day'] = $back_day_day;
                $futureData[$area->id.$back_day_year_month_day]['date'] = $futureData[$area->id.$back_day_year_month_day]['year'].'-'.$futureData[$area->id.$back_day_year_month_day]['month'].'-'.$futureData[$area->id.$back_day_year_month_day]['day'];
                $futureData[$area->id.$back_day_year_month_day]['created'] = date("Y-m-d H:i:s", $time);
                
                /*
                実際の結果が欲しいのではなく、予測した未来としての昨日のデータが必要
                */
                //yesterday////////////////////////////////
                $yesterday_future = $this->Future_model->getFutureByAreaIdByDate($area->id,$back_day_yesterday_year_month_day);
                //daytimne
                $futureData[$area->id.$back_day_year_month_day]['yesterday_daytime'] = $yesterday_future->daytime;
                $futureData[$area->id.$back_day_year_month_day]['yesterday_daytime_icon_image'] = $yesterday_future->daytime_icon_image;
                $futureData[$area->id.$back_day_year_month_day]['yesterday_daytime_number'] = $yesterday_future->night_number;
                $futureData[$area->id.$back_day_year_month_day]['yesterday_daytime_type'] = $yesterday_future->daytime_type;
                //$futureData[$area->id.$back_day_year_month_day]['is_yesterday_daytime_shine'] = $yesterday_future->is_daytime_shine;
                //$futureData[$area->id.$back_day_year_month_day]['is_yesterday_daytime_snow'] = $yesterday_future->is_daytime_snow;
                
                //night
                $futureData[$area->id.$back_day_year_month_day]['yesterday_night'] = $yesterday_future->night;
                $futureData[$area->id.$back_day_year_month_day]['yesterday_night_icon_image'] = $yesterday_future->night_icon_image;
                $futureData[$area->id.$back_day_year_month_day]['yesterday_night_number'] = $yesterday_future->night_number;
                $futureData[$area->id.$back_day_year_month_day]['yesterday_night_type'] = $yesterday_future->night_type;
                //$futureData[$area->id.$back_day_year_month_day]['is_yesterday_night_shine'] = $yesterday_future->is_night_shine;
                //$futureData[$area->id.$back_day_year_month_day]['is_yesterday_night_snow'] = $yesterday_future->is_night_snow;

                //初期化用tomorrow///////////////////////////////////////
                //daytimne
                $futureData[$area->id.$back_day_year_month_day]['tomorrow_daytime'] = '';
                $futureData[$area->id.$back_day_year_month_day]['tomorrow_daytime_icon_image'] = '';
                $futureData[$area->id.$back_day_year_month_day]['tomorrow_daytime_number'] = 0;
                $futureData[$area->id.$back_day_year_month_day]['tomorrow_daytime_type'] = 9;
                //$futureData[$area->id.$back_day_year_month_day]['is_tomorrow_daytime_shine'] = 9;
                //$futureData[$area->id.$back_day_year_month_day]['is_tomorrow_daytime_snow'] = 9;
                
                //holiday
                $futureData[$area->id.$back_day_year_month_day]['day_of_the_week'] = date("N",mktime(0,0,0,$back_day_month,$back_day_day,$next_year));// 1（月曜日）から 7（日曜日）
                if(array_key_exists($next_year.'-'.$month_string.'-'.$back_day_day,$holidays)){
                    $futureData[$area->id.$back_day_year_month_day]['holiday'] = 2;//1土日 2祝日
                }elseif ($futureData[$area->id.$back_day_year_month_day]['day_of_the_week'] == 6 || $futureData[$area->id.$back_day_year_month_day]['day_of_the_week'] == 7){
                    $futureData[$area->id.$back_day_year_month_day]['holiday'] = 1;//1土日 2祝日
                }else{
                    $futureData[$area->id.$back_day_year_month_day]['holiday'] = 0;
                }
                
                print $back_day_year_month_day.'-'.$area->area_name."\n";
            }
        }
        $this->Future_model->insertBatchFuture($futureData);
    }


    //翌日情報を指定日の前日レコードに入れ込む
    function updateFutureForTomorrow($back_day = 1){
        $time = time();
        $this->load->model('Area_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        
        $holidays = null;
        $sampling_year = $this->config->item('jma_weather_start_year');
        //指定日の1日前のデータを取得した後、その1日前の予測が可能になるので、-1日する。例）11月1日の0時、10月31日のデータを持ってきたので、翌年の10月31日の予測ができる
        for ($i = $back_day; $i > 0; $i--){
            $back_day_string = strval("-".$i." day");
            $back_day_year_month_day = date("Y/n/d",strtotime($back_day_string));//??日前
            $back_day_ymd = explode('/',$back_day_year_month_day);

            $next_year = $back_day_ymd[0] + 1;
            $back_day_month = $back_day_ymd[1];
            $back_day_day = $back_day_ymd[2];
            
            //holiday
            if(is_null($holidays)) $holidays = $this->weather_lib->get_holidays_this_month($back_day_ymd[0]);
            $month_string = $back_day_month < 10 ? '0'.$back_day_month : $back_day_month;
            
            foreach ($areas as $area){
                //前日の日付を取得
                $i_p = $i+1;
                $back_day_yesterday_string = strval("-".$i_p." day");
                $back_day_yesterday_year_month_day = date("Y/n/d",strtotime($back_day_yesterday_string));//??日前
                $back_day_yesterday_ymd = explode('/',$back_day_yesterday_year_month_day);

                $next_year_back_day_yesterday_month = $back_day_yesterday_ymd[1];
                $next_year_back_day_yesterday_day = $back_day_yesterday_ymd[2];

                $next_year_back_day_future = $this->Future_model->getFutureByAreaIdByDate($area->id,$next_year.'/'.$back_day_month.'/'.$back_day_day);
                //tomorrow更新//////////////////////////////////////////////////
                $tomorrowData = array();
                $tomorrowData['tomorrow_daytime'] = $next_year_back_day_future->daytime;
                $tomorrowData['tomorrow_daytime_icon_image'] = $next_year_back_day_future->daytime_icon_image;
                $tomorrowData['tomorrow_daytime_number'] = $next_year_back_day_future->daytime_number;
                $tomorrowData['tomorrow_daytime_type'] = $next_year_back_day_future->daytime_type;
                //$tomorrowData['is_tomorrow_daytime_shine'] = $next_year_back_day_future->is_daytime_shine;
                //$tomorrowData['is_tomorrow_daytime_snow'] = $next_year_back_day_future->is_daytime_snow;
                
                $this->db->where('area_id', $area->id);
                $this->db->where('date', $next_year.'/'.$next_year_back_day_yesterday_month.'/'.$next_year_back_day_yesterday_day);
                $this->db->update('futures', $tomorrowData);
            }
        }
    }
    
    //取得した昨日の結果を元に昨日の予測の正答確認
    function updateCorrectBackDay($back_day = 1){
        $time = time();
        $this->load->model('Area_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        for ($i = $back_day; $i > 0; $i--){
            $back_day_string = strval("-".$i." day");
            $back_day_year_month_day = date("Y/n/d",strtotime($back_day_string));//??日前
            $back_day_ymd = explode('/',$back_day_year_month_day);
            foreach ($areas as $area){
                //過去データ取得
                $year_month_day_weather = $this->Weather_model->getWeatherByAreaIdByYearByMonthByDay($area->id,$back_day_ymd[0],$back_day_ymd[1],$back_day_ymd[2]);
                if(!isset($year_month_day_weather->daytime)){
                    echo 'weather-'.$area->id.'-'.$back_day_ymd[0].'-'.$back_day_ymd[1].'-'.$back_day_ymd[2]."\n";
                    die();
                }
                //昨日の予測データ取得
                $year_month_day_future = $this->Future_model->getFutureByAreaIdByYearByMonthByDay($area->id,$back_day_ymd[0],$back_day_ymd[1],$back_day_ymd[2]);
                if(!isset($year_month_day_future->daytime)){
                    echo 'future-'.$area->id.'-'.$back_day_ymd[0].'-'.$back_day_ymd[1].'-'.$back_day_ymd[2]."\n";
                    die();
                }
                //昼の天気で正答確認
                $futureData['is_correct'] =  $this->weather_lib->isCorrect($year_month_day_weather->daytime,$year_month_day_future->daytime) ? 0 : 1;
                $this->Future_model->updateFuture($area->id,$back_day_ymd[0],$back_day_ymd[1],$back_day_ymd[2],$futureData);
                echo 'done-'.$back_day_year_month_day.'-'.$area->id."\n";
            }
        }
    }

    //連続の晴れ、連休更新
    function updateSequenceBackDay($back_day = 1){
        function plus (&$int,$date){
            $int = $int+1;
        }
        $this->load->model('Area_model');
        $this->load->model('Future_model');
        $areas = $this->Area_model->getAllAreasFlipJmaId();

        $back_day_string = strval("-".$back_day." day");
        $back_day_month_day = date("n-d",strtotime($back_day_string));//??日前
        $future_year = date("Y",time()) + 1;
        $back_day_future_year_month_day = $future_year.'-'.$back_day_month_day;

        foreach ($areas as $area){
            $futures = $this->Future_model->getFutureByAreaIdByDateOrderDate($area->id,$back_day_future_year_month_day);
            $daytime_shine_date_list = array();
            $night_shine_date_list = array();
            $holiday_date_list = array();
            
            //古い日付からチェック
            $day = 0;
            $is_daytime_old_update = FALSE;
            $is_daytime_old_update_value = 0;
            
            $is_night_old_update = FALSE;
            $is_night_old_update_value = 0;

            $is_holiday_old_update = FALSE;
            $is_holiday_old_update_value = 0;
            
            foreach ($futures as $future){
                //昼の天気
                if($future->daytime_type == 0){
                    if($day == 0){
                        //過去指定日が晴だったらロールバックして更新しないといけない
                        $is_daytime_old_update = TRUE;
                    }
                    if($is_daytime_old_update) $is_daytime_old_update_value++;
                    if( !array_key_exists($future->date,$daytime_shine_date_list) ){
                        $daytime_shine_date_list[$future->date] = 0;
                    }
                    array_walk($daytime_shine_date_list, "plus");
                }else{
                    $is_daytime_old_update = FALSE;//古い情報更新を停止
                    //晴れじゃなかったのでupdate
                    if(!empty($daytime_shine_date_list)){
                        $this->_updateBackDaySequence($area->id,$daytime_shine_date_list,'daytime_shine_sequence');
                        $futureData = array();
                        $daytime_shine_date_list = array();//連続初期化
                    }
                }

                //夜の天気
                if($future->night_type == 0){
                    if($day == 0){
                        //過去指定日が晴だったらロールバックして更新しないといけない
                        $is_night_old_update = TRUE;
                    }
                    if($is_night_old_update) $is_night_old_update_value++;
                    if( !array_key_exists($future->date,$night_shine_date_list) ){
                        $night_shine_date_list[$future->date] = 0;
                    }
                    array_walk($night_shine_date_list, "plus");
                }else{
                    $is_night_old_update = FALSE;//古い情報更新を停止
                    //晴れじゃなかったのでupdate
                    if(!empty($night_shine_date_list)){
                        $this->_updateBackDaySequence($area->id,$night_shine_date_list,'night_shine_sequence');
                        $night_shine_date_list = array();//連続初期化
                    }
                }

                //連休
                if($future->holiday > 0){
                    if($day == 0){
                        //過去指定日が晴だったらロールバックして更新しないといけない
                        $is_holiday_old_update = TRUE;
                    }
                    if($is_holiday_old_update) $is_holiday_old_update_value++;
                    if( !array_key_exists($future->date,$holiday_date_list) ){
                        $holiday_date_list[$future->date] = 0;
                    }
                    array_walk($holiday_date_list, "plus");
                }else{
                    $is_holiday_old_update = FALSE;//古い情報更新を停止
                    if (!empty($holiday_date_list)){
                        $this->_updateBackDaySequence($area->id,$holiday_date_list,'holiday_sequence');
                        $holiday_date_list = array();//連続初期化
                    }
                }

                $day++;
            }

            //ループを抜けた後に最新のsequenceを更新//////////////////////////////////
            //old daytime update
            if($is_daytime_old_update_value > 0) $this->_updateOldSequence($area->id,$future->date,$back_day_future_year_month_day,$is_daytime_old_update_value,'daytime_shine_sequence');

            //old night update
            if($is_night_old_update_value > 0) $this->_updateOldSequence($area->id,$future->date,$back_day_future_year_month_day,$is_night_old_update_value,'night_shine_sequence');
            
            //old holiday update
            if($is_holiday_old_update_value > 0) $this->_updateOldSequence($area->id,$future->date,$back_day_future_year_month_day,$is_holiday_old_update_value,'holiday_sequence');

            //ループを抜けた後に最新のsequenceを更新//////////////////////////////////
            if(!empty($daytime_shine_date_list)){
                $this->_updateBackDaySequence($area->id,$daytime_shine_date_list,'daytime_shine_sequence');
                $daytime_shine_date_list = array();//連続初期化
            }
            if(!empty($night_shine_date_list)){
                $this->_updateBackDaySequence($area->id,$night_shine_date_list,'night_shine_sequence');
                $night_shine_date_list = array();//連続初期化
            }
            if (!empty($holiday_date_list)){
                $this->_updateBackDaySequence($area->id,$holiday_date_list,'holiday_sequence');
                $holiday_date_list = array();//連続初期化
            }
            echo 'done-sequence'.$area->area_name."\n";
        }
    }
    
    private function _updateOldSequence($area_id,$future_date,$back_day_future_year_month_day,$is_old_update_value,$sequence_name = 'daytime_shine_sequence'){
        //指定日より昔で、直近のsequenceが途切れている(0)レコードを調べる。その日翌日から順番にback day分の値をの加算していく
        $back_day_break_sequence_future = $this->Future_model->getFutureOrderDateByAreaIdByLesserDateByZeroSequence($area_id,$back_day_future_year_month_day,$sequence_name);
        $back_day_break_next_time = strtotime($back_day_break_sequence_future->date) + 86400;
        
        //0レコードの翌日が更新対象
        $back_day_break_next_date = date("Y-n-d",$back_day_break_next_time);
        $back_day_break_next_date_future = $this->Future_model->getFutureByAreaIdByDate($area_id,$back_day_break_next_date);
        
        $back_day_time = strtotime($future_date);
        $start_old_value = $back_day_break_next_date_future->$sequence_name;
        $i = 0;
        for ($update_time = $back_day_break_next_time;$update_time < $back_day_time;$update_time = $update_time + 86400){
            $futureData[$sequence_name] =  $start_old_value + $is_old_update_value - $i;//減算
            $this->Future_model->updateFutureByAreaIdAndDate($area_id,date("Y-n-d",$update_time),$futureData);
            $futureData = array();
            $i++;
        }
    }
    
    private function _updateBackDaySequence($area_id,$date_list,$sequence_name = 'daytime_shine_sequence'){
        foreach ($date_list as $date => $count){
            $futureData[$sequence_name] =  $count;
            $this->Future_model->updateFutureByAreaIdAndDate($area_id,$date,$futureData);
            $futureData = array();
        }
    }
    
    //確率更新
    function updateOddsBackDay($back_day = 1){
        $this->load->model('Odds_model');
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Weather_model');
        $this->load->model('Future_model');
        
        $time = time();
        $regions = $this->Region_model->getAllRegions();
        $areas = $this->Area_model->getAllAreasFlipJmaId();
        for ($i = $back_day; $i > 0; $i--){
            $back_day_string = strval("-".$i." day");
            $back_day_year_month_day = date("Y/n/d",strtotime($back_day_string));//??日前

            $back_day_ymd = explode('/',$back_day_year_month_day);
            $array_key = $back_day_ymd[0].$back_day_ymd[1].$back_day_ymd[2];
            
            //all
            $oddsData = array();
            $date_string = $back_day_ymd[0].'-'.$back_day_ymd[1].'-'.$back_day_ymd[2];
            $record_count = $this->Future_model->getFutureCountByLesserDate($date_string);
            $correct_count = $this->Future_model->getFutureCountByCorrectByLesserDate($date_string);
            $oddsData[$date_string]['date'] = $date_string;
            $oddsData[$date_string]['percentage'] = round($correct_count->count / $record_count->count * 100);
            $oddsData[$date_string]['created'] = date("Y-m-d H:i:s", $time);
            $this->Odds_model->insertBatchOddsesOdds($oddsData);
            
            print 'all odds end'."\n";

            //region
            $regionsOddsData = array();
            foreach ($regions as $region_id => $region){
                $record_count = $this->Future_model->getFutureCountByRegionId($region->id);
                $correct_count = $this->Future_model->getFutureCountByRegionIdByCorrect($region->id);
                $regionsOddsData[$region->id.$array_key]['region_id'] = $region->id;
                $regionsOddsData[$region->id.$array_key]['date'] = date("Y",$time).'-'.date("n",$time).'-'.date("d",$time);
                $regionsOddsData[$region->id.$array_key]['percentage'] = round($correct_count->count / $record_count->count * 100);
                $regionsOddsData[$region->id.$array_key]['created'] = date("Y-m-d H:i:s", $time);
            }
            print 'region odds end'."\n";
            
            //area
            $areasOddsData = array();
            foreach ($areas as $area){
                $record_count = $this->Future_model->getFutureCountByAreaId($area->id);
                $correct_count = $this->Future_model->getFutureCountByAreaIdByCorrect($area->id);
                $areasOddsData[$area->id.$array_key]['area_id'] = $area->id;
                $areasOddsData[$area->id.$array_key]['date'] = date("Y",$time).'-'.date("n",$time).'-'.date("d",$time);
                $areasOddsData[$area->id.$array_key]['percentage'] = round($correct_count->count / $record_count->count * 100);
                $areasOddsData[$area->id.$array_key]['created'] = date("Y-m-d H:i:s", $time);
            }
            print 'area odds end'."\n";
        }
        $this->Region_model->insertBatchRegionsOdds($regionsOddsData);
        $this->Area_model->insertBatchAreasOdds($areasOddsData);
    }

    function _get_sitemap_data($type = 'main'){
        $domain = 'hareco.jp';
        $start_year = 2013;
        $today_year = date("Y",time());
        $end_year = $today_year+1;
        $target_month = date("n",time());
        
        $this->load->helper('url');
        
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->lang->load('setting');
        
        $this->sitemap_line = null;
        $this->sitemap_line .= '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
        $this->sitemap_line .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        
        if($type == 'main') $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/');
        
        //areas
        if($type == 'main'){
            $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/area/');
            $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/area/holiday');
        }
        $areas = $this->Area_model->getAllAreas();
        foreach ($areas as $index => $area) {
            if($type == 'main') $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/area/show/'.$area->id);
            if($type == 'area_date'){
                for ($year=$start_year;$year<=$end_year;$year++){
                    for($month=1;$month<=$target_month;$month++){
                        $lastday = date("t", mktime(0,0,0,$month,1,$year));
                        $month_string = $month < 10 ? '0'.$month : $month;
                        for($day=1;$day <= $lastday;$day++){
                            $day_string = $day < 10 ? '0'.$day : $day;
                            $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/area/date/'.$area->id.'/'.$year.'-'.$month_string.'-'.$day_string);
                        }
                    }
                }
            }
        }

        //springs
        if($type == 'main') $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/spring/');
        $springs = $this->Spring_model->getAllSprings();
        foreach ($springs as $index => $spring) {
            if($type == 'main') $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/spring/show/'.$spring->id);
            if($type == 'spring_date'){
                for ($year=$start_year;$year<=$end_year;$year++){
                    for($month=1;$month<=$target_month;$month++){
                        $lastday = date("t", mktime(0,0,0,$month,1,$year));
                        $month_string = $month < 10 ? '0'.$month : $month;
                        for($day=1;$day <= $lastday;$day++){
                            $day_string = $day < 10 ? '0'.$day : $day;
                            $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/spring/date/'.$spring->id.'/0/'.$spring->area_id.'/'.$year.'-'.$month_string.'-'.$day_string);
                        }
                    }
                }
            }
        }

        //airports
        if($type == 'main') $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/airport/');
        $this->load->model('Airport_model');
        $airports = $this->Airport_model->getAllAirports();
        foreach ($airports as $index => $airport) {
            if($type == 'main') $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/airport/show/'.$airport->id);
            if($type == 'airport_date'){
                for ($year=$start_year;$year<=$end_year;$year++){
                    for($month=1;$month<=$target_month;$month++){
                        $lastday = date("t", mktime(0,0,0,$month,1,$year));
                        $month_string = $month < 10 ? '0'.$month : $month;
                        for($day=1;$day <= $lastday;$day++){
                            $day_string = $day < 10 ? '0'.$day : $day;
                            $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/airport/date/'.$airport->id.'/'.$year.'-'.$month_string.'-'.$day_string);
                        }
                    }
                }
            }
        }

        //leisures
        if($type == 'main'){
            $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/leisure/');
            $this->load->model('Todoufuken_model');
            $all_todoufuken = $this->Todoufuken_model->getAllTodoufuken();
            foreach ($all_todoufuken as $key => $todoufuken){
                $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/leisure/view/'.$todoufuken->id);
            }
        }
        
        $this->load->model('Leisure_model');
        $leisures = $this->Leisure_model->getAllLeisures();
        foreach ($leisures as $index => $leisure) {
            if($type == 'main') $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/leisure/show/'.$leisure->id);
            if($type == 'leisure_date'){
                for ($year=$start_year;$year<=$end_year;$year++){
                    for($month=1;$month<=$target_month;$month++){
                        $lastday = date("t", mktime(0,0,0,$month,1,$year));
                        $month_string = $month < 10 ? '0'.$month : $month;
                        for($day=1;$day <= $lastday;$day++){
                            $day_string = $day < 10 ? '0'.$day : $day;
                            $this->sitemap_line .= $this->_make_sitemap_url('http://'.$domain.'/leisure/date/'.$leisure->id.'/'.$year.'-'.$month_string.'-'.$day_string);
                        }
                    }
                }
            }
        }

        $this->sitemap_line .= '</urlset>';
    }

    function _make_sitemap_url($url,$lastmod = null,$changefreq = 'weekly'){
        $string = '';
        $string .= '    <url>'."\n";
        $string .= '        <loc>'.$url.'</loc>'."\n";
        if($lastmod != null) $string .= '        <lastmod>'.$lastmod.'</lastmod>'."\n";
        if($changefreq != null) $string .= '        <changefreq>'.$changefreq.'</changefreq>'."\n";
        $string .= '    </url>'."\n";
        return $string;
    }
    function _make_file($file,$data){
        umask(0);
        $file=trim($file);
        $file_dat=fopen($file,"w+");
        flock($file_dat, LOCK_EX);
        fputs($file_dat, $data);
        flock($file_dat, LOCK_UN);
        chmod($file,0666);
    }
}
?>
