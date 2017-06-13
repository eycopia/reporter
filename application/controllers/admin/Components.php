<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Components
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Components extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->ion_auth->isLogin();
    }

    /**
     * CRUD Configure for table project
     * @return html
     */
    public function index()
    {
        try{
            $crud = new grocery_CRUD();
            $crud->unset_delete();//grid
            $crud->set_table('component');
            $crud->set_subject('Components');
            $crud->unset_fields('created');
            $crud->required_fields('url');
            $crud->set_relation('idReport','report','title', array('status' => 1));
            $crud->set_relation('idTypeComponent','type_component','name');
            $output = $crud->render();
            $output->title_page = 'Components for Reports';
            $output->main_content =  'admin';
            $this->load->view('template/index',$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}
