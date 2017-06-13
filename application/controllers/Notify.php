<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Notify
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Notify extends CI_Controller{

    private $formatsEmail = array('excel' => 'makeExcel', 'html'=>'makeHtml');
    private $status;
    private $addresses;
    private $report;
    const ROW_LIMIT = 500;

    public function __construct(){
        parent::__construct();
        $this->reporter_auth->isLogin();
        $this->load->model('notify_m');
        $this->load->model('notifications_m');
        $this->load->model('report_m');
        $this->load->library('Load_Component');
        $this->load->model('component_m');
        $this->load->library('email');
    }

    public function send_email($idReport){
        $config = $this->config->item('config_email');
        $this->email->initialize($config);
        $this->status = array('idReport' => $idReport, 'addresses' => null);
        $this->report = $this->report_m->find($idReport);
        $this->report_m->loadReport($idReport);
        $this->addresses = $this->getAddresses($idReport);
        $this->email->from(
            $this->config->item('sender_email'),
            $this->config->item('sender_name')
        );
        $this->email->to($this->addresses);
        $this->email->subject($this->report->title);

        $function = $this->formatsEmail[$this->report->format_notify];
        call_user_func(array($this, $function));
        if($this->email->send()){
            echo "Se envio el email";
            $this->status['addresses'] = join(',',$this->addresses);
        }else{
            $error = $this->email->print_debugger();
            $this->status['log'] = $this->config->item('send_email_error')
                . " :  $error";
            echo $error;
        }
        $this->notifications_m->add($this->status);
    }

    private function getAddresses($idReport){
        if((ENVIRONMENT == 'development')){
            return array( $this->config->item('tester_email') );
        }
        $addresses = array();
        $people = $this->notify_m->getPeopleToNotifyByReport($idReport);
        foreach($people as $p){
            array_push($addresses, $p->email);
        }
        if(count($addresses) == 0){
            throw new Exception("there aren't emails");
        }
        return $addresses;
    }

    /**
     * Make the html table to send
     */
    private function makeHtml(){
        $params = array('model' => $this->report_m, 'idReport' => $this->report->idReport);
        $this->load->library('Large_Download', $params);
        $html = $this->getEmailMessage();
        $rowLimit = $this->setDataTable($html);
        $html .= " </html>";
        if($rowLimit){
            $this->sendByExcel($html);
        }else{
            $this->email->message($html);
        }
    }

    /**
     * Make the file to send
     * @param string $html
     */
    private function makeExcel($html=''){
        $components = $this->component_m->getComponentDownload($this->report->idReport);
        $totalComp = count($components);
        if(empty($html) && count($totalComp) == 0){
            $params = array('model' => $this->report_m, 'idReport' => $this->report->idReport);
            $this->load->library('Large_Download', $params);
            $html = $this->getEmailMessage();
            $html .= '<p>Reporte generador el: '.date('Y-m-d H:i:s') . '</p>';
        }else{
            $this->large_download->restart();
        }

        if( $totalComp > 0 ) {
            $obj = $this->load_component->getInstance($components);
            $file = $obj->save($this->report->idReport);
            $extension = $components->fileExtension;
            $filename = $components->fileName . date('Ymd_His').'.'.$extension;
        }else{
            $file = $this->large_download->save();
            $filename = 'report'.date('Ymd_His'). ".csv";
            $extension = 'csv';
        }

        $this->email->message($html);
        $this->email->attach($file, 'attachment', $filename, 'application/'.$extension);
    }

    private function getEmailMessage(){
        return "<!DOCTYPE html> <html>
                <meta charset='utf-8'>
                <head>
                    <title>{$this->report->title}</title>
                </head> <body>
                  <p>{$this->report->description}</p>";
    }

    private function setDataTable(&$html){
        $data = $this->large_download->getData();
        $fields = $this->large_download->getFields(array_keys($data[0]));
        $tableHead = "<th> ". join('</th><th>', $fields) . "</th>";
        $table = "<table style='border-collapse: collapse;' border='1'>
                  <thead> <tr style='background-color:red; color: white; text-align: center;'> {$tableHead} </tr> </thead>
                  <tbody>";
        $total = count($data);
        $indice = 1;
        $rowLimit = false;
        while(count($data) > 0){
            foreach($data as $row){
                if( $indice > self::ROW_LIMIT ){
                    $html .= "<p> <strong>{$this->lang->line('send_email_error_html')}</strong></p>";
                    $rowLimit = true;
                    break;
                }
                $table .= "<tr><td style='max-width: 400px'>".join("</td><td style='max-width: 400px'>", $row)."</td></tr>";
                $indice++;
            }
            $data = $this->large_download->getData();
            $total += count($data);
        }
        $html .= $table . "</tbody> </table> </body>";
        return $rowLimit;
    }
}

