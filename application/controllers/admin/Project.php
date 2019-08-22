<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Project
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */

class Project extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
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
            $crud->set_table('project');
            $crud->set_subject('Projects');
            $crud->fields('name', 'template', 'slug','status');
            $crud->required_fields('name');
            $crud->callback_before_insert(array($this,'configure_slug'));
            $crud->callback_before_update(array($this,'configure_slug'));
            $crud->unset_export();
            $crud->unset_print();
            $output = $crud->render();
            $output->title_page = $this->lang->line('admin_project_title');
            $output->main_content = $this->config->item('rpt_views') . 'admin';
            $this->load->view( $this->config->item('rpt_base_template'),$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }


    public function configure_slug($post_array, $pk=null){
        $post_array['slug'] = url_title($post_array['name']);
        return  $post_array;
    }
}
