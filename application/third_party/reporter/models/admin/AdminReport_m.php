<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class AdminReport_m *
 * @package Reporter\Models
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */

class AdminReport_m extends Grid implements interfaceGrid{
    private $table = "report";
    private $htmlValid = '<p><a><strong><ul><li><h1><h2><h3><h4><div><span><ol><img><hr><b><i>';
    
    public function __construct()
    {
        parent::__construct(new ModelReporter());
    }
    
    
    /**
     * Add new Report
     * @param array $data
     * @throws string DB error
     */
    public function add($data){
        $userId = $this->reporter_auth->get_user_id();
        $sql = sprintf("INSERT INTO {$this->table} "
        ."(`idUser`, `idServerConnection`, `title`,"
            ."`description`,`url`,`sql`) "
                ." VALUE (%d,%d, '%s','%s', '%s', \"%s\")",
                $userId, $data['connection'],
                strip_tags($data['title'],$this->htmlValid),
                strip_tags($data['description'], $this->htmlValid),
                $data['url'],
                $this->validSql($data['sql']));
        if( ! $this->db->query($sql) ){
            throw new Exception("Error sql", $this->db->error());
        }        
        return $this->db->insert_id();
    }
    
    public function addProjects($idReport, $projects){
        foreach($projects as $p ){
            $params = array('idProject' => $p, 'idReport' => $idReport);
            $this->db->insert('reports_by_project', $params);
        }
    }
    
    public function editProjects($idReport, $projects){
        $this->db->where('idReport', $idReport)
        ->delete('reports_by_project');
        $this->addProjects($idReport, $projects);
    }
    
    /**
     * Edit Report
     * @param array $data
     */
    public function  edit($data){
        echo "<pre>";print_r($data);
        $update = array(
            'idUser' => $this->reporter_auth->get_user_id(),
            'idServerConnection' => $data['connection'],
            'title' => strip_tags($data['title'],$this->htmlValid),
            'description' => strip_tags($data['description'], $this->htmlValid),
            'details' => strip_tags($data['details'], $this->htmlValid),
            'url' => $data['url'],
            'sql' => $this->validSql($data['sql']),
            'columns' => $data['columns'],
            'auto_reload' => $data['reload'],
            'format_notify' => $data['format_notify']
        );
        
        $this->db->where('idReport', $data['idReport']);
        $this->db->update($this->table, $update);
    }
    
    public function setPerformance($idReport, $data){
        $data['field_for_paginate'] = $this->validSql($data['field_for_paginate']);
        $query = $this->db->query("SELECT * FROM report_performance where idReport = $idReport");
        $performance = $query->row();
        
        if(isset($performance->idReportPerformance)){
            $this->db->where('idReportPerformance', $performance->idReportPerformance)
            ->update("report_performance", $data);
        }else{
            $data['idReport'] = $idReport;
            $this->db->insert("report_performance", $data);
            
        }
    }
    
    public function delete($idReport){
        $this->db->where('idReport', $idReport)
        ->update($this->table, array('status' => 0));
    }
    
    
    /**
     * @param $sql
     * @return string
     */
    private function validSql($sql){
        $re = '/(\b(?:update|delete|insert|create|drop|alter)\b )/mi';
        $replace = ' ';
        $sql = preg_replace($re, $replace, $sql);
        return strip_tags($sql, $this->htmlValid);
    }
    
    public function gridDefinition(){
        return array(
            'description' => '',
            'sql' => "SELECT r.idReport, r.title, r.created, group_concat(p.name order by p.name asc separator  ', ' ) as project
                FROM {$this->table} as r
                left join reports_by_project as rp on r.idReport  = rp.idReport
                left join project  as p on rp.idProject = p.idProject and p.status = 1
                WHERE r.status = 1
                GROUP BY r.idReport
                ORDER BY r.idReport desc",
                'data_url' => site_url('admin/report/show/'),
                'database' => 'mysql',
                'filters' => 'basic',
                'columns' => $this->getColumns(),
                'links' => array( array(
                    'fileName' => '<span class="fa fa-plus-circle"></span> '.$this->lang->line('btn_new_report'),
                    'fileExtension' => site_url('admin/report/add'),
                    'nameClass' => 'btn btn-primary'))
                );
    }
    
    /**
     * Setea las columns de la grilla
     */
    public function getColumns(){
        return array(
            array('dt' => 'Item', 'db' => 'idReport', 'table' => 'r'),
            array('dt' => 'Title', 'db' => 'title', 'table' => 'r'),
            array('dt' => 'Project', 'db' => 'project'),
            array('dt' => 'Created', 'db' => 'created'),
            array('dt' => 'Action', 'db' => 'idReport',
                "formatter" => function($d){
                return "<a class='btn btn-success' href='".site_url('admin/report/edit/'.$d)."'>
                    <i class=\"fa fa-edit\"></i> Edit</a>
                    <a class='btn btn-danger' href='".site_url('admin/report/del/'.$d)."'>
                    <i class=\"fa fa-trash\"></i> Delete</a>";
                })
            );
    }
}
