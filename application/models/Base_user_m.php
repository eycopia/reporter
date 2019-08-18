<?php
/**
 * Name: Base_user_m.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */
class Base_user_m extends CI_Model
{
    static $table = 'base_user';

    public function __construct()
    {
        parent::__construct();
    }

    public function add($params){
        $this->db->insert(self::$table, $params);
    }

    public function getProjects(){

    }
}