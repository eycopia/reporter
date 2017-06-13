<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Notify_Report
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */

class Notify_Report extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->reporter_auth->isLogin();
        $this->reporter_auth->checkAdmin();
        $this->load->helper('url');
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
            $crud->set_table('notify');
            $crud->set_subject('Notify Report');
            $crud->unset_fields('created');
            $crud->required_fields('email');
            $crud->set_relation_n_n("reportes","notify_report","report","idNotify", "idReport","title");
            $output = $crud->render();
            $output->title_page = 'Notify Report to';
            $output->main_content = $this->config->item('rpt_views') . 'admin';
            $this->load->view( $this->config->item('rpt_base_template'),$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}
