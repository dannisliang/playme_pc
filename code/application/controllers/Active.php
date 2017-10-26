<?php
class Active extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('active_lib');
    }
    
    /*
     * 活动详情
     */
    public function detail()
    {
        $id       = (int)$this->request_params('id');
        $data   = $this->active_lib->get_active_detail($id);
        $this->push_template_data(array('data'=>$data));
        $this->view('game-detail.php');
    }
    
    
    
    
    
}

