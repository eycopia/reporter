<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Server_m
 * @package Reporter\Models
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Server_m extends CI_Model{
    private $table = 'server_connection';

    public function getServers(){
        $q = $this->db->query("SELECT idServerConnection, name  FROM {$this->table} WHERE status=1");
        return $q->result();
    }

    public function find($idServer){
        $query = $this->db->query("SELECT * FROM {$this->table}".
        " WHERE idServerConnection  = $idServer");
        return $query->row();
    }

    public function getDbConnection($idServer){
        $this->load->library('encrypt');
        $server = $this->find($idServer);
         if(trim($server->password) != ''){
             $key = $this->config->item('encryption_key');
             $server->password = $this->encrypt->decode($server->password, $key);
         }
        return $this->connectDB($server);
    }

    public function connectDB($server){
        $driver = is_null($server->oracle) ? "mysqli" : "oci8";
        $config = array(
            'hostname' => empty($server->oracle)? $server->host : $server->oracle,
            'username' => $server->user,
            'password' => $server->password,
            'database' => $server->dbName,
            'dbdriver' => $driver,
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci'
        );
        return $this->load->database($config, TRUE);
    }
}
