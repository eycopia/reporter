<?php

/**
 * Class CustomReport
 * Shortcut para crear una grilla personalizada
 * [DEPRECATED], use Grid_Controller
 * @package Reporter\Core
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class CustomReport extends Grid_Controller
{
    
    /**
     * The report information
     * @var array
     */
    protected $report;
    private $idReport;
    
    public function __construct($idReport=null, $idProject)
    {
        parent::__construct();
        $this->idReport = $idReport;
        $this->idProject = null;
    }
    
    public function index(){
        $data = $this->getGridDefinition($this->idReport, $this->idProject);
        $this->load->view($data['template'], $data);
    }

    public function show($id=null){
        parent::show($this->idReport);
    }
    
    public function download($id=null)
    {
        parent::download($this->idReport);
    }

}
