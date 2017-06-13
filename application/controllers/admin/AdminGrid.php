<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class AdminGrid
 * @package Reporter\Controllers
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
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
            'table' =>  $records
        );
        $view = $this->config->item('rpt_base_template');
        $this->load->view($view, $data);
    }
}
