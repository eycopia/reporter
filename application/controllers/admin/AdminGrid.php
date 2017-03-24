<?php
/**
 * Created by PhpStorm.
 * User: LANICAMA
 * Date: 22/02/2016
 * Time: 04:30 PM
 */

class AdminGrid extends CI_Controller{

    /**
     * The grid administration
     * @return view
     */
    public function show(){
        $data = $this->model->dataGrid();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index(){
        $records = $this->model->bodyGrid();
        $data = array(
            'title_page' => $this->title_page,
            'main_content' => $this->index_view,
            'table' =>  $records,
            'custom_js_files' => array(base_url('assets/js/report/main.js')),
        );
        $this->load->view('template/index', $data);
    }
}
