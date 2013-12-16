<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Airports
 *
 * @author    kh
 */
class Airport_model extends CI_Model
{
    var $CI;
    private $table_name            = 'airports';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getAirportById($airport_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($airport_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAirportByJmaId($jma_block_no)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.jma_block_no = ?"
        , array(intval($jma_block_no))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAirportByTodoufukenId($todoufuken_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.todoufuken_id = ?"
        , array($todoufuken_id)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAirportByTodoufuken($todoufuken)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.todoufuken_name = ?"
        , array($todoufuken)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAirportByRakutenAirportCode($rakuten_airport_code)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.rakuten_airport_code = ?"
        , array($rakuten_airport_code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllAirportsFlipJmaId()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip','jma_block_no');
        return array();
    }

    function getAllAirports()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getAllAirportsOrderRegionIdOrderRakutenTdoufukenId()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE rakuten_airport_code != 9999
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.rakuten_airport_code ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getAirportsOrder($order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_page');
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

    function getAirportStringByAirports($airports)
    {
        $result = array();
        foreach($airports as $airport) {
            $result[] = $airport->airport_name;
        }

        return implode($result, ' ');
    }

    function insertAirport($airportData) {
        $data = array(
            'name_ja' => $airportData['name_ja'],
            'name_en' => $airportData['name_en'],
            'name_th' => $airportData['name_th'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updateAirport($airport_id,$airportData) {
        $data = array(
            'name_ja' => $airportData['name_ja'],
            'name_en' => $airportData['name_en'],
            'name_th' => $airportData['name_th'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $airport_id);
        return $this->db->update($this->table_name, $data);
    }

    function getAirportId($airport_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.airport_name = ?"
            , array(trim($airport_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteAirport($airport_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $airport_id);
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

    function insertBatchAirportsOdds($airportsOddsData) {
        $this->db->insert_batch('airports_odds', $airportsOddsData);
        return;
    }
}

/* End of file airport_model.php */
/* Location: ./application/models/airport_model.php */