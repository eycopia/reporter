<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Report Generator
 *
 * Los metodos de administración para crear Charts
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

require_once "AdminGrid.php";

class Chart extends AdminGrid {

    /**
     * @var string Index view
     */
    protected $index_view = 'admin/chart_index';

    /**
     * @var string Title page
     */
    protected $title_page = null;


    public function __construct(){
        parent::__construct();
        $this->load->model('chart_m', 'model');
        $this->title_page = $this->lang->line('admin_chart_title');
    }

    public function add(){
        if($this->input->is_ajax_request()){
            $rs = $this->addChart();    
        }else{
            $rs = $this->prepareForm();
        }
        return $rs;
    }

    private function prepareForm(){
        $this->load->model('typeChart_m');
        $this->load->model('project_m');
        $css_files = array( base_url('assets/libs/select2/css/select2.min.css'));
        $custom_js_files = array(base_url('assets/grocery_crud/texteditor/ckeditor/ckeditor.js'),            
            base_url('assets/libs/select2/js/select2.min.js'),
            base_url('assets/js/chart/add.js')
        );      
        $data = array( 
            'title_page' => $this->lang->line('admin_chart_title'),
            'main_content' =>'admin/chart_add',
            'custom_js_files' => $custom_js_files,
            'css_files' => $css_files,
            'projects' => $this->project_m->getProjects(),
            'typesCharts' => $this->typeChart_m->getTypes(),
        );
        return $this->load->view('template/index', $data);
    }

    private function addChart(){
        $this->model->add($_POST);
        $data = array('redirect_to' => site_url('admin/chart'),
            'message' => "The chart was saved"
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

}