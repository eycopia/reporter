<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class VarTypes_m
 * @package Reporter\Models
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class VarTypes_m extends CI_Model{

    private $table = 'var_type';

    public function getTypes(){
        $types = $this->db->query("SELECT * FROM {$this->table}");
        return $types->result();
    }

}
