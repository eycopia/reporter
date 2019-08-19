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

    public function findByUsername($username){
        $sql = "SELECT * FROM %s WHERE username = '%s'";
        $data =  $this->db->query(sprintf($sql, self::$table, $username))->row();
        if(!isset($data->username)){
            throw new Exception("No se pudo encontrar el usuario $username");
        }

        return $data;
    }
}