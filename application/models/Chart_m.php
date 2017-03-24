<?php  require_once 'Datatables/DatatablesSSP.php';

class Chart_m extends CI_Model {
    private $table = "chart";
    private $columns = array();
    private $con = null;
    public function infoTable($config){
        $config['columns'] = array(
            array('dt' => 'title', 'db' => 'title', 'type' => 'column'),
            array('dt' => 'description', 'db' => 'description', 'type' => 'column'),
            array('dt' => 'idProject', 'db' => 'idProject', 'type' => 'column')
        );
        $config['draw'] = isset ( $_REQUEST['draw'] ) ? intval( $_REQUEST['draw'] ) : 0;
        return $config;
    }

    public function getAll($config){
        $sql = "SELECT * FROM chart WHERE status = 1 ";
        $config = $this->infoTable($config);
        $sql = DatatablesSSP::getQuery($_REQUEST, $sql, $config['columns']);
        $total = $this->db->from('chart')
            ->where('status', 1)
            ->count_all_results();
        $limit  = DatatablesSSP::limit($_REQUEST, $this->columns, $sql);
        if(empty($limit)){ $sql .= " LIMIT 1, 10"; }
        else{ $sql .= " $limit";}
        $config['sqlGenerado'] = $sql;
        $query = $this->db->query($sql);
        $config['data'] = $query->result();
        $config['recordsTotal'] = $total;
        $config['recordsFiltered'] = $total;
        return  $config;
    }

    public function add($request){
        $data = array( 'title' => $request['title'],
            'description' => $request['description'],
            'idProject' => $request['idProject']
        );
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        $reports = json_decode($request['reports']);
        foreach ($reports as $report) {
            $data = array('idChart'=>$id, 
                'idReport' => $report->report,
                'idTypeChart' => $report->chart);
            $this->db->insert('charts', $data);
        }
        return;
    }

    public function getReports($idChart){
        $this->load->library('encrypt');        
        $key = $this->config->item('encryption_key');
        $sql = "select t.name,  r.title, r.sql,  s.dbName, s.host,
            s.user, s.password, s.port, s.oracle
            from charts as c
            join type_chart as t on c.idTypeChart = t.idTypeChart
            join report as r on c.idReport = r.idReport
            join server_connection as s on r.idServerConnection = s.idServerConnection
            where c.idChart = {$idChart}";
        $query = $this->db->query($sql);
        $reports = $query->result();
        foreach ($reports as $report) {

            $report->password = $this->encrypt->decode($report->password, $key);
            $this->con =  DatatablesSSP::sql_connect($report);
            $prepara = $this->con->prepare($report->sql);
            $prepara->execute();
            $report->data = $prepara->fetchAll(PDO::FETCH_ASSOC);
        }
        return $reports;
    }

}

/* End of file Chart_m.php */
/* Location: ./application/models/Chart_m.php */
