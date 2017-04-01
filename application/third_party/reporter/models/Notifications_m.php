<?php

class Notifications_m extends  CI_Model{

    private $table = 'notifications';

    public function add($params){
        $this->db->insert($this->table, $params);
        return $this->db->insert_id();
    }
}
