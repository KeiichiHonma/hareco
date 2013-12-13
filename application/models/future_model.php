<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Weathers
 *
 * @author    kh
 */
class Future_model extends CI_Model
{
    var $CI;
    private $table_name            = 'futures';
    private $columns = "id, code, date, year, month, day, region_id,  todoufuken_id, area_id, daytime_icon_image, night_icon_image, tomorrow_daytime_icon_image, daytime,night,tomorrow_daytime,is_daytime_shine,daytime_number,daytime_shine_sequence,night,is_night_shine,night_shine_sequence,yesterday_night,is_yesterday_night_shine , temperature_max, temperature_min, rain_percentage, snow_percentage,day_of_the_week,holiday,holiday_sequence";
    private $start_datetime;
    private $start_date;
    /*
    除去
    川崎市、、神戸市、さいたま市、横浜市#
    
    top下部の連休
    札幌 4
    仙台 13
    東京 30
    名古屋 25
    京都 34
    大阪 40
    広島 37
    福岡 43
    沖縄 56
    */
    private $million_city_query = 'area_id = 4 OR area_id = 13 OR area_id = 30 OR area_id = 25 OR area_id = 34 OR area_id = 40 OR area_id = 37 OR area_id = 43 OR area_id = 56';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
        $this->start_datetime = strtotime("+8 day");
        $this->start_date = date("Y-n-j",$this->start_datetime);
    }
    
    //top slide
    function getSpringFuturesGoupByAreaByHolidayBySequenceForSlide($spring_id,$holiday = 1,$holiday_sequence = 2,$shine_sequence = 2)
    {
        $query = $this->db->query("SELECT {$this->table_name}.id AS id,{$this->table_name}.area_id AS area_id, date, springs.id AS spring_id, springs.spring_name, daytime_icon_image, night_icon_image, tomorrow_daytime_icon_image, daytime,night,tomorrow_daytime,is_daytime_shine,daytime_number,daytime_shine_sequence,night,is_night_shine,night_shine_sequence,yesterday_night,is_yesterday_night_shine , temperature_max, temperature_min, rain_percentage, snow_percentage,day_of_the_week,holiday,holiday_sequence
                                    FROM {$this->table_name}
                                    INNER JOIN springs ON {$this->table_name}.area_id = springs.area_id
                                    WHERE is_daytime_shine = 0 AND date > '{$this->start_date}' AND springs.id = ? AND holiday >= ? AND holiday_sequence >= ? AND daytime_shine_sequence >= ?
                                    GROUP BY area_id"
        , array($spring_id,$holiday,$holiday_sequence,$shine_sequence)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
    /*
    百万都市限定の晴れ未来。連休用
    */
    function getFuturesGoupByAreaByHolidaySequenceByMillionCity($holiday_sequence = 2,$shine_sequence = 2)
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE daytime='晴' AND is_daytime_shine = 0 AND holiday >= 1 AND date > '{$this->start_date}' AND holiday_sequence >= ? AND daytime_shine_sequence >= ?
                                    AND ( {$this->million_city_query} )
                                    GROUP BY area_id"
        , array($holiday_sequence,$shine_sequence)
        );
        if ($query->num_rows() != 0) return $query->result('flip','area_id');
        return array();
    }

    /*
    晴れ未来。連休用
    */
    function getFuturesGoupByAreaByHolidaySequence($holiday_sequence = 2,$shine_sequence = 2)
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE daytime='晴' AND is_daytime_shine = 0 AND holiday >= 1 AND date > '{$this->start_date}' AND holiday_sequence >= ? AND daytime_shine_sequence >= ?
                                    GROUP BY area_id
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.area_id ASC"
        , array($holiday_sequence,$shine_sequence)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    //汎用未来データ取得
    function getFutures($type = 'area', $object_id, $order, $page,$weather = 'shine', $daytime_shine_sequence = null, $day_type = array('type'=>'holiday','value'=>1), $start_date = null)
    {
        $result = array();
        $cond = '';
        $and_cond = array();
        
        //タイプ指定
        switch ($type){
            case 'index':
                $perPageCount = $this->CI->config->item('paging_count_per_index_page');
                $offset = $perPageCount * ($page - 1);
                //$and_cond[] = "{$this->table_name}.area_id = ".$object_id;
                $end_datetime = $this->start_datetime + (86400 * 7);
                $end_date = date("Y-n-j",$end_datetime);
                $and_cond[] = "( {$this->million_city_query} )";
                $and_cond[] = "date < '{$end_date}'";
            break;
            case 'area':
                $perPageCount = $this->CI->config->item('paging_count_per_recommend_page');
                $offset = $perPageCount * ($page - 1);
                $and_cond[] = "{$this->table_name}.area_id = ".$object_id;
                if($weather == 'shine'){
                    $and_cond[] = "is_daytime_shine = 0";
                }elseif($weather == 'rain'){
                    $and_cond[] = "is_rain = 0";
                }elseif($weather == 'snow'){
                    $and_cond[] = "is_daytime_snow = 0";
                }
            break;
            case 'sp':
                $perPageCount = $this->CI->config->item('paging_count_per_recommend_sp_page');
                $offset = $perPageCount * ($page - 1);
                $and_cond[] = "{$this->table_name}.area_id = ".$object_id;
                if($weather == 'shine'){
                    $and_cond[] = "is_daytime_shine = 0";
                }elseif($weather == 'rain'){
                    $and_cond[] = "is_rain = 0";
                }elseif($weather == 'snow'){
                    $and_cond[] = "is_daytime_snow = 0";
                }
            break;
        }
        
        //晴れの連続数
        if(!is_null($daytime_shine_sequence)){
            //$and_cond[] = "holiday_sequence {$sequence}";
            $and_cond[] = "daytime_shine_sequence {$daytime_shine_sequence}";
        }
        
        //日タイプ
        switch ($day_type['type']){
            case 'index':

            break;
            case 'holiday':
                $and_cond[] = "holiday >= {$day_type['value']}";
            break;
            case 'youbi':
                $and_cond[] = "day_of_the_week = {$day_type['value']}";
            break;
            case 'multi':
                if(!empty($day_type['value'])){
                    foreach ($day_type['value'] as $value){
                        if($value == 0){
                            break;
                        }elseif($value <= 7){
                            $or_cond[] = "day_of_the_week = ".$value;
                        }elseif ($value == 8){
                            $or_cond[] = "holiday = 2";
                        }
                        
                    }
                }
            break;
        }

        //期間
        if(is_null($start_date)){
            $and_cond[] = "date >= '{$this->start_date}'";
        }else{
            $and_cond[] = "date >= '{$start_date}'";
        }
        if(!empty($or_cond)) $and_cond[] = '('.implode(' OR ',$or_cond).')';
        if(!empty($and_cond)) $cond = implode(' AND ',$and_cond);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE $cond
                                    ORDER BY {$this->table_name}.{$order}
                                    LIMIT {$offset},{$perPageCount}"
        );

        if ($query->num_rows() != 0) {
            $result['data'] = $query->result();
            $query = $this->db->query("SELECT FOUND_ROWS() as count");
            if($query->num_rows() == 1) {
                foreach ($query->result() as $row)
                $result['count'] = $row->count;
            }
        } else {
            $result['data'] = array();
            $result['count'] = 0;
        }

        return $result;
    }

    //休日で晴れる連休を取得。ゴルフはこちら
    //休日+休前日限定で晴れる連休を取得。温泉はこちら
    function getFuturesByAreaIdByHolidayByYoubiBySequence($area_id, $order, $page, $holiday = null, $sequence = null, $youbi = null)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        if(is_null($youbi)){
            $cond = "holiday >= $holiday AND holiday_sequence >= $sequence";
        }else{
            $cond = "(holiday >= $holiday AND holiday_sequence >= $sequence || day_of_the_week = $youbi)";
        }
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE is_daytime_shine = 0 AND date > '{$this->start_date}' AND {$this->table_name}.area_id = ?
                                    ORDER BY {$this->table_name}.{$order}
                                    LIMIT {$offset},{$perPageCount}"
        , array($area_id,$sequence)
        );

        if ($query->num_rows() != 0) {
            $result['data'] = $query->result();
            $query = $this->db->query("SELECT FOUND_ROWS() as count");
            if($query->num_rows() == 1) {
                foreach ($query->result() as $row)
                $result['count'] = $row->count;
            }
        } else {
            $result['data'] = array();
            $result['count'] = 0;
        }

        return $result;
    }

    function getFutureByAreaIdByYearByMonthByDay($area_id,$year,$month,$day)
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND {$this->table_name}.year = ? AND {$this->table_name}.month = ? AND {$this->table_name}.day = ?"
        , array(intval($area_id),intval($year),intval($month),intval($day))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }
    
    //指定エリアの指定日
    function getFutureByAreaIdByDate($area_id,$date)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND date = '{$date}'"
        , array(intval($area_id))
        );
        if ($query->num_rows() == 1) return $query->row();

        return array();
    }
    
    //指定エリアの指定日+前後1周間分
    function getFuturesByAreaIdByDateForWeek($area_id,$date)
    {
        $ymd = explode('-',$date);
        $time = mktime(0,0,0,$ymd[1],$ymd[2],$ymd[0]);
        $cond = '';

        if( $this->start_datetime < $time && ($time - $this->start_datetime) <= 86400*3 ){//予想開始日から3日以内の場合はstart_dateを使用
            $cond = "date >= '{$this->start_date}'";
        }else{//天気予報が出している日付+今日を含む過去の日付又は、通常日付
            $base = date("Y-m-d",$time - 86400*3);
            $cond = "date >= '{$base}'";
        }
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND {$cond}
                                    ORDER BY date ASC
                                    LIMIT 0,7"
        , array(intval($area_id))
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getFutureByAreaIdByDateOrderDate($area_id,$date)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND date >= '{$date}'
                                    ORDER BY date ASC"
        , array(intval($area_id))
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getFutureCountByRegionId($region_id)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.region_id = ? AND is_correct != 9"
        , array(intval($region_id))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFutureCountByRegionIdByCorrect($region_id)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.region_id = ? AND is_correct = 0"
        , array(intval($region_id))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFutureCountByAreaId($area_id)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND is_correct != 9"
        , array(intval($area_id))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFutureCountByAreaIdByCorrect($area_id)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND is_correct = 0"
        , array(intval($area_id))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }
    
    //dev only/////////////////////////////////////////////////////////////////
    function getFutureCountByLesserDate($date)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE is_correct != 9 AND date < '{$date}'"
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFutureCountByCorrectByLesserDate($date)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE is_correct = 0 AND date < '{$date}'"
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }
    
    function getFutureCountByRegionIdByLesserDate($region_id,$date)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.region_id = ? AND is_correct != 9 AND date < '{$date}'"
        , array(intval($region_id))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFutureCountByRegionIdByCorrectByLesserDate($region_id,$date)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.region_id = ? AND is_correct = 0 AND date < '{$date}'"
        , array(intval($region_id))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFutureByAreaIdOrderDate($area_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ?
                                    ORDER BY date ASC"
        , array(intval($area_id))
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getFutureOrderDateByAreaIdByLesserDateByZeroSequence($area_id,$date,$sequence_name = 'daytime_shine_sequence')
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND {$this->table_name}.date < '{$date}' AND {$this->table_name}.{$sequence_name} = 0
                                    ORDER BY date DESC
                                    LIMIT 0,1"
        , array($area_id)
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFutureCountByAreaIdByLesserDate($area_id,$date)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND is_correct != 9 AND date < '{$date}'"
        , array(intval($area_id))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFutureCountByAreaIdByCorrectByLesserDate($area_id,$date)
    {
        $query = $this->db->query("SELECT COUNT(id) as count
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND is_correct = 0 AND date < '{$date}'"
        , array(intval($area_id))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }
    //dev only end///////////////////////////////////////////////////////////////////

    //future
    function insertBatchFuture($futurerData) {
        //$weatherData['created'] = date("Y-m-d H:i:s", time());
        $this->db->insert_batch($this->table_name, $futurerData);
        return;
    }

    function updateFuture($area_id,$year,$month,$day,$futurerData) {
        $this->db->where('area_id', $area_id);
        $this->db->where('year', $year);
        $this->db->where('month', $month);
        $this->db->where('day', $day);
        return $this->db->update($this->table_name, $futurerData);
    }

    function updateFutureByYMD($year,$month,$day,$futurerData) {
        $this->db->where('year', $year);
        $this->db->where('month', $month);
        $this->db->where('day', $day);
        return $this->db->update($this->table_name, $futurerData);
    }

    function updateFutureByAreaIdAndDate($area_id,$date,$futurerData) {
        $this->db->where('area_id', $area_id);
        $this->db->where('date', $date);
        return $this->db->update($this->table_name, $futurerData);
    }
}

/* End of file weather_model.php */
/* Location: ./application/models/weather_model.php */