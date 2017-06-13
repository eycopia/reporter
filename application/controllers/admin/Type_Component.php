<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Type_Component
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Type_Component extends CI_Controller{
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
            $crud->set_theme('mybootstrap');
            $crud->unset_delete();//grid
            $crud->set_table('type_component');
            $crud->set_subject('Type of Components');
            $crud->unset_fields('created');
            $crud->required_fields('name');
            $output = $crud->render();
            $output->title_page = 'Type of Components';
            $output->main_content =  'admin';
            $this->load->view('template/index',$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}
