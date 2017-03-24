<?php
class Notify extends  CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('notify_m');
        $this->ion_auth->isLogin();
    }

    public function search(){
        $data = $this->notify_m->search($this->input->get('q'));
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
