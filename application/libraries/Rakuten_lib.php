<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rakuten_lib
{
    public  $cache_life            = 1440;                         // Cache lifetime
    public  $cache_dir             = 'cache/';     // Cache directory
    public  $write_cache_flag     = FALSE;                     // Flag to write to cache
    private $start_date;
    function __construct()
    {
        $this->ci =& get_instance();
        $this->start_date = date("Ymd",strtotime("+30 day"));
    }

    /*
    楽天のエリアとは都道府県のこと
    */
    function getCoursesByRakutenAreaCode($rakuten_AreaCode){
        //http://api.rakuten.co.jp/rws/3.0/rest?developerId=1013706927174899148&operation=GoraGolfCourseSearch&version=2010-06-30&areaCode=0&sort=50on
        //$url = 'http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key='.$this->ci->config->item('jalan_key').'&o_area_id='.$jalan_o_area.'&order=4&count=100';
        $url = 'http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraGolfCourseSearch&version=2010-06-30&areaCode='.$rakuten_AreaCode.'&sort=evaluation';
        $xml_courses = $this->_getXML($url);
        $coursesData = array();

        $i = 0;
        foreach ($xml_courses->Hotel as $xml_course){
            $AccessInformation = array();
            foreach ($xml_course as $item){
                if($item->getName() != 'Area' && $item->getName() != 'WifiHikariStation'){
                    if($item->getName() == 'AccessInformation'){
                        $AccessInformation[] = strval($item->attributes()->name) . ':' . strval($item);
                        $hotelsData[$i][$item->getName()] = implode("\n",$AccessInformation);
                    }else{
                        $hotelsData[$i][$item->getName()] = strval($item);
                    }
                }
            }
            $i++;
        }
        return $hotelsData;
    }

    function getPlansByRakutenAreaCode($rakuten_AreaCode){
        $url='http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraPlanSearch&version=2012-12-10&playDate='.$play_date.'&sort=evaluation&areaCode='.$rakuten_AreaCode;
        $xml_courses = $this->_getXML($url);
        $coursesData = array();

        $i = 0;
        foreach ($xml_courses->Hotel as $xml_course){
            $AccessInformation = array();
            foreach ($xml_course as $item){
                if($item->getName() != 'Area' && $item->getName() != 'WifiHikariStation'){
                    if($item->getName() == 'AccessInformation'){
                        $AccessInformation[] = strval($item->attributes()->name) . ':' . strval($item);
                        $hotelsData[$i][$item->getName()] = implode("\n",$AccessInformation);
                    }else{
                        $hotelsData[$i][$item->getName()] = strval($item);
                    }
                }
            }
            $i++;
        }
        return $hotelsData;
    }

    function getCourseByRakutenGolfCourseId($rakuten_golfCourseId){
        //http://api.rakuten.co.jp/rws/3.0/rest?developerId=1013706927174899148&operation=GoraGolfCourseDetail&version=2010-06-30&golfCourseId=12345
        //$url='http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key='.$this->ci->config->item('jalan_key').'&h_id='.$jalan_h_id;
        $url='http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraGolfCourseDetail&version=2010-06-30&golfCourseId='.$rakuten_golfCourseId;
        
        $xml_hotels = $this->_getXML($url);
        $hotelData = array();

        foreach ($xml_hotels->Hotel as $xml_hotel){
            $AccessInformation = array();
            foreach ($xml_hotel as $item){
                if($item->getName() != 'Area' && $item->getName() != 'WifiHikariStation'){
                    if($item->getName() == 'AccessInformation'){//AccessInformationは並列で持っている様子
                        $AccessInformation[] = strval($item->attributes()->name) . ':' . strval($item);
                        $hotelData[$item->getName()] = implode("\n",$AccessInformation);
                    }else{
                        $hotelData[$item->getName()] = strval($item);
                    }
                    
                }
            }
        }
        return $hotelData;
    }

    function getPlansByRakutenGolfCourseIdByDate($rakuten_golfCourseId,$play_date){
        //http://api.rakuten.co.jp/rws/3.0/rest?developerId=1013706927174899148&operation=GoraPlanSearch&version=2012-12-10&playDate=2013-01-10&areaCode=20&planLunch=1&sort=evaluation
        //$url='http://jws.jalan.net/APIAdvance/StockSearch/V1/?key='.$this->ci->config->item('jalan_key').'&h_id='.$jalan_h_id.'&stay_date='.$stay_date.'&stay_count='.$stay_count.'&order=4&count=100';
        $url='http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraPlanSearch&version=2012-12-10&playDate='.$play_date.'&sort=evaluation&golfCourseId='.$rakuten_golfCourseId;
        
        $xml_stocks = $this->_getXML($url);
        $stockData = array();
        
        $plan_name = '';
        $before_plan_name = '';
        $ymd = '';
        $before_ymd = '';
        $i = 0;
        foreach ($xml_stocks->Plan as $xml_stock){
            $stock_tmp_data = array();
            $stock_number = intval($xml_stock->Stay->Date->Stock);
            
            if($stock_number > 0){
                $facilities = array();
                $AccessInformation = array();
                foreach ($xml_stock as $item){
                    if($item->getName() == 'Facilities'){
                        foreach ($item as $facility){
                            $facilities[] = strval($facility);
                        }
                        $stock_tmp_data['facilities'] = implode(',',$facilities);
                    }elseif ($item->getName() == 'PlanName'){
                        $plan_name = strval($item);
                        $stock_tmp_data['PlanName'] = $plan_name;
                    }elseif ($item->getName() == 'Stay'){
                        $stock_tmp_data['stay']['PlanDetailURL'] = strval($item->PlanDetailURL);
                        foreach ($item->Date as $date){
                            $ymd = strval($date->attributes()->year).strval($date->attributes()->month).strval($date->attributes()->date);
                            $stock_tmp_data['stay']['date'][$ymd]['rate'] = strval($date->Rate);
                            $stock_tmp_data['stay']['date'][$ymd]['stock'] = intval($date->Stock);
                        }
                    }else{
                        $stock_tmp_data[$item->getName()] = strval($item);
                    }
                    
                }
                //プラン名が同じ、日付も同じ場合は除去。禁煙、喫煙の違い等で発生する
                if($before_plan_name != $plan_name || $before_ymd != $ymd){
                    $stockData[$i] = $stock_tmp_data;
                    $i++;
                }
                $before_plan_name = $plan_name;
                $before_ymd = $ymd;
            }
        }
        return $stockData;
    }



    
    private function _getXML($url){
        // Are we caching?
        if ($this->cache_life != 0)
        {
            $filename = APPPATH.$this->cache_dir.md5($url).'.xml';

            // Is there a cache file ?
            if (file_exists($filename))
            {
                // Has it expired?
                $timedif = (time() - filemtime($filename));

                if ($timedif < ( $this->cache_life * 60))
                {
                    //$xml = file_get_contents($filename);
                    $xml = @simplexml_load_file($filename);
                }
                else
                {
                    // So raise the falg
                    $this->write_cache_flag = true;
                }
            }
            else
            {
                // Raise the flag to write the cache
                $this->write_cache_flag = true;
            }
        }

        // Parse the document
        if (!isset($xml))
        {
            $xml_write = file_get_contents($url);
            // Do we need to write the cache file?
            if ($this->write_cache_flag)
            {
                if (!$fp = @fopen($filename, 'w+b'))
                {
                    log_message('error', "Unable to write cache file: ".$filename);
                    return;
                }
                flock($fp, LOCK_EX);
                fwrite($fp, $xml_write);
                flock($fp, LOCK_UN);
                fclose($fp);
            }
            $xml = @simplexml_load_file($filename);
        }

        return $xml;
    }
    
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */