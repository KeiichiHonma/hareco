<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Leisure
 *
 * @author    kh
 */
class Leisure_model extends CI_Model
{
    var $CI;
    private $table_name            = 'leisures';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getLeisureById($leisure_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($leisure_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getLeisureByJmaId($jma_block_no)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.jma_block_no = ?"
        , array(intval($jma_block_no))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getLeisureByTodoufukenId($todoufuken_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.todoufuken_id = ?"
        , array($todoufuken_id)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getLeisureByTodoufuken($todoufuken)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.todoufuken_name = ?"
        , array($todoufuken)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getLeisureByRakutenLeisureCode($rakuten_leisure_code)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.rakuten_leisure_code = ?"
        , array($rakuten_leisure_code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllLeisureFlipJmaId()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip','jma_block_no');
        return array();
    }

    function getAllLeisure()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getAllLeisureOrderRegionIdOrderRakutenTdoufukenId()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE rakuten_leisure_code != 9999
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.rakuten_leisure_code ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getLeisureOrder($order, $page)
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

    function getLeisureStringByLeisure($leisures)
    {
        $result = array();
        foreach($leisures as $leisure) {
            $result[] = $leisure->leisure_name;
        }

        return implode($result, ' ');
    }

    function insertLeisure($leisureData) {
        $data = array(
            'name_ja' => $leisureData['name_ja'],
            'name_en' => $leisureData['name_en'],
            'name_th' => $leisureData['name_th'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updateLeisure($leisure_id,$leisureData) {
        $data = array(
            'name_ja' => $leisureData['name_ja'],
            'name_en' => $leisureData['name_en'],
            'name_th' => $leisureData['name_th'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $leisure_id);
        return $this->db->update($this->table_name, $data);
    }

    function getLeisureId($leisure_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.leisure_name = ?"
            , array(trim($leisure_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteLeisure($leisure_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $leisure_id);
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

    function insertBatchLeisureOdds($leisuresOddsData) {
        $this->db->insert_batch('leisures_odds', $leisuresOddsData);
        return;
    }
}

/* End of file leisure_model.php */
/* Location: ./application/models/leisure_model.php */