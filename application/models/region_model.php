<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Weathers
 *
 * @author    kh
 */
class Region_model extends CI_Model
{
    var $CI;
    private $table_name            = 'regions';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getRegionById($region_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($region_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllRegions()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getRegionsOrder($order, $page)
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

    function getRegionStringByRegions($regions)
    {
        $result = array();
        foreach($regions as $region) {
            $result[] = $region->region_name;
        }

        return implode($result, ' ');
    }

    function insertRegion($regionData) {
        $data = array(
            'name_ja' => $regionData['name_ja'],
            'name_en' => $regionData['name_en'],
            'name_th' => $regionData['name_th'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updateRegion($region_id,$regionData) {
        $data = array(
            'name_ja' => $regionData['name_ja'],
            'name_en' => $regionData['name_en'],
            'name_th' => $regionData['name_th'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $region_id);
        return $this->db->update($this->table_name, $data);
    }

    function getRegionId($region_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.region_name = ?"
            , array(trim($region_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteRegion($region_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $region_id);
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

    function insertBatchRegionsOdds($regionsOddsData) {
        $this->db->insert_batch('regions_odds', $regionsOddsData);
        return;
    }
}

/* End of file region_model.php */
/* Location: ./application/models/region_model.php */