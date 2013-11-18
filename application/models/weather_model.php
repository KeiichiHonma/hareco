<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Weathers
 *
 * @author    kh
 */
class Weather_model extends CI_Model
{
    var $CI;
    private $table_name            = 'weathers';
    private $columns = "id, code, date, year, month, day, region_id,  todoufuken_id, area_id, daytime, night,yesterday_night, is_daytime_shine, is_night_shine,is_yesterday_night_shine, is_rain, is_snow,is_yesterday_snow, temperature_max, temperature_min";
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }
    
    function getWeatherByAreaIdByMonthByDay($area_id,$month,$day)
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND {$this->table_name}.month = ? AND {$this->table_name}.day = ?"
        , array(intval($area_id),intval($month),intval($day))
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getWeatherByAreaIdByYearByMonthByDay($area_id,$year,$month,$day)
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND {$this->table_name}.year = ? AND {$this->table_name}.month = ? AND {$this->table_name}.day = ?"
        , array(intval($area_id),intval($year),intval($month),intval($day))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }
    
    //前日の天気と日付でデータ取得
    function getWeatherByAreaIdByYesterdayNightByMonthByDay($area_id,$yesterday_night,$month,$day)
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND {$this->table_name}.yesterday_night = ? AND {$this->table_name}.month = ? AND {$this->table_name}.day = ?"
        , array(intval($area_id),$yesterday_night,intval($month),intval($day))
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    //前日の天気の先頭文字と日付でデータ取得
    function getWeatherByAreaIdByHeadtByMonthByDay($area_id,$head,$month,$day,$from_year = 1993)
    {
        $query = $this->db->query("SELECT {$this->columns}
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ? AND {$this->table_name}.yesterday_night LIKE '{$head}%' AND {$this->table_name}.month = ? AND {$this->table_name}.day = ? AND {$this->table_name}.year >= ?"
        , array($area_id,$month,$day,$from_year)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getWeatherById($weather_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($weather_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllWeathers()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getWeathersOrder($order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_manage_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
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

    function getWeatherStringByWeathers($weathers)
    {
        $result = array();
        foreach($weathers as $weather) {
            $result[] = $weather->weather_name;
        }

        return implode($result, ' ');
    }

    function insertWeather($weatherData) {
        $weatherData['created'] = date("Y-m-d H:i:s", time());
        $this->db->insert($this->table_name, $weatherData);
        return $this->db->insert_id();
    }

    function insertBatchWeather($weatherData) {
        //$weatherData['created'] = date("Y-m-d H:i:s", time());
        $this->db->insert_batch($this->table_name, $weatherData);
        return;
    }

    function updateWeather($area_id,$year,$month,$day,$weatherData) {
        $this->db->where('area_id', $area_id);
        $this->db->where('year', $year);
        $this->db->where('month', $month);
        $this->db->where('day', $day);
        return $this->db->update($this->table_name, $weatherData);
    }

    function getWeatherId($weather_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.weather_name = ?"
            , array(trim($weather_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteWeather($weather_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $weather_id);
        $this->db->delete($this->table_name);

        if ($needTransaction) {
            // check transaction succeeded.
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
        }
    }

    function getWeathersOrderByAreaId($area_id)
    {
        $query = $this->db->query("SELECT {$this->columns},precipitation_one_hour,snowfall
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ?
                                    ORDER BY {$this->table_name}.date ASC"
        , array($area_id)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
}

/* End of file weather_model.php */
/* Location: ./application/models/weather_model.php */