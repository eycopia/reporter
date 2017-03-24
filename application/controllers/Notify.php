<?php
class Notify extends CI_Controller{

    private $formatsEmail = array('excel' => 'sendByExcel', 'html'=>'sendByHtml', 'pdf'=>'sendByPdf');
    private $status;
    private $addresses;
    private $report;
    public function __construct(){
        parent::__construct();
//        $this->load->library('Excel');
        $this->load->model('notify_m');
        $this->load->model('report_m');
        $this->load->library('Load_Component');
        $this->load->model('component_m');
        $this->load->model('notifications_m');
    }

    public function send_email($idReport){
        echo "Empieza el reporte<br>\n";
        $this->status = array('idReport' => $idReport, 'addresses' => null);
        $this->report = $this->report_m->find($idReport);
        $this->addresses = $this->getAddresses($idReport);
        $this->status['addresses'] = join(',',$this->addresses);
        $function = $this->formatsEmail[$this->report->format_notify];
        call_user_func(array($this, $function));
        $this->notifications_m->add($this->status);
    }

    private function getAddresses($idReport){
        if((ENVIRONMENT == 'development')){
            return array('jlcopias@indracompany.com');
        }
        $addresses = array();
        $people = $this->notify_m->getPeopleToNotifyByReport($idReport);
        foreach($people as $p){
            array_push($addresses, $p->email);
        }
        if(count($addresses) == 0){
            throw new Exception('No existen correos a quienes notificar');
        }
        return $addresses;
    }

    private function sendByHtml(){
        $params = array('model' => $this->report_m, 'idReport' => $this->report->idReport);
        $this->report_m->loadReport($this->report->idReport);
        $this->load->library('Large_Download', $params);
        $data = $this->large_download->getData();
        $fields = $this->large_download->getFields(array_keys($data[0]));
        $tableHead = "<th> ". join('</th><th>', $fields) . "</th>";
        $html = "<!DOCTYPE html> <html> <head>
                    <title>{$this->report->title}</title>
                </head> <body>
                    {$this->report->description} <br>
                  <table style='border-collapse: collapse;' border='1'>
                  <thead> <tr style='background-color:red; color: white; text-align: center;'> {$tableHead} </tr> </thead>
                  <tbody>";
        $total = count($data);
        $cortado = false;
        while(count($data) > 0){
            if($total > 150) {$cortado = true; break;}
            foreach($data as $row){
                $html .= "<tr><td style='max-width: 400px'>".join("</td><td style='max-width: 400px'>", $row)."</td></tr>";
            }
            $data = $this->large_download->getData();
            $total += count($data);
        }
        $html .= "</tbody> </table> </body> </html>";
        if($cortado){
            $html .= "<br> <strong>Este reporte contiene mas datos, debe revisar el reportador para obtener toda la información.</strong>";
        }
        $this->load->library('EmailWs');
        $subject = $this->report->title;
        $this->emailws->send(
            $subject,
            $this->addresses,
            $html);
        echo "hecho";
        return;
    }

    private function sendByExcel(){
        try{
            $components = $this->component_m->getComponentDownload($this->report->idReport);
            if(count($components) > 0 ) {
                $obj = $this->load_component->getInstance($components);
                $file = base64_encode($obj->save($this->report->idReport));
                $filename = $components->fileName . date('Ymd_His').'.'.$components->fileExtension;
            }else{
                $params = array('model' => $this->report_m, 'idReport' => $this->report->idReport);
                $this->report_m->loadReport($this->report->idReport);
                $this->load->library('Large_Download', $params);
                $file = base64_encode($this->large_download->save());
                $filename = 'report'.date('Ymd_His'). ".csv";
            }
            $this->load->library('EmailWs');
            $subject = $this->report->title;
            $this->emailws->send_attach(
                $subject,
                $this->addresses,
                'Reporte generador el: '.date('Y-m-d H:i:s'),
                $file,
                $filename);
            echo "<br>Notificacion enviada";
        }catch (Exception $e){
            echo "Errores, en la notificacion, revise la tabla notifications";
            $this->status['log'] = $e->getMessage();
        }
        return;
    }
}

