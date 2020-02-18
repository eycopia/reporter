<?php
/**
 * Class ModelReporter
 * Adaptador de DB_Result
 * @package Reporter\Core
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class ModelReporter implements  interfaceAccessDb
{
    /**
     * @var Access to data base
     */
    private $conn;

    /**
     * @param $sql
     *
     * @return mixed
     */
    function row($sql)
    {
        $query = $this->conn->query($sql);
        if(!$query){
            return array();
        }
        return $query->row();
    }

    /**
     * Retorna los datos en arrays assiativos
     *
     * @param string $sql
     *
     * @return array
     */
    function result_array($sql)
    {
        $query = $this->conn->query($sql);
        if(!$query){
            return array();
        }
        return $query->result_array();
    }

    /**
     * Retorna los datos en objetos
     *
     * @param string $sql
     *
     * @return array
     */
    function result($sql)
    {
        $query = $this->conn->query($sql);
        if(!$query){
            return array();
        }
        return $query->result();
    }

    function setDbConnection($dbConnection=null)
    {
        if(is_null($dbConnection)){
            $CI =& get_instance();
            $this->conn = $CI->db;
        }else {
            $this->conn = $dbConnection;
        }
    }

    function getConn(){
        return $this->conn;
    }

}
