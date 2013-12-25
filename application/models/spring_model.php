<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Springs
 *
 * @author    kh
 */
class Spring_model extends CI_Model
{
    var $CI;
    private $table_name            = 'springs';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getSpringById($spring_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($spring_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getSpringsByAreaId($area_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.area_id = ?"
        , array($area_id)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getSpringByTodoufuken($todoufuken)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.todoufuken_name = ?"
        , array($todoufuken)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllSpringsFlipJalanOnsenArea()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip','jalan_o_area');
        return array();
    }

    function getAllSprings()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getAllSpringsOrderSpringAreaId()
    {
        $query = $this->db->query("SELECT *,{$this->table_name}.id AS id
                                    FROM {$this->table_name}
                                    INNER JOIN spring_areas ON {$this->table_name}.spring_area_id = spring_areas.id
                                    ORDER BY {$this->table_name}.spring_area_id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getSpringsOrderTodoufukenIdByAreaId($area_id,$flip = FALSE)
    {
        $query = $this->db->query("SELECT *,{$this->table_name}.id AS id
                                    FROM {$this->table_name}
                                    INNER JOIN spring_areas ON {$this->table_name}.spring_area_id = spring_areas.id
                                    WHERE {$this->table_name}.area_id = ?
                                    ORDER BY {$this->table_name}.todoufuken_id ASC"
        , array($area_id)
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $flip ? $query->result('flip') : $query->result();
        return array();
    }

    function getSpringsOrder($order, $page)
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

    function getSpringStringBySprings($springs)
    {
        $result = array();
        foreach($springs as $spring) {
            $result[] = $spring->spring_name;
        }

        return implode($result, ' ');
    }

    function insertSpring($springData) {
        $data = array(
            'name_ja' => $springData['name_ja'],
            'name_en' => $springData['name_en'],
            'name_th' => $springData['name_th'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updateSpring($spring_id,$springData) {
        $data = array(
            'name_ja' => $springData['name_ja'],
            'name_en' => $springData['name_en'],
            'name_th' => $springData['name_th'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $spring_id);
        return $this->db->update($this->table_name, $data);
    }

    function getSpringId($spring_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.spring_name = ?"
            , array(trim($spring_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteSpring($spring_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $spring_id);
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

    function insertBatchSpringsOdds($springsOddsData) {
        $this->db->insert_batch('springs_odds', $springsOddsData);
        return;
    }
}

/* End of file spring_model.php */
/* Location: ./application/models/spring_model.php */