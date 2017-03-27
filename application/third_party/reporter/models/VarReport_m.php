<?php
/**
 * Created by PhpStorm.
 * User: LANICAMA
 * Date: 04/03/2016
 * Time: 04:02 PM
 */

class VarReport_m extends CI_Model{

    private $table = 'var_report';

    const active = 1;


    /**
     * Return all variables for Report Id
     * @param $idReport
     * @return Object
     */
    public function getVarsReport($idReport){
        $sql = "SELECT * FROM {$this->table}"
            ." WHERE idReport = $idReport and status = ".self::active;
        $vars = $this->db->query($sql);
        return $vars->result();
    }

    /** Determina si el proceso es para un nuevo ingreso o una edicion
     * @param $data
     * @param $idReport
     * @throws Exception
     */
    public function save($data, $idReport){
        $vars = $this->getVarsReport($idReport);

        if(count($vars)> 0){
            $this->processEdit($vars, $data, $idReport);
        }else{
            foreach( $data as $var){
                $this->add($var, $idReport);
            }
        }
    }


    /**
     * Edit var repotr
     * @param array $vars
     * @param array $newData
     * @param int $idReport
     */
    public function processEdit($vars, $newData, $idReport){
        $process  = $varNames = $newVarNames = array();
        foreach ($vars as $var) {
            $varNames[$var->name] = $var;
        }

        foreach($newData as $var){
            $newVarNames[$var['name']] = $var;
        }

        $new = array_diff_key($newVarNames, $varNames);
        $older = array_diff_key($varNames, $newVarNames);

        if(count($new) == 0 ){
            $this->edit($newData, $idReport);
        }

        if(count($older) > 0){
            $names = array_keys($older);
            $sql = "UPDATE {$this->table} SET status = 0 WHERE idReport = {$idReport} and
                  status = 1 and name in ('".join('\',\'', $names)."')";
            $this->db->query($sql);
            $process = $older;
        }

        if(count($new) > 0 ){
            foreach( $new as $var){
                $this->add($var, $idReport);
            }
            $process = $new;
        }

        if(count($vars) > 0 ){
            $update = array_diff_key($newVarNames, $process);
            $this->edit($update, $idReport);
        }
    }

    /**
     * Add a new Var
     * @param $data Values to insert
     * @param $idReport Report
     * @throws string Mysql error message
     */
    public function add($data, $idReport){
        $default = str_replace(array('"',"'"), '',$data['default']);
        $sql = sprintf("INSERT INTO {$this->table}(`idReport`, `idVarType`, `name`, `label`, `default`)"
            ."VALUES($idReport, %d, '%s', '%s', '%s')", $data['idVarType'], $data['name'],
            $data['label'], $default);
        if(! $this->db->query($sql)){
            throw new Exception('Sql Error', $this->db->error());
        }
    }


    public function edit($vars, $idReport){
        foreach($vars as $var){
            $default = str_replace(array('"',"'"), '',$var['default']);
            $sql = "UPDATE {$this->table} SET idVarType = {$var['idVarType']},
                  `default` = '{$default}' WHERE idReport = $idReport and
                  status = 1 and name = '{$var['name']}' ";
            $this->db->query($sql);
        }
    }
}
