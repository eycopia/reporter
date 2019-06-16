<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Report
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Report extends Grid_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function grid($id, $idProject=null){
	    $replace = ['data_url' => site_url('report/show/'.$id)];
 	    $data = $this->getGridDefinition($id, $idProject, $replace);
 	    $this->load->view($data['template'], $data);
	}
}
