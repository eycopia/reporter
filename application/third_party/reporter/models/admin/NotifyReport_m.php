<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class NotifyReport_m
 * @package Reporter\Models
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class NotifyReport_m extends CI_Model
{
    private $table = 'notify_report';

    /**
     * @param array $ids
     * @param int $idReport the report
     */
    public function save($ids, $idReport){
        $people = $this->getPeopleToNotify($idReport);
        if(count($people) > 0){
            $this->processEdit($ids, $people, $idReport);
        }else{
            foreach($ids as $idNotify){
                $this->add($idNotify, $idReport);
            }
        }
    }

    /**
     * Get the all people to notify by report
     * @param  int $idReport
     * @return mixed
     */
    public function getPeopleToNotify($idReport){
        $sql = "SELECT n.*
                FROM  notify  as n
                JOIN {$this->table} as nr on nr.idNotify = n.idNotify
                WHERE nr.idReport = $idReport and n.status = 1";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert a new record on table notify_report
     * @param $idNotify the notify
     * @param $idReport the report
     */
    public function add($idNotify, $idReport){
        $params = array(
            'idNotify' => $idNotify,
            'idReport' => $idReport
        );
        $this->db->insert($this->table, $params);
    }

    /**
     * Determina si debe agregar o eliminar las personas a notificar
     * @param $ids
     * @param $people
     * @param $idReport
     */
    private function processEdit($ids, $people, $idReport){
        $lastIds = array_map(array(__CLASS__, "getIds"), $people);
        $new = array_diff($ids, $lastIds);
        $older = array_diff($lastIds, $ids);
        if(count($older) > 0){
            foreach($older as $idNotify){
                $this->db->where('idNotify',$idNotify)
                    ->where('idReport', $idReport)
                    ->delete($this->table);
            }
        }else{
            foreach ($new as $idNotify) {
                $this->add($idNotify, $idReport);
            }
        }
    }

    private function getIds($row){
        return $row->idNotify;
    }
}
