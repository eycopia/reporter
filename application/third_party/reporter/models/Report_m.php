<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Report_m
 * @package Reporter\Models
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Report_m extends CI_Model {

    private $table = "report";

    protected $con;

    public $columns = array();

    protected $report = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function getResources(){
        return array(
            'normal' => 'Default',
            'construct' => 'Custom Constructor',
            'model' => 'Custom Model',
            'external' => 'External Report',
            'embedded' => 'Report Embedded'
        );
    }

    public function find($idReport){
        $sql = "SELECT  r.idReport,  r.idUser, r.idServerConnection,  
                    r.title,  r.url,  r.sql, r.description,  r.details, r.cron_notify, 
                    r.auto_reload, r.format_notify,  r.slug,  r.status, r.columns, 
                    rp.idReportPerformance, rp.pagination, rp.items_per_page, 
                    rp.field_for_paginate, rp.resource 
                FROM {$this->table} as r                
			    LEFT JOIN report_performance as rp on r.idReport = rp.idReport
                WHERE r.idReport = {$idReport} and r.status = 1";

        $report = $this->db->query($sql);
        $r = $report->row();

        $sql2 = "SELECT p.name as project, p.idProject, p.template, p.slug
                FROM  project as p 
                LEFT JOIN reports_by_project as rp on p.idProject = rp.idProject
                WHERE rp.idReport = {$idReport}";
        $projects = $this->db->query($sql2);
        $r->projects = $projects->result();
        return $r;
    }

    public function search($q){
        $query = "FROM report
            WHERE lower(title) like '".strtolower(trim($q))."%' and status = 1";
        $rs = $this->db->query("SELECT * ".$query);
        $qTotal = $this->db->query("SELECT count(*) as total ".$query);
        $count = $qTotal->row();
        $data = array('recordsTotal' => $count->total, 'data' => $rs->result() );
        return $data;
    }

    public function getReportsByProject($idProject){
        $q = $this->db->query("SELECT r.idReport, rbp.idProject, r.url,
            r.idServerConnection, r.title, r.description
            FROM {$this->table} as r
            LEFT JOIN reports_by_project as rbp on rbp.idReport = r.idReport
            LEFT JOIN project as p on p.idProject = rbp.idProject
            WHERE rbp.idProject = {$idProject} and p.status = 1 and r.status = 1");
        return $q->result();
    }


    public function declareVars($idReport)
    {
        $sql = "SELECT vr.name as 'name', vr.label, vt.name  as 'type', vr.default, vt.frontendClass
            FROM var_report as vr
            JOIN var_type as vt on vr.idVarType = vt.idVarType
            WHERE vr.idReport = {$idReport} and vr.status = 1";
        $vars = $this->db->query($sql);
        return $vars->result_array();
    }

}
