<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tags
 *
 * @author    kh
 */
class Tag_model extends CI_Model
{
    var $CI;
    private $table_name            = 'tags';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getTagById($tag_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($tag_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllTags()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.region_id,{$this->table_name}.id ASC"
        );

        //if ($query->num_rows() != 0) return $query->result();
        if ($query->num_rows() != 0) return $query->result('flip');
        return array();
    }

    /**
     * get tagged comics
     *
     * @param    array tags
     * @return    array
     */
    function getTagsByTagNames($tags)
    {
        if(empty($tags)){
            return array();
        }
        $result = array();
        $queryParameter = array();
        $where = array();
        for ($index = 0; $index < count($tags); $index++) {
            $where[] = "tag_name LIKE ?";
            // like parameter
            $queryParameter[] = "%{$tags[$index]}%";
        }
        $where = implode($where, ' AND ');
        $queryString = "SELECT * FROM tags WHERE {$where}";
        $query = $this->db->query($queryString,$queryParameter);
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
}

/* End of file tag_model.php */
/* Location: ./application/models/tag_model.php */