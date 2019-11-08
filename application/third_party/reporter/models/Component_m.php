<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Component_m
 *
 * @package Reporter\Models
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */

class Component_m extends CI_Model {

    private $table = 'component';

    const  COMPONENT_DOWNLOAD = 1;
    const  COMPONENT_BUTTON = 2;

    public function __construct(){
        parent::__construct();
    }

    public function findComponentDownload($id){
        $sql = "SELECT c.*, tc.name as type, tc.idTypeComponent
        FROM {$this->table} as c
        JOIN type_component as tc on c.idTypeComponent = tc.idTypeComponent
        WHERE c.idComponent = $id
        and tc.idTypeComponent = ".self::COMPONENT_DOWNLOAD;
        $components = $this->db->query($sql);
        return $components->row();
    }

    public function getComponentDownload($idReport){
        $sql = "SELECT c.*, tc.name as type, tc.idTypeComponent
        FROM {$this->table} as c
        JOIN type_component as tc on c.idTypeComponent = tc.idTypeComponent
        WHERE c.idReport = $idReport
        and tc.idTypeComponent = ".self::COMPONENT_DOWNLOAD;
        $components = $this->db->query($sql);
        return $components->row();
    }
    
    public function getButtonsForReport($idReport){
        $sql = "SELECT c.idComponent, c.definition, c.idTypeComponent, c.idReport, 
           tc.name as type_component, tc.definition as original_definition
        FROM {$this->table} as c
        JOIN type_component as tc on c.idTypeComponent = tc.idTypeComponent
        WHERE c.idReport = $idReport
        and tc.status = 1 and tc.idTypeComponent = ".self::COMPONENT_BUTTON;
        $components = $this->db->query($sql);
        return $components->result();
    }
}
