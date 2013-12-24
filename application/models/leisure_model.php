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

    function getLeisuresByRegionIdOrderKanaIndex($region_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.region_id = ?
                                    ORDER BY {$this->table_name}.kana_index,{$this->table_name}.id ASC"
        , array($region_id)
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getLeisuresByTodoufukenIdOrderKanaIndex($todoufuken_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.todoufuken_id = ?
                                    ORDER BY {$this->table_name}.kana_index,{$this->table_name}.id ASC"
        , array($todoufuken_id)
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getAllLeisures()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    function getLeisuresOrder($order, $page)
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
}

/* End of file leisure_model.php */
/* Location: ./application/models/leisure_model.php */