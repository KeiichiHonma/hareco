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
    private $columns = "id, code, date, year, month, day, region_id,  todoufuken_id, area_id, daytime,is_daytime_shine,daytime_number,daytime_shine_sequence,night,is_night_shine,night_shine_sequence,yesterday_night,is_yesterday_night_shine , temperature_max, temperature_min, rain_percentage, snow_percentage,holiday,holiday_sequence";
    private $start_date;
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
        $this->start_date = date("Y-n-j",strtotime("+8 day"));
    }
    
    //top slide
    function getSpringFuturesGoupByAreaByHolidayBySequenceForSlide($spring_id,$holiday = 1,$sequence = 2)
    {
        $query = $this->db->query("SELECT {$this->table_name}.id AS id,{$this->table_name}.area_id AS area_id, date, springs.id AS spring_id, springs.spring_name, daytime,is_daytime_shine,daytime_number,daytime_shine_sequence,night,is_night_shine,night_shine_sequence,yesterday_night,is_yesterday_night_shine , temperature_max, temperature_min, rain_percentage, snow_percentage,holiday,holiday_sequence
                                    FROM {$this->table_name}
                                    INNER JOIN springs ON {$this->table_name}.area_id = springs.area_id
                                    WHERE is_daytime_shine = 0 AND is_night_shine = 0 AND is_yesterday_night_shine = 0 AND date > '{$this->start_date}' AND springs.id = ? AND holiday >= ? AND holiday_sequence >= ?
                                    GROUP BY area_id"
        , array($spring_id,$holiday,$sequence)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
    /*
    top下部の連休
    札幌
    仙台
    東京
    名古屋
    大阪
    福岡
    沖縄
    */
    function getFuturesGoupByAreaByHolidayBySequenceForSlide($holiday = 2,$sequence = 2)
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE date > '{$this->start_date}' AND holiday = {$holiday} AND is_daytime_shine = 0 AND is_night_shine = 0 AND is_yesterday_night_shine = 0  AND ( area_id = 4 OR area_id = 13 OR area_id = 30 OR area_id = 25 OR area_id = 40 OR area_id = 43 OR area_id = 56)
                                    GROUP BY area_id"
        , array($holiday)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getFuturesGroupByAreaIdByHoliday()
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE date > '{$this->start_date}' AND is_daytime_shine = 0 AND holiday >= 1
                                    GROUP BY area_id"
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getFuturesByRegionIdByHoliday($region_id,$order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_manage_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.region_id = ? AND date > '{$this->start_date}' AND is_daytime_shine = 0 AND holiday >= 1
                                    ORDER BY {$this->table_name}.{$order}
                                    LIMIT {$offset},{$perPageCount}"
        , array($region_id)
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
    
    //休日+休前日限定で晴れる連休を取得
    function getFuturesByAreaIdByHolidayByYoubiBySequence($area_id,$holiday,$sequence,$order, $page,$youbi = null)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_manage_page');
        $offset = $perPageCount * ($page - 1);
        if(is_null($youbi)){
            $cond = "holiday >= $holiday";
        }else{
            $cond = "(holiday >= $holiday AND holiday_sequence >= $sequence || day_of_the_week = $youbi)";
        }
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE is_daytime_shine = 0 AND date > '{$this->start_date}' AND {$this->table_name}.area_id = ? AND $cond
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