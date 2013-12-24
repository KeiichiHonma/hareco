<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Weathers
 *
 * @author    kh
 */
class Todoufuken_model extends CI_Model
{
    var $CI;
    private $table_name            = 'todoufuken';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getTodoufukenById($todoufuken_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($todoufuken_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllTodoufuken()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }
    
    function getAllTodoufukenOrderRegionId()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.region_id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
}

/* End of file todoufuken_model.php */
/* Location: ./application/models/todoufuken_model.php */