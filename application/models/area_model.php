<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Areas
 *
 * @author    kh
 */
class Area_model extends CI_Model
{
    var $CI;
    private $table_name            = 'areas';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getAreaById($area_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($area_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAreaByJmaId($jma_block_no)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.jma_block_no = ?"
        , array(intval($jma_block_no))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAreaByTodoufukenId($todoufuken_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.todoufuken_id = ?"
        , array($todoufuken_id)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAreaByTodoufuken($todoufuken)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.todoufuken_name = ?"
        , array($todoufuken)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAreaByRakutenAreaCode($rakuten_area_code)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.rakuten_area_code = ?"
        , array($rakuten_area_code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllAreasFlipJmaId()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip','jma_block_no');
        return array();
    }

    function getAllAreas()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getAllAreasOrderRegionIdOrderRakutenTdoufukenId()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE rakuten_area_code != 9999
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.rakuten_area_code ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getAreasOrder($order, $page)
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

    function getAreaStringByAreas($areas)
    {
        $result = array();
        foreach($areas as $area) {
            $result[] = $area->area_name;
        }

        return implode($result, ' ');
    }

    function insertArea($areaData) {
        $data = array(
            'name_ja' => $areaData['name_ja'],
            'name_en' => $areaData['name_en'],
            'name_th' => $areaData['name_th'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updateArea($area_id,$areaData) {
        $data = array(
            'name_ja' => $areaData['name_ja'],
            'name_en' => $areaData['name_en'],
            'name_th' => $areaData['name_th'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $area_id);
        return $this->db->update($this->table_name, $data);
    }

    function getAreaId($area_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_name = ?"
            , array(trim($area_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteArea($area_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $area_id);
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

    function insertBatchAreasOdds($areasOddsData) {
        $this->db->insert_batch('areas_odds', $areasOddsData);
        return;
    }
}

/* End of file area_model.php */
/* Location: ./application/models/area_model.php */