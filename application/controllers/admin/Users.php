<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Users
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */
class Users extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->reporter_auth->isLogin();
        $this->reporter_auth->checkAdmin();
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
            $crud->set_table('users');
            $crud->set_subject('Users');
            //$crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
            $crud->set_relation_n_n('projects', 'user_projects', 'project', 'user_id', 'idProject', 'name');
            $crud->fields('username', 'email', 'first_name', 'last_name', 'company', 'phone', 'projects');
            $crud->columns('username', 'email', 'first_name', 'last_name');
            $crud->required_fields('username','first_name', 'password');
            $crud->unset_export();
            $crud->unset_print();
            $output = $crud->render();
            $output->title_page = 'Lists of Users  ';
            $output->main_content =  $this->config->item('rpt_views') . 'admin';
            $this->load->view( $this->config->item('rpt_base_template'),$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
}
