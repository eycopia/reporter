<?php

/**
 * Created by PhpStorm.
 * User: LANICAMA
 * Date: 07/03/2016
 * Time: 10:51 AM
 */

class VarTypes_m extends CI_Model{

    private $table = 'var_type';

    public function getTypes(){
        $types = $this->db->query("SELECT * FROM {$this->table}");
        return $types->result();
    }

}