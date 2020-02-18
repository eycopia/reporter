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
        $this->reporter_auth->isLogin();
        $this->reporter_auth->checkUserAccess(Permission::$ADMIN);
        $this->load->library('grocery_CRUD');
    }

    /**
     * CRUD Configure for table project
     * @return html
     */
    public function index()
    {
        try{
            $crud = new grocery_CRUD();
            $crud->set_theme('mybootstrap');
            $crud->unset_delete();//grid
            $crud->set_table('component');
            $crud->set_subject('Components');
            $crud->unset_fields('created');
            $crud->required_fields('url');
            $crud->set_relation('idReport','report','title', array('status' => 1));
            $crud->set_relation('idTypeComponent','type_component','name');
            $output = $crud->render();
            $output->title_page = 'Components for Reports';
            $output->main_content = $this->config->item('rpt_views') . 'admin';
            $this->load->view( $this->config->item('rpt_base_template'),$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}
