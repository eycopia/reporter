<?php
class Notify extends  CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->reporter_auth->isLogin();
        $this->load->model('notify_m');
    }

    public function search(){
        $data = $this->notify_m->search($this->input->get('q'));
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
