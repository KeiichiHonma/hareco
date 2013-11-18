<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Oddses
 *
 * @author    kh
 */
class Odds_model extends CI_Model
{
    var $CI;
    private $table_name            = 'oddses';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getOddsById($odds_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($odds_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getOddsByJmaId($jma_block_no)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.jma_block_no = ?"
        , array(intval($jma_block_no))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllOddsesFlipJmaId()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip','jma_block_no');
        return array();
    }

    function getAllOddses()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getOddsesOrder($order, $page)
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

    function getOddsStringByOddses($oddss)
    {
        $result = array();
        foreach($oddss as $odds) {
            $result[] = $odds->odds_name;
        }

        return implode($result, ' ');
    }

    function insertOdds($oddsData) {
        $data = array(
            'name_ja' => $oddsData['name_ja'],
            'name_en' => $oddsData['name_en'],
            'name_th' => $oddsData['name_th'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updateOdds($odds_id,$oddsData) {
        $data = array(
            'name_ja' => $oddsData['name_ja'],
            'name_en' => $oddsData['name_en'],
            'name_th' => $oddsData['name_th'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $odds_id);
        return $this->db->update($this->table_name, $data);
    }

    function getOddsId($odds_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.odds_name = ?"
            , array(trim($odds_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteOdds($odds_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $odds_id);
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

    function insertBatchOddsesOdds($oddsData) {
        $this->db->insert_batch('oddses', $oddsData);
        return;
    }
}

/* End of file odds_model.php */
/* Location: ./application/models/odds_model.php */