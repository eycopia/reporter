<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TypeChart_m extends CI_Model {
    private $table = "type_chart";
    
    public function getTypes(){        
        $types = $this->db->query("SELECT * FROM type_chart WHERE status = 1");
        return $types->result();
    }

}

/* End of file TypeCharts_m.php */
/* Location: ./application/models/TypeCharts_m.php */