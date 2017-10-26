<?php
class Test_lib extends Base_lib {
    public function __construct() {
        parent::__construct();
        $this->CI->load->model('test_model');
    }
    
    public function index()
    {
        return $this->CI->test_model->index();
    }

    
}

