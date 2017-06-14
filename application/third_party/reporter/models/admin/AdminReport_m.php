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

    public function find($idReport){
        $sql = "SELECT * FROM {$this->table} where idReport = {$idReport}";
        $report = $this->db->query($sql);
        return $report->row();
    }


    /**
     * Add new Report
     * @param array $data
     * @throws string DB error
     */
    public function add($data){
        $sql = sprintf("INSERT INTO {$this->table} "
            ."(`idUser`,`idProject`, `idServerConnection`, `title`,"
            ."`description`,`url`,`sql`, `items_per_page`,`auto_reload`) "
            ." VALUE (%d,%d, %d, '%s','%s', '%s', \"%s\", %d, '%s')",
            $_SESSION['user_id'],
            $data['project'], $data['connection'],
            strip_tags($data['title'],$this->htmlValid),
            strip_tags($data['description'], $this->htmlValid),
            $data['url'],
            $this->validSql($data['sql']),
            $data['items'], $data['reload']);
        if( ! $this->db->query($sql) ){
            throw new Exception("Error sql", $this->db->error());
        }
        return $this->db->insert_id();
    }

    /**
     * Edit Report
     * @param array $data
     */
    public function  edit($data){
        $update = array(
            'idUser' => $_SESSION['user_id'],
            'idProject' => $data['project'],
            'idServerConnection' => $data['connection'],
            'title' => strip_tags($data['title'],$this->htmlValid),
            'description' => strip_tags($data['description'], $this->htmlValid),
            'details' => strip_tags($data['details'], $this->htmlValid),
            'url' => $data['url'],
            'sql' => $this->validSql($data['sql']),
            'columns' => $data['columns'],
            'items_per_page' => $data['items'],
            'auto_reload' => $data['reload'],
            'format_notify' => $data['format_notify']
        );
        $this->db->where('idReport', $data['idReport']);
        $this->db->update($this->table, $update);
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
        $re = '/(\b(?:update|delete|insert)\b )/mi';
        $replace = ' ';
        $sql = preg_replace($re, $replace, $sql);
        return $sql;
    }

    public function gridDefinition(){
        return array(
            'description' => '',
            'sql' => "SELECT r.idReport, r.title, r.created, p.name as project
                FROM {$this->table} as r
                join project  as p on p.idProject = r.idProject and p.status = 1
                WHERE r.status = 1
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
