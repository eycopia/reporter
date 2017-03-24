<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Report Generator CRUD
 *
 * CRUD for table Project
 *
 * Copyright (C) 2016 Tiricaya.com
 *
 *
 * @package    	Admin
 * @copyright  	Copyright (c) 2016, Jorge Copia Silva
 * @license
 * @version    	1.0.0
 * @author     	Jorge Luis Copia Silva <eycopia@gmail.com>
 */

class Project extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->ion_auth->validateAdminUser();
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
            $crud->set_table('project');
            $crud->set_subject('Projects');
            $crud->fields('name', 'template', 'slug','status');
            $crud->required_fields('name');
            $crud->callback_before_insert(array($this,'configure_slug'));
            $crud->callback_before_update(array($this,'configure_slug'));
            $output = $crud->render();
            $output->title_page = $this->lang->line('admin_project_title');
            $output->main_content =  'admin';
            $this->load->view('template/index',$output);
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }


    public function configure_slug($post_array, $pk=null){
        $post_array['slug'] = url_title($post_array['name']);
        return  $post_array;
    }
}
