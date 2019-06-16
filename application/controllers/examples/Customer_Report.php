<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Report
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Customer_Report extends Grid_Controller {

	public function __construct(){
		parent::__construct('examples/Customer_m');
	}

	public function index($idReport, $idProject){
 	    $data = $this->getGridDefinition($idReport, $idProject);
 	    $this->load->view($data['template'], $data);
    }
}
