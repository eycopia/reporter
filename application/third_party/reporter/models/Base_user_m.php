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
    
    public function edit($idUser, $params){
        unset($params['idUser']);
        $this->db->where('idUser', $idUser)
                 ->update(self::$table, $params);
    }

    public function findByUsername($idUser){
        $sql = "SELECT * FROM %s WHERE idUser = '%d'";
        $data =  $this->db->query(sprintf($sql, self::$table, $idUser))->row();
        if(!isset($data->username)){
            throw new Exception("No se pudo encontrar el usuario $idUser");
        }

        return $data;
    }
}
