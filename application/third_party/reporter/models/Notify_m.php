<?php
class Notify_m extends CI_Model {
    public function getPeopleToNotifyByReport($idReport){
        $sql = "SELECT email FROM notify_report as rn
          join notify as n on n.idNotify = rn.idNotify
          where rn.idReport = $idReport and n.status = 1";
        $rs = $this->db->query($sql);
        return $rs->result();
    }

    /**
     * Search by name of email
     * @param string $q texto to search
     */
    public function search($q){
        $sql = "SELECT * FROM notify
            WHERE email like '{$q}%' or full_name like '{$q}%'
                  and status = 1
            LIMIT 20";
        $query = $this->db->query($sql);
        return $query->result();
    }
}