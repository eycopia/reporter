<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Notifications_m
 * @package Reporter\Models
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Notifications_m extends  CI_Model{

    private $table = 'notifications';

    public function add($params){
        $this->db->insert($this->table, $params);
        return $this->db->insert_id();
    }
}
