<?php
/**
 * Created by PhpStorm.
 * User: LANICAMA
 * Date: 24/02/2016
 * Time: 05:39 PM
 */

class VarReports  extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->ion_auth->isLogin();
    }

    /**
     * CRUD configure  for table server_connection
     * @return html
     */
    public function index()
    {
        try{
            $crud = new grocery_CRUD();

            $crud->unset_delete();//grid
            $crud->unset_fields('created');
            $crud->set_table('var_report');
            $crud->set_subject('Var for Reports');
            $crud->set_relation('idReport','report','title');
            $crud->set_relation('idVarType','var_type','name');
            $output = $crud->render();
            $output->title_page = $this->lang->line('admin_var_title');
            $output->main_content =  'admin';
            $this->load->view('template/index',$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}
